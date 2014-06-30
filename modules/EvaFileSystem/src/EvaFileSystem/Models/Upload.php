<?php

namespace Eva\EvaFileSystem\Models;

use Eva\EvaFileSystem\Entities\Files;
use Eva\EvaUser\Models\Login as LoginModel;
use Eva\EvaEngine\Exception;
use Phalcon\Text;
use Phalcon\Tag;
use Phalcon\Http\Request\File;
use Phalcon\Mvc\Model\Validator;
use Eva\EvaEngine\Mvc\Model\Validator\Between;

class Upload extends Files
{
    public function beforeValidationOnCreate()
    {
        $config = $this->getConfig();

        $this->validate(new Validator\InclusionIn(array(
            'field' => 'fileExtension',
            'domain' => explode(',', $config->allowExtensions),
        )));

        $this->validate(new Between(array(
            'field' => 'fileSize',
            'minimum' => $config->minFileSize,
            'maximum' => $config->maxFileSize,  //20MB
        )));
    }

    public function beforeCreate()
    {
        $user = new LoginModel();
        if ($userinfo = $user->isUserLoggedIn()) {
            $this->userId = $userinfo['id'];
            $this->username = $userinfo['username'];
        }
    }

    public function validation()
    {
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    public function upload(File $file)
    {
        if ($file->getError()) {
            throw new Exception\IOException('ERR_FILE_UPLOAD_FAILED');
        }

        $originalName = $file->getName();
        $tmp = $file->getTempName();
        $fileSize = $file->getSize();
        $type = $file->getType();
        $filenameArray = explode(".", $originalName);
        $fileExtension = strtolower(array_pop($filenameArray));
        $originalFileName = implode('.', $filenameArray);
        $fileName = Tag::friendlyTitle($originalFileName);
        if ($fileName == '-') {
            $fileName = Text::random(Text::RANDOM_ALNUM, 6);
        }

        //hash file less then 10M
        if ($fileSize < 1048576 * 10) {
            $fileHash = hash_file('CRC32', $tmp, false);
        }
        if (false === strpos($type, 'image')) {
            $isImage = 0;
        } else {
            $isImage = 1;
        }

        $fileinfo = array(
            'title' => $originalFileName,
            'status' => 'published',
            'storageAdapter' => 'local',
            'originalName' => $originalName,
            'fileSize' => $fileSize,
            'mimeType' => $type,
            'fileExtension' => $fileExtension,
            'fileHash' => $fileHash,
            'isImage' => $isImage,
            'fileName' => $fileName . '.' . $fileExtension,
            'createdAt' => time(),
        );

        if ($isImage) {
            $image = getimagesize($tmp);
            $fileinfo['imageWidth'] = $image[0];
            $fileinfo['imageHeight'] = $image[1];
        }

        $filesystem = $this->getDI()->getFileSystem();

        $path = md5(microtime());
        $path = str_split($path, 2);
        $pathlevel = $this->getUploadPathLevel();
        $pathlevel > 6 ? 6 : $pathlevel;
        $path = array_slice($path, 0, $pathlevel);
        $filePath = implode('/', $path);
        $path = $filePath . '/' . $fileName . '.' . $fileExtension;

        $fileinfo['filePath'] = $filePath;

        $this->assign($fileinfo);
        if ($this->save()) {
            if (!$filesystem->has($path)) {
                if ($filesystem->write($path, file_get_contents($tmp))) {
                    unlink($tmp);
                } else {
                    throw new Exception\IOException('ERR_FILE_MOVE_TO_STORAGE_FAILED');
                }
            } else {
                throw new Exception\ResourceConflictException('ERR_FILE_UPLOAD_BY_CONFLICT_NAME');
            }
        } else {
            throw new Exception\RuntimeException('ERR_FILE_SAVE_TO_DB_FAILED');
        }
        return $this;
    }

    public function uploadByEncodedData($data, $originalName, $mimeType = null)
    {
        if (!$headPos = strpos($data, ',')) {
            throw new Exception\InvalidArgumentException('ERR_FILE_ENCODED_UPLOAD_FORMAT_INCORRECT');
        }
        $fileHead = substr($data, 0, $headPos + 1);
        $fileEncodedData = trim(substr($data, $headPos + 1));
        $data = base64_decode($fileEncodedData);

        $tmpName = Text::random(\Phalcon\Text::RANDOM_ALNUM, 6);
        $tmpPath = $this->getUploadTmpPath();
        $tmp =  $tmpPath . '/' . $tmpName;
        $adapter = new \Gaufrette\Adapter\Local($tmpPath);
        $filesystem = new \Gaufrette\Filesystem($adapter);
        $filesystem->write($tmpName, $data);

        $fileSize = filesize($tmp);
        $type = $mimeType;
        $filenameArray = explode(".", $originalName);
        $fileExtension = strtolower(array_pop($filenameArray));
        $originalFileName = implode('.', $filenameArray);
        $fileName = Tag::friendlyTitle($originalFileName);
        if ($fileName == '-') {
            $fileName = Text::random(Text::RANDOM_ALNUM, 6);
        }

        //hash file less then 10M
        if ($fileSize < 1048576 * 10) {
            $fileHash = hash_file('CRC32', $tmp, false);
        }
        if (false === strpos($type, 'image')) {
            $isImage = 0;
        } else {
            $isImage = 1;
        }

        $fileinfo = array(
            'title' => $originalFileName,
            'status' => 'published',
            'storageAdapter' => 'local',
            'originalName' => $originalName,
            'fileSize' => $fileSize,
            'mimeType' => $type,
            'fileExtension' => $fileExtension,
            'fileHash' => $fileHash,
            'isImage' => $isImage,
            'fileName' => $fileName . '.' . $fileExtension,
            'createdAt' => time(),
        );

        if ($isImage) {
            $image = getimagesize($tmp);
            $fileinfo['imageWidth'] = $image[0];
            $fileinfo['imageHeight'] = $image[1];
        }

        $filesystem = $this->getDI()->getFileSystem();

        $path = md5(time());
        $path = str_split($path, 2);
        $pathlevel = $this->getUploadPathLevel();
        $pathlevel > 6 ? 6 : $pathlevel;
        $path = array_slice($path, 0, $pathlevel);
        $filePath = implode('/', $path);
        $path = $filePath . '/' . $fileName . '.' . $fileExtension;

        $fileinfo['filePath'] = $filePath;

        $this->assign($fileinfo);
        if ($this->save()) {
            if (!$filesystem->has($path)) {
                if ($filesystem->write($path, file_get_contents($tmp))) {
                    unlink($tmp);
                } else {
                    throw new Exception\IOException('ERR_FILE_MOVE_TO_STORAGE_FAILED');
                }
            } else {
                throw new Exception\ResourceConflictException('ERR_FILE_UPLOAD_BY_CONFLICT_NAME');
            }
        } else {
            throw new Exception\RuntimeException('ERR_FILE_SAVE_TO_DB_FAILED');
        }

        return $this;
    }
}

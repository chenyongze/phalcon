<?php

namespace Wscn\Entities;

use Eva\EvaUser\Models\Login as LoginModel;

class Appoptions extends \Eva\EvaEngine\Mvc\Model
{


    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $version = 1;

    /**
     *
     * @var string
     */
    public $endpoint;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var string
     */
    public $data;

    /**
     *
     * @var integer
     */
    public $createdAt;

    /**
     *
     * @var integer
     */
    public $updatedAt;

    /**
     *
     * @var integer
     */
    public $userId;

    /**
     *
     * @var string
     */
    public $username;

    protected $tableName = 'wscn_appoptions';

    public function beforeCreate()
    {
        $this->createdAt = $this->createdAt ? $this->createdAt : time();
        $user = new LoginModel();
        if ($user->isUserLoggedIn()) {
            $userinfo = LoginModel::getCurrentUser();
            $this->userId = $this->userId ? $this->userId : $userinfo['id'];
            $this->username = $this->username ? $this->username : $userinfo['username'];
        }
    }

    public function beforeUpdate()
    {
        $this->updatedAt = $this->updatedAt ? $this->updatedAt : time();
        $user = new LoginModel();
        if ($user->isUserLoggedIn()) {
            $userinfo = LoginModel::getCurrentUser();
            $this->userId = $this->userId ? $this->userId : $userinfo['id'];
            $this->username = $this->username ? $this->username : $userinfo['username'];
        }
    }
}

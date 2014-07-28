<?php
namespace VnetWap\View\Helpers;

class WscnThumb
{
    public function __invoke($uri, $query = null, $configKey = 'default')
    {

        if ($query) {
            if (true === is_array($query)) {
                $query = implode(',', $query);
            }

            if (false !== ($pos = strrpos($uri, '.'))) {
                $uri = explode('/', $uri);
                $fileName = array_pop($uri);
                $nameArray = explode('.', $fileName);
                $nameExt = array_pop($nameArray);
                $nameFinal = array_pop($nameArray);
                $nameFinal .= ',' . $query;
                array_push($nameArray, $nameFinal, $nameExt);
                $fileName = implode('.', $nameArray);
                array_push($uri, $fileName);
                $uri = implode('/', $uri);
            }
        }


        if (\Phalcon\Text::startsWith($uri, 'http://', false) || \Phalcon\Text::startsWith($uri, 'https://', false)) {

            return str_replace('http://api.wallstreetcn.com/', 'http://thumbnail.wallstreetcn.com/thumb/', $uri);
        }


        $config = self::getDI()->getConfig();
        if (isset($config->thumbnail->$configKey->baseUri) && $baseUrl = $config->thumbnail->$configKey->baseUri) {
            return $baseUrl . $uri;
        }

        return $uri;
    }
}

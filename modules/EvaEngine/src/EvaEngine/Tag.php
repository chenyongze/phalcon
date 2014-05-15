<?php

namespace Eva\EvaEngine;

class Tag extends \Phalcon\Tag
{
    public static function config()
    {
        $config = self::getDI()->get('config');
        if(!$args = func_get_args()) {
            return $config;
        }

        $res = $config;
        foreach($args as $arg) {
            if(!isset($res->$arg)) {
                return '';
            }
            $res = $res->$arg;
        }
        return $res;
    }

    public static function component($componentName, array $params = array())
    {
        return Mvc\View::getComponent($componentName, $params);
    }

    public static function _($message = null, $replacement = null)
    {
        $translate = self::getDI()->get('translate');
        if ($message) {
            return $translate->_(trim($message), $replacement);
        }

        return $translate;
    }

    public static function flashOutput()
    {
        $flash = self::getDI()->get('flash');
        if (!$flash) {
            return '';
        }
        $messages = $flash->getMessages();
        $classMapping = array(
            'error' => 'alert alert-danger',
            'warning' => 'alert alert-warning',
            'success' => 'alert alert-success',
            'notice' => 'alert alert-info',
        );

        $messageString = '';
        $escaper = self::getDI()->get('escaper');
        foreach ($messages as $type => $submessages) {
            foreach ($submessages as $message) {
                $messageString .= '<div class="alert ' . $classMapping[$type] . '" data-raw-message="' . $escaper->escapeHtmlAttr($message) . '">' . self::_($message) . '</div>';
            }
        }

        return $messageString;

        /*
        <?if($this->flash):?>
        <?$messages = $this->flash->getMessages();?>
        <?$classMapping = array(
            'error' => 'alert alert-danger',
            'warning' => 'alert alert-warning',
            'success' => 'alert alert-success',
            'notice' => 'alert alert-info',
        );?>
        <?foreach($messages as $type => $submessages):?>
        <?foreach($submessages as $message):?>
        <div class="alert <?=$classMapping[$type]?>" data-raw-message="<?=$this->escaper->escapeHtml($message);?>"><?=$this->tag->_($message)?></div>
        <?endforeach?>
        <?endforeach?>
        <?endif?>
        */
    }

    public static function uri($uri, array $query = null, array $baseQuery = null)
    {
        $url = self::getDI()->get('url');
        if ($query && $baseQuery) {
            $query = array_merge($baseQuery, $query);
        }

        return $url->get($uri, $query);
    }

    public static function thumb($uri, $query = null, $configKey = 'default')
    {
        if (\Phalcon\Text::startsWith($uri, 'http://', false) || \Phalcon\Text::startsWith($uri, 'https://', false)) {
            return $uri;
        }

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

        $config = self::getDI()->get('config');
        if (isset($config->thumbnail->$configKey->baseUri) && $baseUrl = $config->thumbnail->$configKey->baseUri) {
            return $baseUrl . $uri;
        }

        return $uri;
    }

    /**
    * Get either a Gravatar URL or complete image tag for a specified email address.
    *
    * @param string $email The email address
    * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
    * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
    * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
    * @param boole $img True to return a complete IMG tag False for just the URL
    * @param array $atts Optional, additional key/value attributes to include in the IMG tag
    * @return String containing either just a URL or a complete image tag
    * @source http://gravatar.com/site/implement/images/php/
    */
    public static function gravatar($email, $s = 80, $d = 'mm', $r = 'g')
    {
        $url = 'http://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $email ) ) );
        $url .= "?s=$s&d=$d&r=$r";

        return $url;
    }

    /**
     * Tranform input time to time string which can be parsed by javascript
     *
     * @param int $time
     *                  @access public
     *
     * @return string javascript parse-able string
     */
    public static function jsTime($time = '' , $timezone = null)
    {
        $time = $time ? $time : time();
        $timezone = $timezone ? $timezone : self::getDI()->get('config')->datetime->defaultTimezone;
        $time = $time + $timezone * 3600;
        $prefix = $timezone < 0 ? '-' : '+';

        $zone = str_pad(str_pad(abs($timezone), 2, 0, STR_PAD_LEFT), 4, 0);

        return gmdate('D M j H:i:s', $time) . ' UTC' . $prefix . $zone . ' ' . gmdate('Y', $time);
    }

    /**
     * Tranform input time to iso time
     *
     * @param string $time
     * @param int    $timezone
     *
     * @access public
     *
     * @return string time string
     */
    public static function isoTime($time = null, $timezone = null)
    {
        $timezone = $timezone ? $timezone : self::getDI()->get('config')->datetime->defaultTimezone;
        $time = $time ? $time : time();

        return $time = gmdate('c', $time);
    }

    public static function datetime($time = '', $format = '',  $timezone = null)
    {
        $timezone = $timezone ? $timezone : self::getDI()->get('config')->datetime->defaultTimezone;
        $format = $format ? $format : self::getDI()->get('config')->datetime->defaultFormat;
        $time = $time ? $time : time();
        $time = is_numeric($time) ? $time : strtotime($time);
        $time = $time + $timezone * 3600;

        return gmdate($format, $time);
    }

}

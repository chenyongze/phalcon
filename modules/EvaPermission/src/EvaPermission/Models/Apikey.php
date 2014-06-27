<?php

namespace Eva\EvaPermission\Models;

use Eva\EvaPermission\Entities;
use Eva\EvaEngine\Exception;

class Apikey extends Entities\Apikeys
{
    public function beforeCreate()
    {
        $this->createdAt = time();
        $this->apikey = \Phalcon\Text::random(\Phalcon\Text::RANDOM_ALNUM, 8);
    }
}

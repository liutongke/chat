<?php

namespace App\Controller;

class Client
{
    public $uid;

    public function __construct($uid)
    {
        $this->uid = $uid;
    }

}
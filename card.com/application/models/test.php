<?php


class testModel extends ModelBase
{

    public static function getInstance()
    {
        return new self;
    }

    public function redis()
    {
        $this->initRedis();
    }


    public static function getCount()
    {
        static $count = 100;
        return $count--;
    }

}
<?php

/**
 * Created by PhpStorm.
 * User: jiaqi
 * Date: 2016/4/23
 * Time: 14:54
 */
class ModelBase
{
    protected $redis;

    public function __construct()
    {
        /*$this->redis = new RedisClient();
        $this->redis->connect();*/
    }

    protected function initRedis()
    {
        $redis = new RedisClient();
        $redis->connect();
        return $redis;
    }

}
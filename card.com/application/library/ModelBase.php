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

    protected function initRedis()
    {
        $this->redis = new RedisClient();
        $this->redis->connect();
    }

}
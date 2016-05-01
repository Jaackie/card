<?php

/**
 * Created by PhpStorm.
 * User: jiaqi
 * Date: 2016/5/1
 * Time: 22:29
 */
class cardModel extends ModelBase
{
    public function __construct()
    {
        $this->redis = $this->initRedis();
    }



}
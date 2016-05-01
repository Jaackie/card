<?php

class IndexController extends Yaf_Controller_Abstract
{
    public function indexAction()
    {
        /*$redis = new Redis();
        $redis->connect('127.0.0.1',6379);*/
//        phpinfo();
        echo 'hello';
        testModel::getInstance()->redis();
    }
}
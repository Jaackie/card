<?php


class testModel extends ModelBase
{
    public function __construct()
    {
        $this->redis = $this->initRedis();
    }

    public static function getInstance()
    {
        return new self;
    }

    public function set($key, $val)
    {
        return $this->redis->set($key, $val);
    }

    public function get($key)
    {
        return $this->redis->get($key);
    }

    public function push()
    {
        $res = 0;
        for ($i = 0; $i < 1000000; $i++) {
            $res = $this->redis->lpush('card', $i);
        }
        return $res;
    }

    public function pop()
    {
        return $this->redis->lpop('card');
    }


    public static function getCount()
    {
        static $count = 100;
        return $count--;
    }

    public function in()
    {
        return $this->redis->incr('aaa');
    }

}
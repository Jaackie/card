<?php

/**
 * Created by PhpStorm.
 * User: Jaackie
 * Date: 2016/5/4
 * Time: 20:07
 */
class cardPackageModel extends ModelBase
{
    private $time;
    private $package_name;

    const PACKAGE_POOL = 'card_package_pool';
    const PACKAGE_QUEUE = 'card_package_queue';
    const PACKAGE = 'card_package_';

    public function __construct($time)
    {
        $this->time = $time;
        $this->package_name = self::PACKAGE . date('d-H', $time);
        $this->redis = $this->initRedis();
    }

    public static function getInstance($time)
    {
        return new self($time);
    }

    public function add()
    {
        if ($this->_isInPool()) {
            return;
        } else {
            $this->_addToPool();
            $this->_addToQueue();
            return;
        }
    }

    public function get()
    {
        $res = $this->_getPackage();
        if ($res && $res != 'tail') {
            return $res;
        } else {
            return 0;
        }
    }

    public function doPackage()
    {
        $make = false;
        while ($p_name = $this->_getQueue()) {
            if (!$this->_isExistPackage($p_name)) {
                $this->_makePackage($p_name);
                $make = true;
            }
        }

        return $make;
    }

    public function _makePackage($package_name)
    {
        if (preg_match('/(\d+)-(\d+)/is', $package_name, $time)) {
            $str_time = '2016-1-' . $time[1] . ' ' . $time[2] . ':0:0';
            $time = strtotime($str_time);

            $count_a = counterModel::getInstance('a', $time)->getCountByHour();
            $count_other = counterModel::getInstance('b', $time)->getCountByHour();

            $this->_setCardInPackage($package_name, 'tail');
            $this->_setCardInPackage($package_name, 'end:' . randModel::timeToHour($this->time));
            randModel::getInstance($this->time)->setStart();

            do {

                if ($count_a > 0) {
                    $this->_setCardInPackage($package_name, 'a');
                    $count_a--;
                }
                if ($count_other > 0) {
                    $this->_setCardInPackage($package_name, 'b');
                    $this->_setCardInPackage($package_name, 'c');
                    $this->_setCardInPackage($package_name, 'd');
                    $this->_setCardInPackage($package_name, 'e');
                    $count_other--;
                }
            } while ($count_a || $count_other);


            return true;
        } else {
            return false;
        }
    }

    private function _isInPool()
    {
        $res = $this->redis->hget(self::PACKAGE_POOL, $this->package_name, false);
        if ($res) {
            return true;
        } else {
            return false;
        }
    }

    private function _addToPool()
    {
        return $this->redis->hset(self::PACKAGE_POOL, $this->package_name, 1);
    }

    private function _addToQueue()
    {
        return $this->redis->lpush(self::PACKAGE_QUEUE, $this->package_name);
    }

    private function _getQueue()
    {
        return $this->redis->lpop(self::PACKAGE_QUEUE);
    }

    private function _getPackage()
    {
        $res = $this->redis->lpop($this->package_name);

        if (!$res) return false;

        if (preg_match('/[a-e]/is', $res)) {
            return $res;
        } elseif (preg_match('/(end):(\d+)/', $res, $time)) {
            randModel::getInstance($this->time)->setEnd();
            return 'tail';
        } else {
            $this->redis->lpush($this->package_name, 'tail');
            return 'tail';
        }
    }

    private function _isExistPackage($package_name)
    {
        $res = $this->_getPackage();
        if (!$res) return false;

        if ($res == 'tail') {
            return true;
        }

        $this->_setCardInPackage($package_name, $res);
        return true;
    }

    private function _setCardInPackage($package_name, $card_name)
    {
        return $this->redis->lpush($package_name, $card_name);
    }

} 
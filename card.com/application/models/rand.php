<?php

/**
 * Created by PhpStorm.
 * User: Jaackie
 * Date: 2016/5/7
 * Time: 11:08
 */
class randModel extends ModelBase
{
    private $time;

    const CARD_RAND = 'card_rand';

    const CARD_TIME_POOL = 'card_time_pool';

    const INIT_VAL = 1000;

    public function __construct($time)
    {
        $this->time = $time;
        $this->redis = $this->initRedis();
    }

    public static function getInstance($time)
    {
        return new self($time);
    }

    public function get()
    {
        $res = $this->redis->hget(self::CARD_RAND, $this->timeToHour($this->time) - 3600, false);
        if ($res) {
            return intval($res);
        } else {
            return intval(self::INIT_VAL);
        }
    }

    public function setStart()
    {
        $hour_time = self::timeToHour($this->time);
        $start_time = $this->time;
        if (!$this->_getTime($hour_time)) {
            $this->redis->hset(self::CARD_TIME_POOL, $hour_time, ['start' => $start_time]);
        }
        return;
    }

    public function setEnd()
    {
        $hour_time = self::timeToHour($this->time);
        $end_time = $this->time;
        if ($res = $this->_getTime($hour_time)) {
            $res['end'] = $end_time;
            $this->redis->hset(self::CARD_TIME_POOL, $hour_time, ['end' => $end_time]);
            $dif_time = $end_time - $res['start'];
            if ($dif_time > 0) {
                $weight = round(3600 / $dif_time, 3);
                $weight *= 0.8;
                $last = self::getInstance($hour_time - 3600)->get();
                $this->redis->hset(self::CARD_RAND, $hour_time, intval($last * $weight));
            }
        }
    }

    private function _getTime($hour_time)
    {
        $res = $this->redis->hget(self::CARD_TIME_POOL, $hour_time);
        if (!$res) return null;
        return $res;
    }

    public static function timeToHour($time)
    {
        return floor($time / 3600) * 3600;
    }


} 
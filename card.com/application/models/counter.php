<?php

/**
 * Created by PhpStorm.
 * User: Jaackie
 * Date: 2016/5/3
 * Time: 12:46
 */
class counterModel extends ModelBase
{
    private $cardName;      //卡片名
    private $time;          //获取卡片时间
    private $count_total;   //总数
    private $count_used;    //用量
    private $count_remain;  //余量

    const REDIS_KEY_USED = 'count_used';

    const TIME_START = '2016-1-1';
    const TIME_END = '2016-1-11';

    public function __construct($cardName, $time)
    {
        $this->cardName = cardModel::checkName($cardName);
        if ($this->checkTime($time)) {
            $this->time = $time;
        } else {
            throw new Exception('Time error');
        }
        $this->redis = $this->initRedis();
        $this->_initCount();
    }

    public static function getInstance($cardName, $time)
    {
        return new self($cardName, $time);
    }

    public function getCountByHour()
    {
        return round($this->_getCountByDay() * $this->_weightByHour());
    }


    private function _initCount()
    {
        $this->_initCountTotal();
        $this->_initCountUsed();
        $this->_initCountRemain();
    }

    private function _initCountTotal()
    {
        $total_arr = [
            'a' => 10000,
            'b' => 1000000,
            'c' => 1000000,
            'd' => 1000000,
            'e' => 1000000,
        ];
        $this->count_total = $total_arr[$this->cardName];
    }

    private function _initCountUsed()
    {
        $count_used = $this->redis->hget(self::REDIS_KEY_USED, $this->cardName, false);

        $this->count_used = $count_used ? intval($count_used) : 0;
    }

    private function _initCountRemain()
    {
        $this->count_remain = $this->count_total - $this->count_used;
    }

    public function resetUsed($number = 1, $optionMinus = true)
    {
        if ($optionMinus) {
            $this->count_used -= $number;
        } else {
            $this->count_used += $number;
        }

        $this->redis->hset(self::REDIS_KEY_USED, $this->cardName, $this->count_used);
    }

    public function checkTime($time = 0)
    {
        if (!$time) {
            $time = $this->time;
        }
        $start = strtotime(self::TIME_START);
        $end = strtotime(self::TIME_END);
        if ($time < $start || $time > $end) {
            return false;
        }

        return true;
    }

    private function _getCountByDay()
    {
        $day = intval(date('d', $this->time));
        if ($this->cardName == 'a') {
            if ($day < 10) {
                return $this->count_total * 0.05;
            } else {
                return $this->count_total * 0.55;
            }
        } else {
            return $this->count_total * 0.1;
        }
    }

    private function _weightByHour()
    {
        $clock = intval(date('H', $this->time));

        $weight_arr = $this->_weight();

        $total = 0;
        foreach ($weight_arr as $weight) {
            $total += $weight;
        }

        return $weight_arr[$clock] / $total;
    }

    private function _weight()
    {
        return [
            '0' => 0.4,
            '1' => 0.2,
            '2' => 0.1,
            '3' => 0.1,
            '4' => 0.1,
            '5' => 0.1,
            '6' => 0.3,
            '7' => 1,
            '8' => 2.2,
            '9' => 3.3,
            '10' => 4,
            '11' => 4.4,
            '12' => 4.8,
            '13' => 4.7,
            '14' => 4.5,
            '15' => 4.4,
            '16' => 4.7,
            '17' => 5,
            '18' => 4.8,
            '19' => 4.9,
            '20' => 4.1,
            '21' => 3.6,
            '22' => 1.5,
            '23' => 0.7,
        ];
    }

} 
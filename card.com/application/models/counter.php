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
        $this->time = $time;
        $this->redis = $this->initRedis();
        $this->_initCount();
    }

    public function getCountByHour()
    {
        if ($this->cardName == 'a') {
            if ($this->time >= strtotime(self::TIME_START) && $this->time <= strtotime(self::TIME_END) - 24 * 3600) {
//                $count = $this
            }
        }
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

    public function checkTime()
    {
        $start = strtotime(self::TIME_START);
        $end = strtotime(self::TIME_END);
        if ($this->time < $start || $this->time > $end) {
            return false;
        }

        return true;
    }

    private function _rateByHour()
    {
        $clock = intval(date('H', $this->time));
        if ($clock >= 0 && $clock <= 5) {
            return 0.01;
        } elseif ($clock >= 6 && $clock <= 11) {
            return ($clock - 5) * 0.02 + 0.01;
        }

    }

} 
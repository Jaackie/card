<?php

/**
 * Created by PhpStorm.
 * User: Jaackie
 * Date: 2016/5/7
 * Time: 10:25
 */
class userModel extends ModelBase
{
    private $uid;

    const USER_CARD = 'card_user';

    public function __construct($uid)
    {
        $this->uid = intval($uid);
        $this->redis = $this->initRedis();
    }

    public static function getInstance($uid)
    {
        return new self($uid);
    }

    public function addCard($card_name)
    {
        $card_arr = $this->redis->hget(self::USER_CARD, $this->uid);

        $card_arr[] = $card_name;

        $this->redis->hset(self::USER_CARD, $this->uid, $card_arr);
    }

    public function getCard()
    {
        if (!$this->uid) $this->uid = null;

        $res = $this->redis->hget(self::USER_CARD, $this->uid);

        if (!$res) $res = [];

        return $res;
    }

} 
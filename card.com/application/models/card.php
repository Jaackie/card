<?php

/**
 * Created by PhpStorm.
 * User: Jaackie
 * Date: 2016/5/1
 * Time: 22:29
 */
class cardModel extends ModelBase
{
    private $cardName;      //卡片名

    public function __construct($cardName)
    {
        $this->cardName = self::checkName($cardName);
        $this->redis = $this->initRedis();
    }

    public static function checkName($cardName)
    {
        $cardName = strtolower($cardName);
        $ordVal = ord($cardName);
        if ($ordVal < 97 || $ordVal > 101) {
            throw new Exception('card name error!');
        }

        return $cardName;
    }

}
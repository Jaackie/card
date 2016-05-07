<?php

class IndexController extends Yaf_Controller_Abstract
{
    public function indexAction()
    {
        /*randModel::getInstance(0)->setStart(strtotime('2016-1-1 10:0:0'), strtotime('2016-1-1 10:0:0'));
        randModel::getInstance(0)->setEnd(strtotime('2016-1-1 10:0:0'), strtotime('2016-1-1 10:20:0'));

        $res = randModel::getInstance(strtotime('2016-1-1 11:05:0'))->get();
        var_dump($res);*/

        if (!isset($_GET['uid']) || !isset($_GET['time'])) {
            $this->_result('miss uid or time');
        }
        $time = $_GET['time'];
        $uid = $_GET['uid'];


        if (isset($_GET['dynamic'])) {
            $rand = rand(1, randModel::getInstance($time)->get());
        } else {
            $rand = rand(1, 1000);
        }

        if ($rand > 3) {
            $this->_result(0);
        }

        try {
            $cp = new cardPackageModel($time);
            $cp->add();
            $card = $cp->get();
            if ($card) {
                userModel::getInstance($uid)->addCard($card);
            }
            $this->_result($card);
        } catch (Exception $e) {
            $this->_result($e->getMessage());
        }
    }

    public function getUserCardAction()
    {
        $uid = isset($_GET['uid']) ? $_GET['uid'] : 0;
        $card_arr = userModel::getInstance($uid)->getCard();
        $this->_result($card_arr);
    }

    private function _result($res)
    {
        if (is_array($res)) {
            $res = json_encode($res);
        }
        echo $res;
        exit;
    }
}
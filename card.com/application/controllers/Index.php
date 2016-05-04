<?php

class IndexController extends Yaf_Controller_Abstract
{
    public function indexAction()
    {
        /*$arr = [];
        for ($i = 0; $i < 1000; $i++) {
            $arr[] = rand(0, 1000);
        }
        $arrRes = array_count_values($arr);
        echo "<pre>";
        var_dump($arrRes);
        echo "</pre>";*/
        var_dump(date('H', time()));
        /*$t = new testModel();

        echo $t->in();*/
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2016/5/6
 * Time: 20:02
 */

define("APPLICATION_PATH", dirname(__DIR__));

$app = new Yaf_Application(APPLICATION_PATH . "/conf/application.ini");
$app->bootstrap()->execute("dopackage");

function dopackage()
{
    while (true) {
        $res = cardPackageModel::getInstance(0)->doPackage();
        if (!$res) {
            printf('[%s] %s', date('Y-m-d H:i:s'), 'sleep');
            echo "\n";
            sleep(3);
        }else{
            printf('[%s] %s', date('Y-m-d H:i:s'), 'do');
            echo "\n";
        }
    }
}

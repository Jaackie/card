<?php
define("APPLICATION_PATH", dirname(__DIR__));

$app = new Yaf_Application(APPLICATION_PATH . "/conf/application.ini");
$app->bootstrap()->run();

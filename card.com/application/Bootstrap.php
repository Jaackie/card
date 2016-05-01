<?php

class Bootstrap extends Yaf_Bootstrap_Abstract
{

    public function _initConfig()
    {
        $config = Yaf_Application::app()->getConfig();
        Yaf_Registry::set("config", $config);
        Yaf_Dispatcher::getInstance()->autoRender(FALSE);
    }

    public function _initDefaultName(Yaf_Dispatcher $dispatcher)
    {
        $dispatcher->setDefaultModule("Index")->setDefaultController("Index")->setDefaultAction("index");
    }

    public function _initLoader()
    {

    }
}

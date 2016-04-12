<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html;charset=utf-8');
        $view->headTitle()->setSeparator(' - ');
        $view->headTitle('Stijn - Intern Xsarus - 2015/2016');

    }

    protected function _initAutoload()
    {
        Zend_Loader_Autoloader::getInstance()->registerNamespace('Auth');


    }


}


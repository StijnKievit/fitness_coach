<?php
/**
 * Created by PhpStorm.
 * User: s.kievit
 * Date: 15-12-2015
 * Time: 9:33
 */
class AdminController extends Zend_Controller_Action
{

    public function init()
    {

        if(Auth_AuthChecker::getInstance()->getStatus() && Auth_AuthChecker::getInstance()->isAdmin())
        {

        }
        else{
            if(Auth_AuthChecker::getInstance()->getStatus())
            {
                $this->_helper->redirector('index', 'index', null, array());
            }
            else{
                $this->_helper->redirector('login', 'auth', null, array());
            }
        }
    }

    public function indexAction()
    {

    }

}
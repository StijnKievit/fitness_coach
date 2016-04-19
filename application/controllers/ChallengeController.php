<?php

class ChallengeController extends Zend_Controller_Action
{
    protected $dbAdapter;
    protected $page_prefix;

    public function init()
    {
        if(!Auth_AuthChecker::getInstance()->getStatus())
        {
            $this->_helper->redirector('login', 'auth', null, array());
        }
        $this->dbAdapter = Zend_Db_Table::getDefaultAdapter();
        $this->page_prefix = 'Challenge_';
    }

    public function indexAction()
    {



    }

    public function statsAction(){

    }

    public function newchallengesAction(){

        $db_model = new Application_Model_DbTable_Challenge();

        $challenges = $db_model->fetchAll($db_model->select()
        ->order('id DESC')
        ->limit(5, 0)

        );

        $this->view->challenges = $challenges;

    }

}


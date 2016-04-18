<?php

class IndexController extends Zend_Controller_Action
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
        $this->page_prefix = 'home_';
    }

    public function indexAction()
    {
        $user_model = new Application_Model_User();
        $this->view->levels = $user_model->getLevelData();

                /*$skill_model = new Application_Model_Skills(null, null);
                $comp_model = new Application_Model_Competenties();
                $story_model = new Application_Model_Stories();
                $this->view->skills = $skill_model->getAllSkills();
                $this->view->comp_list = $comp_model->getCompetenties();
                $this->view->teststory = $story_model->getStoriesByDate(23,11,2015);
                $this->loadContent(array("welcome","over_xsarus","team","skills", "comp"));
                $this->view->page_prefix = $this->page_prefix;*/
    }

}


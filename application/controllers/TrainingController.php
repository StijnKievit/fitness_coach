<?php

class TrainingController extends Zend_Controller_Action
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
        $this->page_prefix = 'Training_';
    }

    /*dashboard*/
    public function indexAction()
    {



    }

    /*return list of your training options*/
    public function selecttrainingAction(){

        $training_model = new Application_Model_Training();
        $trainingen = $training_model->getTrainingen();

        $this->view->trainingen = $trainingen;
    }
    /*create an training*/
    public function addtrainingAction()
    {
        $request = $this->getRequest();

        if ($this->getRequest()->isPost()) {
            if ($request->getPost()) {
                /*if its a post get values*/
                $training_model = new Application_Model_Training();
                $result = $training_model->save(trim($request->getParam('name')), trim($request->getParam('type')),$request->getParam('days'), $request->getParam('weeks') );

                var_dump($result["id"]);
                if(count($result) > 1){
                    $this->view->message = 'Training is toegevoegd!';
                }

            }
        }
    }
    //add excersice to training
    public function addxAction(){

    }
    //remove excersice
    public function removexAction(){

    }
    //returns stats of current excersice
    public function statsAction(){

    }



}


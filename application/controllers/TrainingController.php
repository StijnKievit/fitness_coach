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
        /*select active training and push it to the view*/
        $user_model = new Application_Model_User();
        $training_model = new Application_Model_Training();

        if($user_model->getCurrentTraining() != null)
        {
            $training_model->setTraining($user_model->getCurrentTraining());

            $this->view->cur_training = $training_model;

        }
        else{
            $this->view->cur_training = null;
        }



    }

    /*return list of your training options*/
    public function selecttrainingAction(){

        $request = $this->getRequest();

        if ($this->getRequest()->isPost()) {
            if ($request->getPost()) {

                $params = $request->getParams();

                $user_table = new Application_Model_DbTable_User();

                $data = array(
                    "current_training" => $params['training_id']
                );

                $user_table->update($data, "id=".Auth_AuthChecker::getInstance()->getId());
            }
        }
        else{
            $training_model = new Application_Model_Training();
            $trainingen = $training_model->getTrainingen();

            $this->view->trainingen = $trainingen;
        }
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



                    /*redirect to new location
                    $this->forward()->dispatch("addexercise", null ,null , array(
                        'training_id' => $result['id']
                    ));
                */
                    $this->_forward('addexercise', null, null, array('training_id' => $result['id'],
                                                                     'type' => $result['type'],
                                                                     'days' => $result['training_days']   ));
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

    public function addexerciseAction(){

        $request = $this->getRequest();
        $params = $request->getParams();

        $this->view->training_id = $params['training_id'];
        $this->view->training_type = $params['type'];
        $this->view->training_days = $params['days'];

    }

    public function activetrainingAction()
    {

        $request = $this->getRequest();
        $params = $request->getParams();

        if(array_key_exists( 'active', $params) ){

        }
        else{

/*            $training_id = $params['training_id'];*/
            $user_table = new Application_Model_User();
            $training_id = $user_table->getCurrentTraining();


            $db_table_training = new Application_Model_DbTable_Training();
            $result = $db_table_training->fetchRow(
                $db_table_training->select()
                    ->where("id =" .$training_id)
            );

/*            var_dump($result['type']);*/
            $training_type = $result['type'];

            $training_model = new Application_Model_ActiveTraining();
            $training_model->init($training_id);
            $training_model->training_type = $training_type;
            $training_model->set_max_days($result['training_days']);
            $training_model->set_max_weeks($result['training_weeks']);
            $training_model->cur_day = $result['cur_day'];
            $training_model->cur_week = $result['cur_week'];
            $training_model->completed = $result['completed'];
            $training_model->count_training_done = $result['training_done'];

            $training_model->createTraining();

            /*add model to session*/
            $myNamespace = new Zend_Session_Namespace('active_training');
            $myNamespace->model = $training_model;

            $this->view->oefeningen = $training_model->getExercises("NORMAL");
            $this->view->training_type = $training_model->training_type;

            //getmodel

        }

        /*check if request is post*/
        if ($this->getRequest()->isPost()) {
            if ($request->getPost()) {
            /*add finished training to model*/



            }

        }
    }

    public function trainingstatsAction(){

        $user_table = new Application_Model_User();
        $training_id = $user_table->getCurrentTraining();

        $training_model = new Application_Model_Training();
        $training_model->setTraining($training_id);

        $this->view->type_of_training = $training_model->type_of_training;
        $this->view->completed_exercises = $training_model->getcompletedExercises();

    }


}


<?php
/**
 * Created by PhpStorm.
 * User: s.kievit
 * Date: 4-1-2016
 * Time: 13:12
 */

class AjaxController extends Zend_Controller_Action
{
    protected $dbAdapter;

    public function init()
    {
        if (!Auth_AuthChecker::getInstance()->getStatus()) {
            $this->_helper->redirector('login', 'auth', null, array());
        }
        $this->dbAdapter = Zend_Db_Table::getDefaultAdapter();
    }

    public function indexAction()
    {

    }

    public function addexerciseAction(){
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $request = $this->getRequest();

        if ($this->getRequest()->isPost()) {
            if ($request->getPost()) {
                $params = ($request->getParams());

                if($params['type_of_training'] == "cardio")
                {

                    $db_table = new Application_Model_DbTable_CardioExercise();

                    $data_array = array(
                        "training_id" => $params['training_id'],
                        "name" => $params['name'],
                        "training_day" => $params['day'],
                        "type" => $params['type'],
                        "distance" => $params['distance'],
                        "time" => $params['time'],

                    );

                    $newRow = $db_table->createRow($data_array);
                    if($newRow->save()){

                        $data_return = array('id' => $newRow['id'], 'type' => $newRow['type'], 'name' => $newRow['name'], 'html'=> "

                        <li data-id = '".$newRow['id']."' class=\"train_item working_item\"><span>
                                ".$newRow['name']."
                            </span>
                            <div class=\"item_selection\">

                                <a data-id =  '".$newRow['id']."'href=\"#\" class=\"remove\">Verwijderen</a>
                            </div>
                        </li>

                        ");

                        /*to edit: <span data-id = '".$newRow['id']. "' class=\"edit\">Aanpassen</span>*/
                        echo json_encode($data_return);

                    }
                }
                if($params['type_of_training'] == "kracht")
                {
                    $db_table = new Application_Model_DbTable_KrachtExercise();
                    $data_array = array(
                        "training_id" => $params['training_id'],
                        "name" => $params['name'],
                        "training_day" => $params['day'],
                        "type" => $params['type'],
                        "sets" => $params['sets'],
                        "reps" => $params['reps'],

                    );

                    $newRow = $db_table->createRow($data_array);
                    if($newRow->save())
                    {
                        $data_return = array('id' => $newRow['id'], 'type' => $newRow['type'], 'name' => $newRow['name'], 'html'=> "

                        <li data-id = '".$newRow['id']."' class=\"train_item working_item\"><span>
                                ".$newRow['name']."
                            </span>
                            <div class=\"item_selection\">

                                <span data-id =  '".$newRow['id']."' class=\"remove\">Verwijderen</span>
                            </div>
                        </li>

                        ");
                        /*to edit: <span data-id = '".$newRow['id']. "' class=\"edit\">Aanpassen</span>*/
                        echo json_encode($data_return);
                    }

                }

            }
        }
        /*echo json_encode($request);*/
    }

    public function removeexerciseAction(){
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $request = $this->getRequest();

        if ($this->getRequest()->isPost()) {
            if ($request->getPost()) {
                $params = ($request->getParams());
                var_dump($params);
            }
        }

    }

    public function editexercise(){
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

    }

    public function getexercise(){
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

    }


    /*function to handle form input during*/
    public function completeexerciseAction(){

        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $request = $this->getRequest();

        if ($this->getRequest()->isPost()) {
            if ($request->getPost()) {
                $params = ($request->getParams());
                /*var_dump($params);*/

                /*add new item to model */
                /*get namespace*/
                if($params['training_type'] == 'kracht')
                {
                    $myNamespace = new Zend_Session_Namespace('active_training');
                    ($myNamespace->model->finished_exersice($params['oefening_id'], $params['sets']));
                }
                if($params['training_type'] == "cardio")
                {
                    $data = array(
                        "time" => $params['time'],
                        "distance" => $params['distance']
                    );
                    $myNamespace = new Zend_Session_Namespace('active_training');
                    ($myNamespace->model->finished_exersice($params['oefening_id'], $data));
                }


            }
        }
    }

    public function completetrainingAction(){

        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $request = $this->getRequest();

        if ($this->getRequest()->isPost()) {
            if ($request->getPost()) {
                /*$params = ($request->getParams());
                var_dump($params);                */
                $myNamespace = new Zend_Session_Namespace('active_training');
                /*finish training*/
                $myNamespace->model->finished_training();
                /*unset session*/
                Zend_Session::namespaceUnset('active_training');

            }
        }

        /*redirect to home controller, index action*/

        $this->_helper->redirector('index', 'index', null);

    }

    public function setcurtrainingAction(){
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $request = $this->getRequest();

        if ($this->getRequest()->isPost())
        {
            $user_model = new Application_Model_User();
            $user_model->setCurrentTraining($request->getParams()['id']);
        }
    }

    public function removetrainingAction(){

        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $request = $this->getRequest();

        if ($this->getRequest()->isPost())
        {
            $id = $request->getParams()['id'];
            $db_model = new Application_Model_DbTable_Training();
            $db_model->delete("id = " . $id);
        }
    }
}
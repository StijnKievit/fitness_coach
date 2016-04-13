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
                                <a data-id = '".$newRow['id']. "' href=\"\" class=\"edit\">Aanpassen</a>
                                <a data-id =  '".$newRow['id']."'href=\"\" class=\"remove\">Verwijderen</a>
                            </div>
                        </li>

                        ");

                        echo json_encode($data_return);

                    }
                }
                if($params['type_of_training'] == "kracht")
                {
                    $db_table = new Application_Model_DbTable_KrachtExercise();
                    echo 'its kracht';
                }

            }
        }
        /*echo json_encode($request);*/
    }

    public function editexercise(){
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

    }

    public function getexercise(){
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

    }
}
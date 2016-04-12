<?php
/**
 * Created by PhpStorm.
 * User: s.kievit
 * Date: 27-10-2015
 * Time: 9:33
 */

class CompController extends Zend_Controller_Action
{
    protected $dbAdapter;

    public function init()
    {
        if(!Auth_AuthChecker::getInstance()->getStatus())
        {
            $this->_helper->redirector('login', 'auth', null, array());
        }
        $this->dbAdapter = Zend_Db_Table::getDefaultAdapter();
    }
    public function indexAction(){


        $request = $this->getRequest();

        if($this->getRequest()->isPost())
        {
            if ($request->getPost())
            {
                $this->_helper->layout()->disableLayout();
                $this->_helper->viewRenderer->setNoRender(true);

                $completed = $request->getParam('completed');
                $comp_id = $request->getParam('id');

                $comp_complete_value = 0;

                if($completed == 'true')
                {
                    $comp_complete_value = 1;
                }
                else if($completed == 'false'){

                    $comp_complete_value = 0;
                }
                else{
                    echo json_encode('invalid data');
                }

                $data = array(
                    'completed' => $comp_complete_value
                );

                try{
                    $this->dbAdapter->update('intern_competenties', $data, "id = $comp_id" );
                    echo json_encode('update complete');
                }
                catch(Exception $e)
                {
                    echo json_encode('something went wrong');
                }



            }
        }
        else
        {
            $current_comp = $this->dbAdapter->fetchAll("SELECT intern_competenties.id, intern_competenties.name AS `name`, intern_comp_type.name AS `type`, intern_competenties.completed FROM intern_competenties INNER JOIN intern_comp_type ON intern_competenties.type_id = intern_comp_type.id ");
            $this->view->comp_list = $current_comp;
        }

    }
    public function newAction(){

        if(!Auth_AuthChecker::getInstance()->isAdmin())
        {
            $this->_helper->redirector('index', 'index', null, array());
        }

        $request = $this->getRequest();

        if($this->getRequest()->isPost())
        {
            if ($request->getPost())
            {
                $newComp = $request->getParam('comp_name');
                $newCompType = $request->getParam('comp_type');

                echo $newComp;

                $data = array(
                    'name' => $newComp,
                    'type_id' => $newCompType
                );

                try {
                    $this->dbAdapter->insert('intern_competenties', $data);

                    $comp_type = $this->dbAdapter->fetchAll("SELECT * FROM intern_comp_type");
                    $this->view->comp_type_list = $comp_type;

                    $current_comp = $this->dbAdapter->fetchAll("SELECT intern_competenties.name AS `name`, intern_comp_type.name AS `type` FROM intern_competenties INNER JOIN intern_comp_type ON intern_competenties.type_id = intern_comp_type.id ");
                    $this->view->comp_list = $current_comp;
                }
                catch(Exception $e)
                {
                    echo $e;

                }
            }
        }
        else{
            try{
                $comp_type = $this->dbAdapter->fetchAll("SELECT * FROM intern_comp_type");
                $this->view->comp_type_list = $comp_type;

                $current_comp = $this->dbAdapter->fetchAll("SELECT intern_competenties.name AS `name`, intern_comp_type.name AS `type` FROM intern_competenties INNER JOIN intern_comp_type ON intern_competenties.type_id = intern_comp_type.id ");
                $this->view->comp_list = $current_comp;
            }
            catch(Exception $e)
            {
                echo $e;
            }

        }

    }
}
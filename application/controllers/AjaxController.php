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

    public function skillAction(){

        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $skill_model = new Application_Model_DbTable_Skill();
        $skills = $skill_model->fetchAll();
        $skill_array = array();

        foreach ($skills as $skill){
            array_push($skill_array, $skill['name'] );
        }

        echo json_encode($skill_array);
    }

    public function storyAction(){

        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $story_model = new Application_Model_DbTable_Story();
        $stories = $story_model->fetchAll();

        $story_array = array();

        foreach ($stories as $story){

            $item = array(
                'title' => $story['title'],
                'timestamp' => $story['time_stamp'],
                'introduction' => $story['introduction'],
                'challenges' => $story['challenges'],
                'conclusion' => $story['conclusion']
            );

            array_push($story_array, $item);
        }
        echo json_encode($story_array);
    }
}
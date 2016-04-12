<?php
/**
 * Created by PhpStorm.
 * User: s.kievit
 * Date: 23-12-2015
 * Time: 14:35
 */
class SkillController extends Zend_Controller_Action
{

    public function init()
    {
        if(!Auth_AuthChecker::getInstance()->getStatus())
        {
            $this->_helper->redirector('login', 'auth', null, array());
        }
    }

    public function removeunlinkedAction(){



        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $request = $this->getRequest();

        if($request->getParam('key') == 'effe48e221a19c7edd49b509a7f78302')
        {
            $combine_model = new Application_Model_DbTable_StorySkill();
            $skill_model = new Application_Model_DbTable_Skill();


            $active_items = $combine_model->fetchAll();
            $active_items_id_array = array();

            foreach($active_items as $active_skills)
            {
                array_push($active_items_id_array, $active_skills['skill_id']);

            }

            $id_list = implode(",", $active_items_id_array);
            $result = $skill_model->delete("id NOT IN ( $id_list )");

            echo json_encode('Skills verwijderd: '.$result);
        }
        else{
            echo json_encode('access denied');
        }


    }

    public function indexAction(){

        $request = $this->getRequest();

        if($request->getParam('id') != '') {

            $id = $request->getParam('id');

            $db_model = new Application_Model_DbTable_StorySkill();

            $results = $db_model->fetchAll(
                $db_model->select()
                ->where('skill_id = ?', $id)
            );



            $skill_list = array();

            foreach($results as $skill)
            {
                    $skill = new Application_Model_Skills($skill['id']);

                    array_push($skill_list, $skill);

            }

            /*get name*/
            $skill_db_table = new Application_Model_DbTable_Skill();
            $main_skill = $skill_db_table->fetchRow(
                $skill_db_table->select()
                ->where('id = ?', $id)
            );

            $this->view->skill_name = $main_skill['name'];
            $this->view->skill_list = $skill_list;
        }
        else{
            $skill_db_table = new Application_Model_DbTable_Skill();
            $results = $skill_db_table->fetchAll();
            $this->view->skill_name = 'Skills';
            $this->view->main_skill_list = $results;
        }
    }

    public function jsonskilllistAction(){

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
}
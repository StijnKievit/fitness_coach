<?php
/**
 * Created by PhpStorm.
 * User: s.kievit
 * Date: 27-10-2015
 * Time: 9:33
 */
class StoryController extends Zend_Controller_Action
{
    protected $dbAdapter;

    public function init()
    {
        $this->dbAdapter = Zend_Db_Table::getDefaultAdapter();

        if(!Auth_AuthChecker::getInstance()->getStatus())
        {
            $this->_helper->redirector('login', 'auth', null, array());
        }
    }

    public function indexAction()
    {
        $request = $this->getRequest();

        if($request->getParam('id') != '')
        {
            $id = $request->getParam('id');

            $story = $this->dbAdapter->fetchAll('SELECT * FROM intern_stories WHERE id = ?', array($id));
            $this->view->story = $story;

            $comp_model = new Application_Model_Competenties();
            $skill_model = new Application_Model_Skills(null, $id);

            $comp =  $comp_model->getStoryCompetenties($id);
            /*$skill = $skill_model->getStorySkills($id);*/

            $skill = $skill_model->getSkillList();
            
            $this->view->story_comp = $comp;
            $this->view->story_skill = $skill;


            $comments = new Application_Model_Comments();
            //get the comments for the single story
            foreach($story as $item) {
                $this->view->comments = $comments->get_comments($item['id']);
            }



            if($request->getParam('ajax') != '')
            {
                //disable layout
                $this->_helper->layout()->disableLayout();
                $this->_helper->viewRenderer->setNoRender(true);

                $complete_story = array(
                    'story' => $story,
                    'comp'  => $comp,
                    'skill' =>  $skill
                );

                echo json_encode( $complete_story );
            }

        }
        else{
            $this->view->stories = $this->dbAdapter->fetchAll("SELECT id, title, time_stamp FROM intern_stories");
            $this->view->content = $this->loadContent('story_');
        }




    }

    public function adminAction(){

        if(!Auth_AuthChecker::getInstance()->isAdmin())
        {
            $this->_helper->redirector('index', 'story', null, array());
        }
        $this->view->stories = $this->dbAdapter->fetchAll("SELECT id, title, time_stamp FROM intern_stories");
    }
    public function newAction()
    {

        if(!Auth_AuthChecker::getInstance()->isAdmin())
        {
            $this->_helper->redirector('index', 'story', null, array());
        }

        $comp_model = new Application_Model_Competenties();


        $this->view->comp_list = $comp_model->getCompetenties();

        $request = $this->getRequest();

        if ($this->getRequest()->isPost())
        {
            if ($request->getPost()) {


                $story = array(
                    'titel' =>  $request->getParam('title'),
                    'datum'  =>  $request->getParam('time_stamp'),
                    'introductie'  =>  trim($request->getParam('intro')),
                    'uitdaging'    =>  trim($request->getParam('challenges')),
                    'conclusie'    =>  trim($request->getParam('conclusion')),
                    'competenties'   =>  $request->getParam('comps'),
                    'skills'    =>  array_map('trim', explode(',', $request->getParam('skills')))
                );


                $storyModel = new Application_Model_Stories();


                switch ($storyModel->save($story)){
                    case 'failed':
                        $this->view->message = 'Opslaan van story is niet gelukt.';
                        break;
                    case 'invalid':
                        $this->view->message = 'Niet alle velden zijn ingevuld.';
                        break;
                    default:
                        $this->view->message = 'Opslaan is gelukt.';
                }
            }

        }
    }

    public function removeAction(){

        if(!Auth_AuthChecker::getInstance()->isAdmin())
        {
            $this->_helper->redirector('index', 'story', null, array());
        }

        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $request = $this->getRequest();

                $remove_id = $request->getParam('id');
                if(isset($remove_id))
                {
                    try{
                        $this->dbAdapter->delete("intern_stories", "id = $remove_id");
                    }
                    catch(Exception $e)
                    {
                        die($e);
                    }

                    $this->_helper->redirector('admin');

                }
    }

    public function editAction(){

        if(!Auth_AuthChecker::getInstance()->isAdmin())
        {
            $this->_helper->redirector('index', 'story', null, array());
        }

        $request = $this->getRequest();

        $edit_id = $request->getParam('id');

        if ($this->getRequest()->isPost())
        {
            if ($request->getPost())
            {
                $story = array(
                    'id' => $edit_id,
                    'titel' =>  $request->getParam('title'),
                    'datum'  =>  $request->getParam('time_stamp'),
                    'introductie'  =>  trim($request->getParam('intro')),
                    'uitdaging'    =>  trim($request->getParam('challenges')),
                    'conclusie'    =>  trim($request->getParam('conclusion')),
                    'competenties'   =>  $request->getParam('comps'),
                    'skills'    =>  array_filter(array_map('trim', explode(',', $request->getParam('skills'))))
                );

                $storyModel = new Application_Model_Stories();

                switch ($storyModel->update($story)){
                    case 'failed':
                        $this->view->message = 'Opslaan van story is niet gelukt.';
                        break;
                    case 'invalid':
                        $this->view->message = 'Niet alle velden zijn ingevuld.';
                        break;
                    default:
                        $this->view->message = 'Opslaan is gelukt.';
                }

                $storyModel = new Application_Model_Stories();
                $this->view->story = $storyModel->getStory($edit_id);

                $comp_model = new Application_Model_Competenties();


                $this->view->comp_list = $comp_model->getCompetenties();
                $this->view->story_comp_list = $comp_model->getStoryCompetenties($edit_id);

            }

        }
        else
        {
            if(isset($edit_id))
            {
                $storyModel = new Application_Model_Stories();
                $this->view->story = $storyModel->getStory($edit_id);

                $comp_model = new Application_Model_Competenties();


                $this->view->comp_list = $comp_model->getCompetenties();
                $this->view->story_comp_list = $comp_model->getStoryCompetenties($edit_id);
            }
        }
    }

    public function downloadstoriesAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $table = new Application_Model_DbTable_Story();
        $result = $table->fetchAll();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename='.date("Ymd").'_stories'.'.csv');

        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');

        // output the column headings
        fputcsv($output, array('id', 'title', 'time_stamp', 'introduction', 'challenges',  'conclusion'));

        // loop over the rows, outputting them
        foreach($result as $record)
        {
            fputcsv($output, array($record['id'],$record['title'],$record['time_stamp'],$record['introduction'],$record['challenges'],$record['conclusion']));
        }

    }

    public function loadContent($page_prefix)
    {
        $db_table = new Application_Model_DbTable_Pages();
        $pages = $db_table->fetchAll(
            $db_table->select()
                ->where('code LIKE ?', $page_prefix.'%')
        );

        return $pages;
    }
}

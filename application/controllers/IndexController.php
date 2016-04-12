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

        $user_kracht_xp = 500;
        $user_cardio_xp = 10;

        $this->view->levels = $this->CalcLevels($user_cardio_xp, $user_kracht_xp);


                /*$skill_model = new Application_Model_Skills(null, null);
                $comp_model = new Application_Model_Competenties();
                $story_model = new Application_Model_Stories();
                $this->view->skills = $skill_model->getAllSkills();
                $this->view->comp_list = $comp_model->getCompetenties();
                $this->view->teststory = $story_model->getStoriesByDate(23,11,2015);
                $this->loadContent(array("welcome","over_xsarus","team","skills", "comp"));
                $this->view->page_prefix = $this->page_prefix;*/


    }
    //old outdated function (getStories should return an error)
    public function updatetimelineAction(){

        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $story_model = new Application_Model_Stories();
        echo json_encode( $story_model->getStories() );
    }

    //used for getting a single story
    public function getstoryAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        if(isset($_GET['story_id']))
        {
            $story_model = new Application_Model_Stories();
            echo json_encode($story_model->getStory($_GET['story_id']));
            //echo json_encode($_GET['story_id']);
        }



    }
    //used by timeline to get the right stories for the selected time windows
    public function getstorybydateAction()
    {       $day = $_GET['day'];
            $month = $_GET['month'];
            $year = $_GET['year'];


        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $story_model = new Application_Model_Stories();
        echo json_encode($story_model->getStoriesByDate($day,$month, $year));
    }

    public function loadContent($code_array)
    {
        $page_array = array();
        foreach($code_array as $code)
        {
            $db_table = new Application_Model_DbTable_Pages();
            $page = $db_table->fetchAll(
                $db_table->select()
                ->where('code = ?', $this->page_prefix.$code)

            );

           /* var_dump($page);
            var_dump($code);*/

            $item  = array($this->page_prefix.$code => $page[0]);

            $page_array[$this->page_prefix.$code] = $page[0];
        }
        $this->view->page = $page_array;
    }

    /*this function calculates the levels and percentages*/
    public function CalcLevels($cardio_xp, $kracht_xp)
    {
        $user_total_xp = $cardio_xp + $kracht_xp;

        $fit_constant = .075;
        $sub_constant = .1;
        $fitheidlv =  floor($fit_constant * sqrt($user_total_xp));
        $krachtlv = floor($sub_constant * sqrt($kracht_xp));
        $cardiolv = floor($sub_constant * sqrt($cardio_xp));

        $fit_factor = $user_total_xp / pow(($fitheidlv + 1) / $fit_constant, 2);
        $cardio_factor = $cardio_xp / pow(($cardiolv + 1) / $sub_constant, 2);
        $kracht_factor = $kracht_xp / pow(($krachtlv + 1) / $sub_constant, 2);


        $valuearray = array(
            "fitheid" => array(
                "lv" => $fitheidlv,
                "fact" => $fit_factor
            ),
            "cardio" => array(
                "lv" => $cardiolv,
                "fact" => $cardio_factor
            ),
            "kracht" => array(
                "lv" => $krachtlv,
                "fact" => $kracht_factor
            )
        );

        return $valuearray;
    }


}


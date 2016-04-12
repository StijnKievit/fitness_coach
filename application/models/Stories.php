<?php
/**
 * Created by PhpStorm.
 * User: s.kievit
 * Date: 27-10-2015
 * Time: 13:47
 */

class Application_Model_Stories
{
    protected $_dbAdapter;

    public function __construct()
    {
        $this->_dbAdapter =  Zend_Db_Table::getDefaultAdapter();
    }

    public function save($data)
    {

            if(in_array('', $data))
            {
                return 'invalid';
            }
            else
            {

                $insert_data = array(
                    'title' => $data['titel'],
                    'time_stamp' => $data['datum'],
                    'introduction' => $data['introductie'],
                    'challenges' => $data['uitdaging'],
                    'conclusion' => $data['conclusie']
                );




                try {

                    $this->_dbAdapter->insert('intern_stories', $insert_data);
                    $id = $this->_dbAdapter->lastInsertId();

                    foreach ($data['skills'] as $skill) {

                        $skill_model = new Application_Model_Skills(null, null);
                        $skill_model->saveSkill($skill, $id);

                    }
                    foreach ($data['competenties'] as $competentie) {
                        $this->_dbAdapter->insert('intern_story_comp', array(
                            'comp_id' => $competentie,
                            'story_id' => $id
                        ));
                    }
                } catch (Exception $e)
                {
                    return 'failed';
                }
            }
    }

    public function update($data){
        if(in_array('', $data))
        {
            return 'invalid';
        }
        else
        {

            $update_id = $data['id'];
            $update_data = array(
                'title' => $data['titel'],
                'time_stamp' => $data['datum'],
                'introduction' => $data['introductie'],
                'challenges' => $data['uitdaging'],
                'conclusion' => $data['conclusie']
            );

            try {

                $this->_dbAdapter->update('intern_stories', $update_data, "id = $update_id");

                $skill_id_list = array();

                foreach ($data['skills'] as $skill)
                {
                    $skill_model = new Application_Model_Skills();
                    array_push($skill_id_list, $skill_model->saveSkill($skill, $update_id));
                }

                $skill_id_string = implode(",", $skill_id_list);
                //clear removed skills
                $sql = ("DELETE FROM intern_story_skills WHERE story_id = $update_id AND id NOT IN(".$skill_id_string.")");
                $this->_dbAdapter->query($sql);

                /*competenties*/
                foreach ($data['competenties'] as $competentie) {


                    if (($result = $this->_dbAdapter->fetchOne("SELECT COUNT(*) FROM intern_story_comp WHERE comp_id = "."'$competentie'"." AND story_id = $update_id")) != 0)
                    {

                    }
                    else{
                        $this->_dbAdapter->insert('intern_story_comp', array(
                            'comp_id' => $competentie,
                            'story_id' => $update_id
                        ));
                    }

                }

                $compstring = implode(",", $data['competenties']);

                //clear removed skills
                $sql = ("DELETE FROM intern_story_comp WHERE story_id = $update_id AND comp_id NOT IN( ".$compstring.")");
                $this->_dbAdapter->query($sql);
            }
            catch (Exception $e)
            {
                return 'failed';
            }
        }
    }

    public function getStory($id){

        $content = $this->_dbAdapter->fetchAll("SELECT * FROM intern_stories WHERE id = ?", array($id));

        $compModel = new Application_Model_Competenties();

        $comp = $compModel->getStoryCompetenties($id);

        $skillModel = new Application_Model_Skills(null, $id);
        /*$skill = $skillModel->getStorySkills($id);*/
        $skill = $skillModel->getSkillList();

        $object = array(
            'content' => $content,
            'competenties' => $comp,
            'skills' => $skill
        );

        return $object;


    }
    public function getStories()
    {

        die('this function is no longer available - getstories()');



        $result = $this->_dbAdapter->fetchAll("SELECT id, title, time_stamp FROM intern_stories");
        $full_time_stamps = array();

        foreach($result as $item)
        {
            array_push($full_time_stamps, strtotime($item['time_stamp']));
        }

        $min_value = min($full_time_stamps);

        $timeline_stamps  = array();

        foreach($full_time_stamps as $stamps)
        {
            array_push($timeline_stamps, (int)round(($stamps - $min_value) / 1000));
        }

        for ($x = 0; $x < count($result); $x++)
        {
            $result[$x]['time_stamp'] = $timeline_stamps[$x];
        }

        return $result;
    }

    public function getStoriesByDate($day, $month, $year)
    {
        $startDate = $year.'/'.$month.'/'.$day.' 00:00:00.00';
        $endDate = $year.'/'.$month.'/'.$day.' 23:59:59.99';

        $result = $this->_dbAdapter->fetchAll("SELECT id, title, time_stamp FROM intern_stories WHERE time_stamp BETWEEN '".$startDate."' AND '".$endDate."'");

        return $result;
    }


}



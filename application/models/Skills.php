<?php
/**
 * Created by PhpStorm.
 * User: s.kievit
 * Date: 23-11-2015
 * Time: 14:26
 */

class Application_Model_Skills
{
    protected $_dbAdapter;
    protected $_skillTable;
    protected $_skill_story;

    protected $id;
    protected $skill_id;
    protected $story_id;
    protected $skill_name;

    protected $skill_list = array();

    public function __construct($id = null, $story_id = null)
    {
        $this->_dbAdapter = Zend_Db_Table::getDefaultAdapter();
        $this->_skillTable = new Application_Model_DbTable_Skill();
        $this->_skill_story = new Application_Model_DbTable_StorySkill();

        if($id != null):

            try{
                $result = $this->_skill_story->fetchAll(
                    $this->_skill_story->select()
                    ->where('id = ?', $id)
                );

                $this->skill_id = $result[0]['skill_id'];
                $this->story_id = $result[0]['story_id'];
                $this->id = $id;
                }
            catch(Exception $e)
            {
                die($e);
            }
        endif;

        if($story_id != null)
        {
            $all_story_skills = $this->_skill_story->fetchAll(
                $this->_skill_story->select()
                ->where('story_id = ?', $story_id)
            );



            foreach($all_story_skills as $skill)
            {
                $skill = new Application_Model_Skill($skill['skill_id']);
                array_push($this->skill_list, $skill);
            }
        }


    }

    public function get_id(){
        return $this->id;
    }
    public function set_id($id){
        $this->id = $id;
    }
    public function get_skill_id(){
        return $this->skill_id;
    }
    public function set_skill_id($skill_id){
        $this->skill_id = $skill_id;
    }
    public function get_story_id(){
        return $this->story_id;
    }
    public function set_story_id($story_id){
        $this->story_id = $story_id;
    }
    public function set_name($skill_name){
        $this->skill_name = $skill_name;
    }

    public function get_name(){
        $skills = $this->_skillTable->fetchAll(
            $this->_skillTable->select()
                ->where('id = ?', $this->skill_id)
        );
        if(count($skills) > 0)
        {
            return $skills[0]['name'];
        }
        return '';

    }

    public function getSkillList(){
        return $this->skill_list;
    }

    public function getStoryName()
    {
        if(isset($this->story_id))
        {
            $story_model = new Application_Model_DbTable_Story();

            $result = $story_model->fetchRow(
                $story_model->select()
                    ->where('id = ?', $this->story_id)
            );



            return $result['title'];

        }

        return 'story';
    }

    public function getAllSkills()
    {
        $skills = $this->_skillTable->fetchAll();
        $main_skills_array = array();

        foreach($skills as $skill)
        {
            $item = new Application_Model_Skill($skill['id'], $skill['name']);
            array_push($main_skills_array, $item);
        }

        return $main_skills_array;
    }

    public function saveSkill($name, $story_id){

        $cur_skill_name = $name;
        $skill_id = '';

        $result = $this->_skillTable->fetchAll(
            $this->_skillTable->select()
            ->where('name = ?', $cur_skill_name)
        );

        if(count($result) > 0){
            $skill_id = $result[0]['id'];
        }
        else{
            $insert = $this->_skillTable->insert(array(
                "name" => $cur_skill_name
            ));
            $skill_id =  ($insert != null) ? $insert : $this->_dbAdapter->lastInsertId('intern_skills');
        }

        $data = array(
            'skill_id' => $skill_id,
            'story_id' => $story_id
        );

        $records = $this->_skill_story->fetchAll(
            $this->_skill_story->select()
            ->where('skill_id = ?', $skill_id)
            ->where('story_id = ?', $story_id)
        );

        if(!count($records) > 0)
        {
            $story_skill_insert = $this->_skill_story->insert($data);
            return ($story_skill_insert != null) ? $story_skill_insert : $this->_dbAdapter->lastInsertId('intern_story_skills');
        }
        else{
            return $records[0]['id'];
        }
    }
}


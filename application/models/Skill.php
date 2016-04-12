<?php
/**
 * Created by PhpStorm.
 * User: s.kievit
 * Date: 23-12-2015
 * Time: 15:20
 */
class Application_Model_Skill
{

    protected $skill_id;
    protected $skill_name;


    public function __construct($id = null, $name = null)
    {
        $this->skill_id = $id;
        $this->skill_name = $name;

        if($id != null)
        {

            $model = new Application_Model_DbTable_Skill();
            $result = $model->fetchAll(
                $model->select()
                ->where('id = ?', $id)
            );

            $this->skill_name = $result[0]['name'];

        }
    }

    public function get_id(){
        return $this->skill_id;
    }

    public function get_skill_name(){
        return $this->skill_name;
    }

    public function save(){

        $model = new Application_Model_DbTable_Skill();

        $check_query = $model->fetchAll(
            $model->select()
                ->where('name = ?', $this->skill_name)
        );


        if(!count($check_query) > 0):

            /*add to koppel tabel*/
            $model->insert(array(
                "name" => $this->skill_name
            ));

            return 'success';

        else:

            return 'failed';

        endif;

    }
}
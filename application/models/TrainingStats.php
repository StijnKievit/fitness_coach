<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 19-4-2016
 * Time: 09:14
 */
class Application_Model_TrainingStats
{
    public $training_id;
    public $training_type;
    public $training_name;
    public $exercises;

    public function __construct()
    {

    }

    public function getTraining(){

        $user_table = new Application_Model_User();

        if($this->training_id == '')
        {
            $this->training_id = $user_table->getCurrentTraining();
        }
        $training_table = new Application_Model_DbTable_Training();

        $training = $training_table->fetchRow($training_table->select()->where('id = ?',$this->training_id ));
        $this->training_type = $training['type'];
        $this->training_name = $training['name'];

    }

    /*get all training exercises*/
    public function getExercises(){

        if($this->training_type == 'cardio')
        {
            $db_exercise_table = new Application_Model_DbTable_CardioExercise();
        }
        elseif($this->training_type == 'kracht')
        {
            $db_exercise_table = new Application_Model_DbTable_KrachtExercise();
        }

        $this->exercises = $db_exercise_table->fetchAll($db_exercise_table->select()->where('training_id = ?', $this->training_id)->order('type ASC') );

        return $this->exercises;

    }


    /*get results per exercise*/
    public function getResults($exercise_id){

        if($this->training_type == 'cardio')
        {
            $db_exercise_table = new Application_Model_DbTable_FinishedCardio();
        }
        elseif($this->training_type == 'kracht')
        {
            $db_exercise_table = new Application_Model_DbTable_FinishedKracht();
        }

        return $db_exercise_table->fetchAll($db_exercise_table->select()->where('oefening_id = ?', $exercise_id));
    }

    public function setTrainingId($id){
        $this->training_id = $id;
    }

}

<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 12-4-2016
 * Time: 14:57
 */

class Application_Model_Training extends Application_Model_DbTable_Training
{
    protected $_dbAdapter;

    public $training_id;
    public $training_days;
    public $cur_day;
    public $cur_week;
    public $training_weeks;
    public $name;
    public $exercises;
    public $completed;
    public $type_of_training;
    public $times_training_done;


    public function __construct()
    {
        parent::__construct();
        $this->_dbAdapter =  Zend_Db_Table::getDefaultAdapter();

    }

    public function setTraining($id)
    {
        $this->training_id = $id;

        $db_table = new Application_Model_DbTable_Training();
        $cur_training = $db_table->fetchRow(
            $db_table->select()->where('id = ?', $id)
        );

        $this->training_days = $cur_training['training_days'];
        $this->training_weeks = $cur_training['training_weeks'];
        $this->cur_day = $cur_training['cur_day'];
        $this->cur_week = $cur_training['cur_week'];
        $this->name = $cur_training['name'];
        $this->completed = $cur_training['completed'];
        $this->type_of_training = $cur_training['type'];
        $this->times_training_done = $cur_training['training_done'];




        if($this->type_of_training == 'cardio'){

            $exercises_cardio_table = new Application_Model_DbTable_CardioExercise();
            $this->exercises = $exercises_cardio_table->fetchAll(
                $exercises_cardio_table->select()
                                        ->where('training_id = '.$this->training_id)
            );

        }
        if($this->type_of_training == 'kracht'){

            $exercises_kracht_table = new Application_Model_DbTable_KrachtExercise();
            $this->exercises = $exercises_kracht_table->fetchAll(
                $exercises_kracht_table->select()
                    ->where('training_id = '.$this->training_id)
            );
        }

    }
    public function getProgress(){
        $max_days = $this->training_weeks * $this->training_days;
            return ($this->times_training_done / $max_days) * 100;
    }

    public function save($name,$type, $days, $weeks){

        if($name!='' && $days > 0 && $weeks > 0)
        {
            $insert_data = array(
                'user_id' => Auth_AuthChecker::getInstance()->getId(),
                'name' => $name,
                'type' => $type,
                'training_days' => $days,
                'cur_day' => 1,
                'training_weeks' => $weeks,
                'cur_week' => 1
            );
        }

        $model = new Application_Model_DbTable_Training();

        $created_item = $model->createRow();
        $created_item->setFromArray($insert_data);
        $created_item->save();

        return $created_item;

    }

    /*get basic training information*/
    public function getTraining(){

    }

    /*fetch all the data needed to start a training*/
    public function startTraining(){

    }

    public function getTrainingen()
    {
        $db_table = new Application_Model_DbTable_Training();
        $results = $db_table->fetchAll(
            $db_table->select()
                ->where('user_id = ?', Auth_AuthChecker::getInstance()->getId())
        );

        return $results;
    }

    public function countExercises(){
        return count($this->exercises);
    }

    public function getcompletedExercises(){

        if($this->type_of_training == 'cardio')
        {
                $cardio_db_done_model = new Application_Model_DbTable_FinishedCardio();
                return $cardio_db_done_model->fetchAll($cardio_db_done_model->select()->where('training_id = ?', $this->training_id));
        }
        if($this->type_of_training == 'kracht')
        {
            $kracht_db_done_model = new Application_Model_DbTable_FinishedCardio();
            return $kracht_db_done_model->fetchAll($kracht_db_done_model->select()->where('training_id = ?', $this->training_id));
        }
    }



}
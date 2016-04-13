<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 13-4-2016
 * Time: 15:08
 */

class Application_Model_ActiveTraining{


    private $dbtable_training;
    private $dbtable_cardioexe;
    private $dbtable_krachtexe;
    private $training_id;
    public  $training_name;
    public $completed;
    private $training_weeks;
    private $training_days;

    public $cur_week;
    public $cur_day;

    private $exercise_list = array();
    private $exercise_done_list = array();



    private $cur_exercise;



    public function __construct(array $options = null){

        $this->dbtable_training = new Application_Model_DbTable_Training();
        $this->dbtable_cardioexe = new Application_Model_DbTable_CardioExercise();
        $this->dbtable_krachtexe = new Application_Model_DbTable_KrachtExercise();


    }

    public function getId($id){
        return $this->training_id;
    }

    public function init($id){
        $this->training_id = $id;





    }



}

?>
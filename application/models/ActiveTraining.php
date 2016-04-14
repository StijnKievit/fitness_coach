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


    public $training_type;
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

    public function set_max_days($days){
        $this->training_days = $days;
    }
    public function set_max_weeks($weeks){
        $this->training_weeks = $weeks;
    }

    /*initialize training*/
    public function createTraining(){

        if($this->training_type == 'cardio'){

            $oefeningen = $this->dbtable_cardioexe->fetchAll(
                $this->dbtable_cardioexe->select()
                                        ->where('training_id ='.$this->training_id )
                                        ->where('training_day = '.$this->cur_day)
            );

            foreach($oefeningen as $oefening)
            {
                $item = array(
                  "id" => $oefening['id'],
                    "training_id" => $oefening['training_id'],
                    "training_day" => $oefening['training_day'],
                    "name" => $oefening['name'],
                    "type" => $oefening['type'],
                    "distance" => $oefening['distance'],
                    "time" => $oefening["time"]
                );

                array_push($this->exercise_list , $item);
            }

        }
        elseif($this->training_type == 'kracht')
        {
            $oefeningen = $this->dbtable_cardioexe->fetchAll(
                $this->dbtable_krachtexe->select()
                    ->where('training_id ='.$this->training_id )
                    ->where('training_day = '.$this->cur_day)
            );

            foreach($oefeningen as $oefening)
            {
                $item = array(
                    "id" => $oefening['id'],
                    "training_id" => $oefening['training_id'],
                    "training_day" => $oefening['training_day'],
                    "name" => $oefening['name'],
                    "type" => $oefening['type'],
                    "sets" => $oefening['sets'],
                    "reps" => $oefening["reps"]
                );

                array_push($this->exercise_list , $item);
            }
        }


    }

    public function getExercises($type){

        if($type == 'json'){
            return json_encode($this->exercise_list);
        }
        else{
            return $this->exercise_list;
        }

    }
}

?>
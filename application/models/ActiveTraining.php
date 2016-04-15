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

    public function finished_exersice($oefening_id, $results){

        //oefening_id = int
        //results = array;

        /*var_dump($results);*/

        foreach($this->exercise_list as $exercise)
        {
            if($exercise['id'] == $oefening_id)
            {
                var_dump($exercise);
                $cur_exercise = $exercise;
                /*found the right one*/
            }
        }

        if($this->training_type == 'cardio'){

        }
        elseif($this->training_type == 'kracht')
        {

            $db_table = new Application_Model_DbTable_FinishedKracht();

            $kracht_results = $results;

            $new_data = array(
                "user_id" => Auth_AuthChecker::getInstance()->getId(),
                "oefening_id" => $cur_exercise['id'],
                "sets" => $cur_exercise['sets'],
                "reps" => $cur_exercise['reps']
            );

            $counter = 1;
            foreach($kracht_results as $result){

                $new_data["set_$counter"] = $result;
                $counter++;
            }

            /*ADD EXERCISE TO DONE LIST*/
            array_push($this->exercise_done_list, $new_data);
        }

    }

    public function finished_training(){
        var_dump('training is finished');


        /*save results*/
        if($this->training_type == 'kracht')
        {
            foreach($this->exercise_done_list as $exercise){

                $db_table = new Application_Model_DbTable_FinishedKracht();
                $db_table->insert($exercise);

            }
        }
        elseif($this->training_type == 'cardio'){

            foreach($this->exercise_done_list as $exercise)
            {
                $db_table = new Application_Model_DbTable_CardioExercise();
                $db_table->insert($exercise);
            }
        }

        /*change stage of current training*/

        if($this->cur_day == $this->training_days)
        {
            if($this->cur_week == $this->training_weeks)
            {
                /*completed*/
                $this->completed = 1;
            }
            else{
                $this->cur_week++;
                $this->cur_day = 1;
            }
        }
        else{
            $this->cur_day++;
        }




        /*update training*/
        $training_table = new Application_Model_DbTable_Training();

        $data = array(
            'cur_day' => $this->cur_day,
            'cur_week' => $this->cur_week,
            'completed' => $this->completed
        );


        $training_table->update($data, "id=".$this->training_id);

        /*add points*/




    }
}

?>
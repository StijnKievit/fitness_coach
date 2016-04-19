<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 19-4-2016
 * Time: 15:26
 */


class Application_Model_UserStats
{
    public $user_id;
    public $challenges;


    public function __construct()
    {
        $this->user_id = Auth_AuthChecker::getInstance()->getId();
    }

    public function getChallenges(){

        $challenge_table = new Application_Model_DbTable_UserChallenge();
        $this->challenges = $challenge_table->fetchAll($challenge_table->select()->where('user_id = ?', $this->user_id));
        return $this->challenges;
    }

    public function updateChallenges($training, $type, $value)
    {
        /*
         * training = cardio / kracht
         * type = 'bijv time, distance
         * value = raw data
         * */

        $user_challenge_table = new Application_Model_DbTable_UserChallenge();

        $valid_challenges = $user_challenge_table->fetchAll($user_challenge_table->select()
                                                                ->where("training_type = ?", $training)
                                                                ->where("exercise_type = ?", $type)
                                                                ->where("completed = 0")
        );

        foreach($valid_challenges as $challenge)
        {

            /*update results*/
            $cur_value = $challenge['cur_value'];
            $goal = $challenge['goal'];
            $completed = 0;
            $new_value = (int)$cur_value + (int)$value;

            /*check if challenge is completed and assign points to the user*/
            if($new_value >= $goal)
            {
                    $completed = 1;

                    $challenge_table = new Application_Model_DbTable_Challenge();
                    $challenge_data = $challenge_table->fetchRow($challenge_table->select()->where('id = ?', $challenge['uitdaging_id']));

                    $user_model = new Application_Model_User();

                    $user_model->addPoints($challenge_data['type_of_training'], $challenge_data['value']);
            }


            /*save challenge*/
            $data_array = array(
                "cur_value" => $new_value,
                "completed" => $completed
            );

            $user_challenge_table->update($data_array, "id = ".$challenge['id']);

        }

    }

    public function newChallenge($challenge_id){

        $challenge_table = new Application_Model_DbTable_Challenge();
        $user_challenge_table = new Application_Model_DbTable_UserChallenge();

        $challenge = $challenge_table->fetchRow($challenge_table->select()->where('id = ?', $challenge_id));

        $user_records_check = $user_challenge_table->fetchAll($user_challenge_table->select()->where('uitdaging_id = ?', $challenge_id));

        if(count($user_records_check) > 1)
        {
            /*throw exception*/
        }
        else{
            $data_array = array(
                "user_id" => $this->user_id,
                "uitdaging_id" => $challenge_id,
                "cur_value" => 0,
                "goal" => $challenge['goal'],
                "completed" => 0,
                "training_type" => $challenge['type_of_training'],
                "exercise_type" => $challenge['type']

            );

            $user_challenge_table->insert($data_array);
        }
    }







}
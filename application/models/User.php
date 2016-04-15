<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 15-4-2016
 * Time: 13:45
 */

class Application_Model_User
{
    protected $_dbAdapter;
    private $db_table;

    private $user_id;
    private $user_name;
    private $user_email;
    private $user_password;

    private $cur_training;

    private $cardio_points;
    private $kracht_points;

    private $cardio_lv;
    private $kracht_lv;
    private $fit_lv;

    public function __construct()
    {
        $this->_dbAdapter = Zend_Db_Table::getDefaultAdapter();
        $this->user_id = Auth_AuthChecker::getInstance()->getId();
        $this->db_table = new Application_Model_DbTable_User();

        $user = $this->db_table->fetchRow(
                    $this->db_table->select()
                                    ->where('id = '.$this->user_id)
        );

        $this->user_email = $user['email'];
        $this->cardio_points = $user['cardio_ptn'];
        $this->kracht_points = $user['kracht_ptn'];
        $this->cur_training = $user['current_training'];

    }



    public function setCurrentTraining($id){

        $data = array(
            "current_training" => $id
        );

        $this->db_table->update($data, "id=".$this->user_id);

    }

    public function addPoints ($type, $value)
    {
        if($type == 'cardio')
        {
            $this->cardio_points = ( (int)$this->cardio_points + (int)$value );
        }
        elseif($type == 'kracht')
        {
            $this->kracht_points = ( (int)$this->kracht_points + (int)$value );
        }

        $this->updateUser();
    }


    public function updateUser(){

        $user_data = array(
            'username' => $this->user_name,
            'email' => $this->user_email,
            'kracht_ptn' => $this->kracht_points,
            'cardio_ptn' => $this->cardio_points,
            'current_training' => $this->cur_training
        );

        $this->db_table->update($user_data, "id=".$this->user_id);
    }

    public function updatePassword($newpassword){

        $user_data = array(
          'password' => md5($newpassword)
        );

        $this->db_table->update($user_data, "id=".$this->user_id);
    }

    public function getCardioPoints(){

        return $this->cardio_points;
    }
    public function getKrachtPoints(){
        return $this->kracht_points;
    }

    public function getCardioLevel(){

        /*calc level*/
    }
    public function getKrachtLevel(){
        /*calc level*/
    }
    public function getFitLevel(){
        /*calc level*/
    }

    public function calc(){
        $user_total_xp = $this->cardio_points + $this->kracht_points;

        $fit_constant = .075;
        $sub_constant = .1;
        $fitheidlv =  floor($fit_constant * sqrt($user_total_xp));
        $krachtlv = floor($sub_constant * sqrt($this->kracht_points));
        $cardiolv = floor($sub_constant * sqrt($this->cardio_points));

        $fit_factor = $user_total_xp / pow(($fitheidlv + 1) / $fit_constant, 2);
        $cardio_factor = $this->cardio_points / pow(($cardiolv + 1) / $sub_constant, 2);
        $kracht_factor = $this->kracht_points / pow(($krachtlv + 1) / $sub_constant, 2);


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
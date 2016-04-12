<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 12-4-2016
 * Time: 14:57
 */

class Application_Model_Training
{
    protected $_dbAdapter;

    public function __construct()
    {
        $this->_dbAdapter =  Zend_Db_Table::getDefaultAdapter();
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

    public function getTrainingen(){
        $db_table = new Application_Model_DbTable_Training();
        $results = $db_table->fetchAll(
            $db_table->select()
                ->where('user_id = ?', Auth_AuthChecker::getInstance()->getId())
        );

        return $results;
    }

}
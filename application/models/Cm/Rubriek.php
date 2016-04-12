<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 2-12-2015
 * Time: 21:10
 */
class Application_Model_Cm_Rubriek
{
    protected $_dbAdapter;
    protected $_tableName;

    protected $rubriek_posities = array();
    protected $rubriek_id;
    protected $active;

    protected $rubriek_positions = array();

    public function __construct($page_id)
    {
        $this->_dbAdapter = Zend_Db_Table::getDefaultAdapter();
        $this->_tableName = 'intern_cm_page_rubriek';


        $sql = "SELECT * FROM ". $this->_tableName." WHERE page_id = "."'".$page_id."' AND active = 'ja'";

        try {
            $result = $this->_dbAdapter->fetchAssoc($sql);

            foreach ($result as $rubriek) {
                array_push($this->rubriek_positions, $rubriek);
            }
        }
        catch(Exception $e)
        {
            die($e);
        }


    }

    public function getRubriek($page_id)
    {
        //returns all position with this rubriek
    }
    public function updateRubriek($id, $code, $page_id, $active, $order)
    {
        //change code / page id / order
    }
    public function insertRubriek($code, $page_id, $active, $order)
    {

    }
    public function rubriekStatus($id, $actief)
    {
        //set if actief ja of nee
    }

    public function deleteRubriek($id){

    }
    public function delete_by_pageid($page_id){

        $this->_dbAdapter->delete($this->_tableName, "page_id = $page_id");

        //now delete positions

    }


}
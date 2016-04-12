<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 2-12-2015
 * Time: 21:10
 */
class Application_Model_Cm_Position
{
    protected $_dbAdapter;
    protected $_tableName;

    protected $id;
    protected $colspan;
    protected $content;
    protected $mime;

    public function __construct()
    {
        $this->_dbAdapter = Zend_Db_Table::getDefaultAdapter();
        $this->_tableName = 'intern_cm_position';
    }

    public function getPositions($rubriek_id)
    {

    }

    public function getPosition($code){
        //get position by code
    }

    public function insertPosition($data)
    {

    }

    public function updatePosition($id, $data)
    {

    }
    public function deletePosition($id)
    {

    }
    public function positionStatus($value)
    {
        //actief ja of nee
    }



}
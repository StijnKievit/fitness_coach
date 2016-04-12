<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 12-4-2016
 * Time: 14:56
 */

class Application_Model_DbTable_Training extends Zend_Db_Table_Abstract
{
    protected $_name = 'training';
    protected $_primary = 'id';
}
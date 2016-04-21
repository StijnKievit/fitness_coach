<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 21-4-2016
 * Time: 14:07
 */

class Application_Model_DbTable_Exercises extends Zend_Db_Table_Abstract
{
    protected $_name = 'exercises';
    protected $_primary = 'id';
}
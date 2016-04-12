<?php
/**
 * Created by PhpStorm.
 * User: s.kievit
 * Date: 24-12-2015
 * Time: 10:01
 */

class Application_Model_DbTable_Story extends Zend_Db_Table_Abstract
{
    protected $_name = 'intern_stories';
    protected $_primary = 'id';
}
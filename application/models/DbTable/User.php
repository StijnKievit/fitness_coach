<?php
/**
 * Created by PhpStorm.
 * User: s.kievit
 * Date: 16-9-2015
 * Time: 10:28
 */

class Application_Model_DbTable_User extends Zend_Db_Table_Abstract
{

    protected $_name = 'intern_user';
    protected $_primary = 'id';

}
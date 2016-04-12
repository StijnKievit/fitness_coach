<?php
/**
 * Created by PhpStorm.
 * User: s.kievit
 * Date: 3-9-2015
 * Time: 10:53
 */


class Application_Model_RegisterMapper
{
    protected $_dbTable;


    //verification
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;

    }

    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Register');
        }
        return $this->_dbTable;
    }


    //core functions


    public function save(Application_Model_Register $register){

        $data = array(
            'email' => $register->getEmail(),
            'username'=> $register->getUsername(),
            'password' => $register->getPassword()

        );


        //check if id exists (if exist -> update )
        if (null === ($id = $register->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }


    public function find($id, Application_Model_Register $register){

        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $register->setId($row->id)
            ->setEmail($row->email)
            ->setComment($row->comment)
            ->setCreated($row->created);

    }
    public function fetchAll(){

        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Register();
            $entry->setId($row->id)
                ->setEmail($row->email)
                ->setUsername($row->username)
                ->setPassword($row->password);
            $entries[] = $entry;
        }
        return $entries;
    }

}


?>
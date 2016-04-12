<?php
/**
 * Created by PhpStorm.
 * User: s.kievit
 * Date: 3-9-2015
 * Time: 9:10
 */

class Application_Model_Register
{

    protected $_username;
    protected $_email;
    protected $_password;
    protected $_id;

    public function __construct(array $options = null){

        if(is_array($options)) {
        $this->setOptions($options);
        }

    }

    //redirecting / validation

    public function setOptions(array $options){

        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }


    public function __set($name, $value){

        $method = 'set'.$name;
        if(('mapper' == $name) || !method_exists($this, $method)){
            throw new Exception('property bestaat niet');

        }
        return $this->$method();
    }

    public function __get($name){

        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('property bestaat niet');
        }
        return $this->$method();
    }



    //CORE FUNCTIONS

    //username

    public function setUsername($username){

        $this->_username = (string) $username;
        return $this;
    }

    public function getUsername(){

        return $this->_username;
    }


    //email

    public function setEmail($email){

        $this->_email = (string) $email;
        return $this;
    }

    public function getEmail(){

        return $this->_email;
    }

    //password
    public function setPassword($password){

        $this->_password = (string) $password;
        return $this;
    }

    public function getPassword(){

        return $this->_password;
    }
    //id - primary
    public function setId($id){

        $this->_id = (int) $id;
        return $this;
    }

    public function getId(){

        return $this->_id;
    }

}




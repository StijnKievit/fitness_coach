<?php
/**
 * Created by PhpStorm.
 * User: s.kievit
 * Date: 3-9-2015
 * Time: 15:27
 */


class Auth_AuthChecker
{

    private  $auth;

    private static $instance;



    private function __construct()
    {

        $this->auth = Zend_Auth::getInstance();


    }

    public static function getInstance(){

        if (is_null(self::$instance))
        {
            self::$instance = new self();
        }
        return self::$instance;

    }

    public function getId(){

        if($this->auth->hasIdentity()){

            return $this->auth->getStorage()->read()->id;
        }
        else{

            return 'not logged in';
        }
    }

    public function getEmail(){

        if($this->auth->hasIdentity()){

            return $this->auth->getStorage()->read()->email;
        }
        else{

            return 'not logged in';
        }

    }


    public function getUsername(){

        if($this->auth->hasIdentity()){

            return $this->auth->getStorage()->read()->username;
        }
        else{

            return 'not logged in';
        }
    }

    public function getStatus(){

        if($this->auth->hasIdentity())
        {
            return true;
        }
        else{
            return false;
        }
    }

    public function getRole(){

        if($this->auth->hasIdentity()){

            return $this->auth->getStorage()->read()->role;
        }
        else{

            return 'not logged in';
        }
    }

    public function isAdmin(){

        if($this->auth->hasIdentity()){

            if( $this->auth->getStorage()->read()->role === 'admin' )
            {

                return true;

            }
            else{
                return false;
            }
           /* if($this->getEmail() === '0875013@hr.nl'){
                return true;
            }
            else{
                return false;
            }*/
        }
        else{

            return false;
        }

    }

    public function isUser(){

        if($this->auth->hasIdentity()){

           return true;
        }
        else
        {
            return false;
        }

    }

}

?>
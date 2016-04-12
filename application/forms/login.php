<?php
/**
 * Created by PhpStorm.
 * User: s.kievit
 * Date: 3-9-2015
 * Time: 11:22
 */

class Application_Form_Login extends Zend_Form
{
    public function init()
    {

        $this->setMethod('post');
        $this->setName('login');
        $this->setAction(Zend_Controller_Front::getInstance()->getBaseUrl().'/auth/login');

        $email = new Zend_Form_Element_Text('email');
        $email  ->setLabel('Email address')
                ->setRequired(true)
                ->setValidators(array('EmailAddress'));


        $password = new Zend_Form_Element_Password('password');
        $password  ->setLabel('password')
            ->setRequired(true);

        $submit = new Zend_Form_Element_Submit('login');
        $submit  ->setLabel('login')
        ->setAttrib('class','btn btn-basic btn-fix main-btn btn-border');


        $this->addelements(array($email, $password, $submit));

    }

}


?>
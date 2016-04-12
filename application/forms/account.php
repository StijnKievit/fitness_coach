<?php
/**
 * Created by PhpStorm.
 * User: s.kievit
 * Date: 1-9-2015
 * Time: 11:23
 */

class Application_Form_Account extends Zend_Form
{

    public function init()
    {
        $this->setAttrib('action','../create');
        $this->setMethod('post');


        $this->addElement('text','email', array(
            'label' => 'Email',
            'required' => true,
            'filter' => array('StringTrim'),
            'validators' => array('EmailAddress')
        ));

        $this->addElement('text','username', array(
            'label' => 'Username',
            'required' => true
            ));

        $this->addElement('password','password' , array(
            'label' => 'Password',
            'required' => true
        ));
        $this->addElement('submit','send', array(
            'label' => 'Submit',
            'class' => 'btn btn-basic btn-fix'


        ));
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: s.kievit
 * Date: 7-9-2015
 * Time: 14:58
 */

class Application_Form_Catogory extends Zend_Form
{

    public function init()
    {




        $this->setMethod('post');
        $this->setName('catogory');

        //$this->setAction(Zend_Controller_Front::getInstance()->getBaseUrl().'/auth/login');

        $name = new Zend_Form_Element_Text('name');
        $name  ->setLabel('Catogory name')
            ->setRequired(true);

        $submit = new Zend_Form_Element_Submit('save');
        $submit  ->setLabel('save')
            ->setAttrib('class','btn btn-basic btn-fix');



        $this->addelements(array($name, $submit));

    }

}
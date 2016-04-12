<?php
/**
 * Created by PhpStorm.
 * User: s.kievit
 * Date: 7-9-2015
 * Time: 9:41
 */

class Application_Form_Product extends Zend_Form
{
    public function init()
    {




        $this->setMethod('post');
        $this->setName('product');

        //$this->setAction(Zend_Controller_Front::getInstance()->getBaseUrl().'/auth/login');

        $name = new Zend_Form_Element_Text('name');
        $name  ->setLabel('product name')
            ->setRequired(true);



        $description = new Zend_Form_Element_Textarea('description');
        $description  ->setLabel('description')
            ->setRequired(true);


        $catogory = new Application_Form_Element_CatogorySelect('catogory');
        $catogory->setLabel('catogory')
            ->setRequired(true);

      /*  $catogory = new Zend_Form_Element_Text('catogory');
        $catogory  ->setLabel('catogory')
            ->setRequired(true);*/

      /*  $catogory = new Zend_Form_Element_Select('catogory');
        $catogory->setLabel('catogory')
                    ->setMultiOptions(array(
                        '0' => 'home',
                        '1' => 'outdoor',
                    ))
            ->setRequired(true)->addValidator('NotEmpty', true);*/


        $price = new Zend_Form_Element_Text('price');
        $price  ->setLabel('price')
            ->setRequired(true);


        $submit = new Zend_Form_Element_Submit('save');
        $submit  ->setLabel('save')
          ->setAttrib('class','btn btn-basic btn-fix');



        $this->addelements(array($name, $description, $catogory, $price, $submit));

    }

}
<?php
/**
 * Created by PhpStorm.
 * User: s.kievit
 * Date: 7-9-2015
 * Time: 14:43
 */

class Application_Form_Element_CatogorySelect extends Zend_Form_Element_Select {

    public function init()
    {
        $oCatogoryTb = new Application_Model_CatogoryMapper();


        $value = array_map('get_object_vars', $oCatogoryTb->fetchAll());

       // var_dump($oCatogoryTb->fetchAll());
       // var_dump($value);
        foreach ($value as $oCatogory) {
            $this->addMultiOption($oCatogory['_id'], $oCatogory['_name']);
        }
    }
}


?>
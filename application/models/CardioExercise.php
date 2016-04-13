<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 13-4-2016
 * Time: 15:27
 */

class Application_Model_CardioExercise
{

    protected $db_table;

    public $name;
    public $type;
    public $distance;
    public $time;

    public function __construct(array $options = null)
    {

    }

    public function addResult($value)
    {
        if($this->type = 'afstand'){
            $this->time = $value;
        }
        else{
            $this->distance = $value;
        }
    }

    public function getResults(){
        if($this->type = 'afstand'){
            return $this->time;
        }
        else{
            return $this->distance;
        }

    }

    public function save(){

    }
}


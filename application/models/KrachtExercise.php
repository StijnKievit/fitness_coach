<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 13-4-2016
 * Time: 15:27
 */

class Application_Model_KrachtExercise
{

    protected $db_table;

    public $name;
    public $type;
    public $sets;
    public $reps;

    public $day;
    public $week;

    private $results;

    public function __construct(array $options = null)
    {
        $this->results = array();
    }

    public function addResult($weights)
    {
       array_push($this->results,$weights);
    }

    public function getResults(){
        return $this->results;
    }

    public function save(){

    }
}


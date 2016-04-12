<?php
/**
 * Created by PhpStorm.
 * User: s.kievit
 * Date: 27-10-2015
 * Time: 11:57
 */

class Application_Model_Competenties
{
    protected $_dbAdapter;

    public function __construct(){
        $this->_dbAdapter =  Zend_Db_Table::getDefaultAdapter();
    }

    public function getCompetenties()
    {

        /*Fetch alle competenties*/
        $current_comp = $this->_dbAdapter->fetchAll("SELECT intern_competenties.id, intern_competenties.name AS `name`, intern_comp_type.name AS `type`, intern_competenties.completed
                                                    FROM intern_competenties
                                                    INNER JOIN intern_comp_type ON intern_competenties.type_id = intern_comp_type.id
                                                    ORDER BY `type`");

        $ordered_array = array(
            'Adviseren' => array(),
            'Analyseren' => array(),
            'Implementeren' => array(),
            'Onderzoeken' => array(),
            'Ontwerpen' => array(),
            'Realiseren' => array()

        );

        foreach($current_comp as $item)
        {
            if(array_key_exists($item['type'], $ordered_array))
            {
                array_push( $ordered_array[$item['type']], $item);
            }
        }

        /*create object en stuur naar view*/

        $object = new stdClass();
        foreach( $ordered_array as $key => $value)
        {
            $object->$key = $value;
        }
        return $object;
    }

    public function getStoryCompetenties($story_id){

        $result = '';
        try{
            $result = $this->_dbAdapter->fetchAll("SELECT cp.id, cp.`name` AS name , intern_comp_type.`name` AS type
                                             FROM intern_story_comp
                                             INNER JOIN intern_competenties AS cp ON intern_story_comp.comp_id = cp.id
                                             LEFT JOIN intern_comp_type ON intern_comp_type.id = cp.type_id
                                             WHERE intern_story_comp.story_id = ?", array($story_id));
        }
        catch(Exception $e){

        }

        return $result;

    }
}

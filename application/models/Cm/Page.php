<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 2-12-2015
 * Time: 21:09
 */

class Application_Model_Cm_Page
{
    protected $_dbAdapter;
    protected $_tableName;
    protected $_rubriek_tableName;
    protected $_pos_tableName;

    protected $page_id;
    protected $page_code;
    protected $page_status;

    protected $page_rubrieken = array();


    public function __construct($code)
    {
        $this->_dbAdapter = Zend_Db_Table::getDefaultAdapter();
        $this->_tableName = 'intern_cm_page';
        $this->_rubriek_tableName = 'intern_cm_page_rubriek';
        $this->_pos_tableName = 'intern_cm_position';

        $sql = "SELECT * FROM ". $this->_tableName." WHERE code = "."'".$code."' AND active = 'ja'";
        $result = $this->_dbAdapter->fetchAssoc($sql);

        $this->page_id = $result[1]['id'];
        $this->page_code = $result[1]['code'];
        $this->page_status = $result[1]['active'];
    }

    public function getPage($code)
    {
        //get pagina content

        $sql = "SELECT *
                FROM ".$this->_tableName." AS p
                JOIN ".$this->_rubriek_tableName." AS r ON r.page_id = p.id
                JOIN ".$this->_pos_tableName." AS pos ON pos.rubriek_id = r.id
                HAVING p.active = 'ja' AND r.active = 'ja' AND p.code = "."'".$code."'"."
                ORDER BY r.order_index";

        $result = $this->_dbAdapter->fetchAll($sql);
        return $result;
    }

    public function updatePage($id)
    {
        //update page content
    }
    public function insertPage($code)
    {
        //maak een nieuwe pagina aan
        $this->_dbAdapter->insert( $this->_tableName, array('code' => $code, 'active' => 'ja', 'index' => 'ja'));

    }
    public function deletePage($id){

        //verwijder de pagina
        $this->_dbAdapter->delete($this->_tableName, "id = $id");


        //verwijder de pagina's die bij deze rubriek horen
        $rubriek = new Application_Model_Cm_Rubriek();

        //verwijder de pagina (incl. alle pagina rubriekken die er bij horen
    }
    public function pageStatus($id, $value)
    {
        //update actief ja of nee
    }

    public function pageIndex($id, $value)
    {

    }


}
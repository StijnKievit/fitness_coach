<?php
/**
 * Created by PhpStorm.
 * User: s.kievit
 * Date: 11-1-2016
 * Time: 14:06
 */

class Application_Model_Pages
{

    protected $db_table;

    public function __construct(array $options = null){

        $this->db_table = new Application_Model_DbTable_Pages();

    }

    public function fiterByCode($code)
    {
       $results = $this->db_table->fetchAll(
            $this->db_table->select()
            ->where("code LIKE ?", '%'.$code.'%')
        );

        var_dump($results);
        return $results;
    }

    public function getByCode($code)
    {
        $results = $this->db_table->fetchAll(
            $this->db_table->select()
                ->where("code = ?", $code)
        );

        return $results;
    }

    public function getById($id)
    {
        $results = $this->db_table->fetchAll(
            $this->db_table->select()
                ->where("id = ?", $id)
        );

        return $results;
    }

    public function getAll()
    {
        $results = $this->db_table->fetchAll();
        return $results;
    }
    public function update($id, $title, $code, $content, $index)
    {
        $data = array(
            "title" => trim($title),
            "code" => str_replace(' ', '_', trim($code)),
            "content" => trim($content),
            "show_overview" => $index
        );

        $result = $this->db_table->update($data, array("id = $id"));

        return $result;
    }
    public function create($title, $code, $content, $index)
    {
        $data = array(
            "title" => trim($title),
            "code" => str_replace(' ', '_', trim($code)),
            "content" => trim($content),
            "show_overview" => $index
        );

        $result = $this->db_table->insert($data);

        return $result;
    }
    public function remove($id)
    {
        return $this->db_table->delete("id = $id");
    }

    public function getActivePages()
    {
        $results = $this->db_table->fetchAll(
            $this->db_table->select()
                ->where("show_overview = ?", 'ja')
        );

        return $results;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: s.kievit
 * Date: 17-12-2015
 * Time: 13:53
 */
class Application_Model_Comment
{
    protected $_dbAdapter;
    protected $_db_comment_table;

    protected $id;
    protected $comment;
    protected $user;
    protected $story_id;
    protected $time_stamp;
    protected $user_id;

    public function __construct()
    {
        $this->_dbAdapter = Zend_Db_Table::getDefaultAdapter();
        $this->_db_comment_table = new Application_Model_DbTable_Comment();
    }

    public function set_comment($comment){
        $this->comment = $comment;
    }

    public function get_comment(){
        return $this->comment;
    }

    public function set_user($user){
        $this->user = $user;
    }

    public function get_user(){
        return $this->user;
    }

    public function set_user_id($user_id){
        $this->user_id = $user_id;
    }

    public function get_user_id(){
        return $this->user_id;
    }

    public function set_story_id($story_id){
        $this->story_id = $story_id;
    }

    public function get_time_stamp(){
        return $this->time_stamp;
    }

    public function set_time_stamp($timestamp){
        $this->time_stamp = $timestamp;
    }

    public function set_id($id){
        $this->id = $id;
    }

    public function get_id(){
        return $this->id;
    }

    public function addComment()
    {

    }

    public function getComment()
    {

    }

    public function save(){

        $insert_data = array(

            'story_id' => $this->story_id,
            'time_stamp' => date('Y-m-d H:i:s'),
            'comment' => $this->comment,
            'user_id' => $this->user,

        );

        //make sure a all the users get an email after an comment is made:

        //get all user id's by story id

        //get all email adresses

        //send email with notification
        $mail = new Application_Model_Mail_Basicmail();
        $mail->setBodyHtml('commentaar', '<div><p>Er is een reactie toegevoegd.</p><p>Story id:<br />'.$this->story_id.'</p><p>comment:<br />'.$this->comment.'</p></div>');
        $mail->setFrom();
        $mail->setSubject('commentaar reactie');
        $mail->addTo('0875013@hr.nl', 'Stijn Kievit');
        $mail->send();

        //Er is nog een opmerking geplaatst bla bla bla

        try{
            $this->_db_comment_table->insert($insert_data);
        }
        catch(Exception $e){
            throw $e;
        }
    }
    public function delete(){
        $this->_db_comment_table->delete("id = $this->id");
    }
}
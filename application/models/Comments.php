<?php
/**
 * Created by PhpStorm.
 * User: s.kievit
 * Date: 17-12-2015
 * Time: 14:13
 */
class Application_Model_Comments
{
    protected $_comments = array();

    public function get_comments($story_id)
    {
        $db_table = new Application_Model_DbTable_Comment();
        $user_table = new Application_Model_DbTable_User();
        $results = $db_table->fetchAll(
            $db_table->select()
                        ->where('story_id = ?', $story_id )
                        ->order(array('time_stamp DESC'))
        );

        foreach($results as $comment)
        {
            $cur_user = $user_table->fetchRow(
                $user_table->select()
                ->where('id = ?', $comment['user_id'])
            );

            $commentOBJ = new Application_Model_Comment();
            $commentOBJ->set_id($comment['id']);
            $commentOBJ->set_comment($comment['comment']);
            $commentOBJ->set_story_id($story_id);
            $commentOBJ->set_user($cur_user['name']);
            $commentOBJ->set_user_id($comment['user_id']);
            $commentOBJ->set_time_stamp($comment['time_stamp']);




            array_push($this->_comments, $commentOBJ);
        }


        /*fix*/
        return $this->_comments;
    }
}


<?php
/**
 * Created by PhpStorm.
 * User: s.kievit
 * Date: 17-12-2015
 * Time: 13:49
 */
class CommentController extends Zend_Controller_Action
{
    protected $dbAdapter;
    protected $comments;

    public function init()
    {
        if(!Auth_AuthChecker::getInstance()->getStatus())
        {
            $this->_helper->redirector('login', 'auth', null, array());
        }
        $this->dbAdapter = Zend_Db_Table::getDefaultAdapter();
    }
    public function indexAction(){
        $request = $this->getRequest();

        if ($this->getRequest()->isPost()) {

            $this->_helper->layout()->disableLayout();
            $this->_helper->viewRenderer->setNoRender(true);

            $user_id = $request->getParam('comment_user');
            $story_id = $request->getParam('comment_story_id');
            $content = $request->getParam('comment_content');

            $comment = new Application_Model_Comment();
            $comment->set_comment(trim($content));
            $comment->set_user($user_id);
            $comment->set_story_id($story_id);


            $comment->save();
        }

    }
}

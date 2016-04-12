<?php
/**
 * Created by PhpStorm.
 * User: s.kievit
 * Date: 11-1-2016
 * Time: 14:20
 */
class PagesController extends Zend_Controller_Action
{
        protected $_model;

        public function init()
        {
            $this->_model = new Application_Model_Pages();
            if(!Auth_AuthChecker::getInstance()->getStatus()){
                $this->_helper->redirector('login', 'auth', null, array());
            }
        }

        public function indexAction()
        {

            if(Auth_AuthChecker::getInstance()->isAdmin())
            {
                $pages = $this->_model->getAll();
                $this->view->pages = $pages;
            }
            else{
                $this->_helper->redirector('overview', 'pages', null, array());
            }



        }

        public function codeAction()
        {
            $code = '';

            $request = $this->getRequest();

            if($request->getParam('val') != '') {


                $result =$this->_model->getByCode($request->getParam('val'));


                $this->view->page_content = $result;

            }

        }

        public function overviewAction()
        {
            $result = $this->_model->getActivePages();

            $this->view->pages = $result;
            $this->view->content = $this->loadContent('pages_');
        }

        public function newAction(){

            if(Auth_AuthChecker::getInstance()->isAdmin()) {
                $request = $this->getRequest();
                if ($request->isPost()) {
                    $params = $request->getParams();
                    $result = $this->_model->create($params['page_title'], $params['page_code'], $params['page_content'], $params['show_overview']);
                    $this->view->result = $result;


                }
            }
        }

        public function editAction(){

            if(Auth_AuthChecker::getInstance()->isAdmin()) {

                $request = $this->getRequest();
                $db_table = new Application_Model_DbTable_Pages();

                if ($request->isPost()) {
                    $params = $request->getParams();
                    $update_result = $this->_model->update($params['id'], $params['page_title'], $params['page_code'], $params['page_content'], $params['show_overview']);
                    $this->view->result = $update_result;
                    $result = $db_table->fetchAll(
                        $db_table->select()
                            ->where("id = ? ", $params['id'])
                    );

                    $this->view->title = $result[0]->title;
                    $this->view->content = $result[0]->content;
                    $this->view->id = $result[0]->id;
                    $this->view->identifier = $result[0]->code;
                    $this->view->index = $result[0]->show_overview;

                    $this->view->title = $params['page_title'];
                    $this->view->content = $params['page_content'];
                    $this->view->id = $params['id'];
                    $this->view->identifier = str_replace(' ', '_', trim($params['page_code']));
                    $this->view->index = $params['show_overview'];

                } else {
                    $id = $request->getParam('id');


                    $result = $db_table->fetchAll(
                        $db_table->select()
                            ->where("id = ? ", $id)
                    );

                    $this->view->title = $result[0]->title;
                    $this->view->content = $result[0]->content;
                    $this->view->id = $result[0]->id;
                    $this->view->identifier = $result[0]->code;
                    $this->view->index = $result[0]->show_overview;
                }
            }


        }

        public function removeAction(){

            if(Auth_AuthChecker::getInstance()->isAdmin())
            {
                $request = $this->getRequest();
                $params = $request->getParams();
                $this->_model->remove($params['id']);

                $this->_helper->layout()->disableLayout();
                $this->_helper->viewRenderer->setNoRender(true);

                $this->_helper->redirector('index', 'pages', null, array());
            }

        }

        public function loadContent($page_prefix)
        {
            $db_table = new Application_Model_DbTable_Pages();
            $pages = $db_table->fetchAll(
                $db_table->select()
                    ->where('code LIKE ?', $page_prefix.'%')
            );

            return $pages;
        }
}

?>
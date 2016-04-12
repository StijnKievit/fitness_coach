<?php
/**
 * Created by PhpStorm.
 * User: s.kievit
 * Date: 3-9-2015
 * Time: 11:23
 */

class AuthController extends Zend_Controller_Action
{

    public function init()
    {

    }

    public function indexAction(){


    }



    public function loginAction()
    {


        if($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();

            if (!is_null($formData['email']) && !is_null($formData['ww'])) {

                $email = strtolower($formData['email']);
                $password = md5($formData['ww']);

                var_dump($password);

                $authAdapter = $this->getAuthAdapter();

                $authAdapter->setIdentity($email)
                    ->setCredential($password);


                $auth = Zend_Auth::getInstance();
                $result = $auth->authenticate($authAdapter);


                if ($result->isValid()) {


                    $storage = $auth->getStorage();
                    $storage->write($authAdapter->getResultRowObject(
                        null,
                        'password'
                    ));

                    $userIdentity = $storage->read($authAdapter);

                    $this->_helper->redirector('index', 'index', null, array());
                    $this->view->loginMessage = "Welcome ".$userIdentity->username."!";
                } else {

                    $this->view->loginMessage = "Sorry, we herkenen uw email en wachtwoord combinatie niet.";
                }

            }
        }



    }

    public function logoutAction(){

        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector('login', 'auth', null, array());
    }

    private function getAuthAdapter(){

        $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
        $authAdapter->setTableName('user')
                    ->setIdentityColumn('email')
                    ->setCredentialColumn('password');

        return $authAdapter;

    }



}


?>
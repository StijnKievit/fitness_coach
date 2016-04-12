<?php
/**
 * Created by PhpStorm.
 * User: s.kievit
 * Date: 1-10-2015
 * Time: 11:40
 */

class RegisterController extends Zend_Controller_Action
{
    protected $dbAdapter;

    public function init()
    {
        if(!Auth_AuthChecker::getInstance()->isAdmin())
        {
            if(!Auth_AuthChecker::getInstance()->getStatus())
            {
                $this->_helper->redirector('login', 'auth', null, array());
            }
            else
            {
                $this->_helper->redirector('index', 'index', null, array());
            }
        }

        $this->dbAdapter = Zend_Db_Table::getDefaultAdapter();
    }

    public function indexAction()
    {
        $request = $this->getRequest();

        if ($this->getRequest()->isPost()) {
            if ($request->getPost())
            {
                $voornaam = $request->getParam('voornaam');
                $achternaam = $request->getParam('achternaam');
                $email = $request->getParam('email');
                $password1 = $request->getParam('pass1');
                $password2 = $request->getParam('pass2');

                if($voornaam != '' && $achternaam != '' & $email != '' && $password1 != '')
                {
                    if($password1 == $password2)
                    {
                        $input = array(
                            'password' => md5($password2),
                            'email' => strtolower($email),
                            'name' => $voornaam . ' ' . $achternaam,
                            'role' => 'user'
                        );

                        try
                        {

                            $email_exists = $this->dbAdapter->fetchAll("SELECT * FROM intern_user WHERE email = ?", array($email));
                            if(count($email_exists) < 1)
                            {
                                $this->dbAdapter->insert('intern_user', $input);
                            }
                            else
                            {
                                $this->view->message = "Er bestaat al een account met dit email adres!";
                            }
                        }
                        catch(Exception $e)
                        {

                        }
                    }
                    else{
                        $this->view->message = "Uw wachtwoorden komt niet overeen!";
                    }
                }
                else{
                    $this->view->message = "Niet alle velden zijn ingevuld!";
                }
            }
        }
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: s.kievit
 * Date: 10-9-2015
 * Time: 10:31
 */
class Auth_AuthMenuFactory
{

    private $adminUrl;
    private $userUrl;
    private $unregUrl;

    private $baseUrl;
    private $menuFactory;
    private static $instance;
    private $shopCart;


    private function __construct()
    {

        $this->menuFactory = Zend_Auth::getInstance();

        $this->baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();

        $this->shopCart = new Application_Model_ShopCart_Cart();

        $this->adminUrl = array(

            array(

                'name' => 'Products',
                'url' => $this->baseUrl . '/product'
            ),
            array(

                'name' => 'Catogories',
                'url' => $this->baseUrl . '/catogory'
            ),
            array(

                'name' => 'Users',
                'url' => $this->baseUrl . '/register'
            ),
            array(

                'name' => '|',
                'url'  => '#'

            ),
            array(

                'name' => 'Me',
                'url' => $this->baseUrl . '/index'
            ),
            array(

                'name' => 'Logout',
                'url' => $this->baseUrl . '/auth/logout'
            )


        );

        $this->userUrl = array(

            array(

                'name' => 'Me',
                'url' => $this->baseUrl . '/index'
            ),
            array(

                'name' => 'Cart ('.$this->shopCart->getAmountItems().')' ,
                'url' => $this->baseUrl . '/cart'
            ),
            array(

                'name' => 'Log out',
                'url' => $this->baseUrl . '//auth/logout'
            )
        );

        $this->unregUrl = array(

            array(

                'name' => 'Cart ('.$this->shopCart->getAmountItems().')' ,
                'url' => $this->baseUrl . '/cart'
            ),
            array(

                'name' => 'Login',
                'url' => $this->baseUrl . '/auth/login'
            )
        );


    }

    public static function getInstance()
    {

        if (is_null(self::$instance))
        {
            self::$instance = new self();
        }
        return self::$instance;

    }

    public function generateMenu()
    {
        if (Auth_AuthChecker::getInstance()->isUser() && !Auth_AuthChecker::getInstance()->isAdmin())
        {

            echo '<ul>';

                foreach ($this->userUrl as $uri)
                {
                    echo "<li>";
                    echo "<a href='" . $uri["url"] . "'>" . $uri['name'] . "</a>";
                    echo "</li>";
                }
                //form echo (need something smarter)
                echo "<li class='search'>";
                echo "<form  method='post' action='".$this->baseUrl."/search/index'  id='searchform'>";
                echo "<input  type='text' name='input'>";
                echo "<input class='btn btn-basic btn-fix'  type='submit' name='search' value='Search'>";
                echo "</form>";
                echo "</li>";

            echo '</ul>';


        }
        if (Auth_AuthChecker::getInstance()->isAdmin())
        {
            echo '<ul>';

            foreach ($this->adminUrl as $uri)
            {
                echo "<li>";
                echo "<a href='" . $uri["url"] . "'>" . $uri['name'] . "</a>";
                echo "</li>";
            }

            echo '</ul>';

        }

        if (!Auth_AuthChecker::getInstance()->isUser())
        {

            echo '<ul>';

                foreach ($this->unregUrl as $uri)
                {
                    echo "<li>";
                    echo "<a href='" . $uri["url"] . "'>" . $uri['name'] . "</a>";
                    echo "</li>";
                }

                //form echo:
                echo "<li class='search'>";
                    echo "<form  method='post' action='".$this->baseUrl." /search/index'  id='searchform'>";
                            echo "<input  type='text' name='input'>";
                            echo "<input class='btn btn-basic btn-fix'  type='submit' name='search' value='Search'>";
                    echo "</form>";
                echo "</li>";

            echo '</ul>';

        }

    }

}


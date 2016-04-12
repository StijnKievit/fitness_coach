<?php
/**
 * Created by PhpStorm.
 * User: s.kievit
 * Date: 8-10-2015
 * Time: 11:44
 */
class Application_Model_Mail_Basicmail extends Zend_Mail
{


    public function setFrom()
    {
        parent::setFrom("0875013@hr.nl","Stijn Kievit - Intern");
    }

    public function setBodyHtml($onderwerp, $content)
    {
        $body = '
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml">
                 <head>
                  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                  <title>Demystifying Email Design</title>
                  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
                </head>
                <body style="font-family:Arial;">
                    <table align="center" border="1" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse;">
                     <tr>
                        <td align="center" bgcolor="#012429" style="padding: 40px 0 30px 0;">
                            <a href="http://stage-stijn.xsarus.net/" style="text-decoration: none"><h1 style="color: #ffffff; display: block; width: 400px; margin: 0 auto">Stijn intern</h1></a>
                        </td>
                     </tr>
                     <tr>
                      <td bgcolor="#FFF" style="padding: 30px 20px 30px 20px;">
                        <h2>U heeft mail m.b.t. '.trim($onderwerp).'</h2>
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                <td>
                                    '.$content.'
                                </td>
                            </tr>
                        </table>
                      </td>
                     </tr>
                    <tr>
                      <td bgcolor="#012429" style="padding: 30px 0px 30px 20px;">
                       <span style="color:#FFF">&copy; Stijn Kievit - 2016</span>
                      </td>
                     </tr>
                    </table>
                </body>
            </html>
        ';

        parent::setBodyHtml($body, "UTF-8");
    }

}


?>
<?php
class UnitTest extends PHPUnit_Framework_TestCase
{
    private $CI;

    public function setUp()
    {
        $this->CI = &get_instance();
    }

    public function testLogin()
    {
        $_POST['email'] = 'foo@bar.com';
        $_POST['password'] = 'password123';
        require_once(FCPATH . APPPATH . 'controllers/user.php');
        $this->CI->User = new User();
        echo $this->CI->User->login();
    }
}
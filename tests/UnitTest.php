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
        $email = 'foo@bar.com';
        $password = 'password123';
        $_POST['email'] = $email;
        $_POST['password'] = $password;
        $this->CI->User = load_controller('User');
        $result = json_decode($this->CI->User->login())->output;
        $this->assertEquals($result->email, $email);
    }
}
<?php

class UnitTest extends PHPUnit_Framework_TestCase
{
    private $CI;
    
    private function login($email = 'foo@bar.com', $password = 'password123')
    {
        $_POST['email'] = $email;
        $_POST['password'] = $password;
        $this->CI->User = load_controller('User');
        return json_decode($this->CI->User->login())->output;
    }

    private function register($email = 'biz@baz.com', $password = 'password123')
    {
        $_POST['email'] = $email;
        $_POST['password'] = $password;
        $this->CI->User = load_controller('User');
        return json_decode($this->CI->User->register())->output;
    }

    private function permissions($resource = 'administrator')
    {
        $output = $this->login();
        $_POST['token'] = $output->token;
        $_POST['resource'] = $resource;
        return json_decode($this->CI->User->permissions())->output;
    }

    public function setUp()
    {
        $this->CI = &get_instance();
        $this->CI->CLI = load_controller('CLI');
        $this->CI->CLI->install();
        $this->CI->CLI->add('administrator', 'foo@bar.com', 'password123');
    }

    public function testLoginSuccess()
    {
        $this->assertTrue($this->login()->status);
    }

    public function testLoginFail()
    {
        $this->assertFalse($this->login('')->status);
        $this->assertFalse($this->login('foo@bar.com', 'password456')->status);
    }

    public function testRegisterSuccess()
    {
        $this->assertTrue($this->register()->status);
    }

    public function testRegisterFail()
    {
        $this->assertFalse($this->register('')->status);
    }

    public function testPermissionsSuccess()
    {
        $this->assertTrue($this->permissions()->status);
    }

    public function testPermissionsFail()
    {
        $this->assertFalse($this->permissions('')->status);
    }
    
}
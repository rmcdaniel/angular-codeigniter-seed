<?php

require_once 'vendor/autoload.php';

class WebDriverDemoShootout extends Sauce\Sausage\WebDriverTestCase
{

    protected $base_url = 'http://tutorialapp.saucelabs.com';

    public static $browsers = array(
        // run FF15 on Vista on Sauce
        array(
            'browserName' => 'firefox',
            'desiredCapabilities' => array(
                'version' => '15',
                'platform' => 'VISTA'
            )
        )
    );

    protected function randomUser()
    {
        $id = uniqid();
        return array(
            'username' => "fakeuser_$id",
            'password' => 'testpass',
            'name' => "Fake $id",
            'email' => "$id@fake.com"
        );
    }

    protected function doLogin($username, $password)
    {
        $this->url('/');
        $this->byName('login')->value($username);
        $this->byName('password')->value($password);
        $this->byCss('input.login')->click();

        $this->assertTextPresent("Logged in successfully", $this->byId('message'));
    }

    protected function doLogout()
    {
        $this->url('/logout');
        $this->assertTextPresent("Logged out successfully", $this->byId('message'));
    }

    protected function doRegister($user, $logout = false)
    {
        $user['confirm_password'] = isset($user['confirm_password']) ?
            $user['confirm_password'] : $user['password'];
        $this->url('/register');
        $this->byId('username')->value($user['username']);
        $this->byId('password')->value($user['password']);
        $this->byId('confirm_password')->value($user['confirm_password']);
        $this->byId('name')->value($user['name']);
        $this->byId('email')->value($user['email']);
        $this->byId('form.submitted')->click();

        if ($logout)
            $this->doLogout();
    }

    public function setUpPage()
    {
        $this->url('http://tutorialapp.saucelabs.com');
    }

    public function testLoginFailsWithBadCredentials()
    {
        $fake_username = uniqid();
        $fake_password = uniqid();

        $this->url('/');
        $this->byName('login')->value($fake_username);
        $this->byName('password')->value($fake_password);
        $this->byCss('input.login')->click();

        $this->assertTextPresent("Failed to login.", $this->byId('message'));
    }

    public function testLogin()
    {
        $user = $this->randomUser();
        $this->doRegister($user, true);
        $this->doLogin($user['username'], $user['password']);
    }

    public function testLogout()
    {
        $this->doRegister($this->randomUser(), true);
    }

    public function testRegister()
    {
        $user = $this->randomUser();
        $this->doRegister($user);
        $logged_in_text = "You are logged in as {$user['username']}";
        $this->assertTextPresent($logged_in_text);
    }

    public function testRegisterFailsWithoutUsername()
    {
        $user = $this->randomUser();
        $user['username'] = '';
        $this->doRegister($user);
        $this->assertTextPresent("Please enter a value");
    }

    public function testRegisterFailsWithoutName()
    {
        $user = $this->randomUser();
        $user['name'] = '';
        $this->doRegister($user);
        $this->assertTextPresent("Please enter a value");
    }

    public function testRegisterFailsWithMismatchedPasswords()
    {
        $user = $this->randomUser();
        $user['confirm_password'] = uniqid();
        $this->doRegister($user);
        $this->assertTextPresent("Fields do not match");
    }

    public function testRegisterFailsWithBadEmail()
    {
        $user = $this->randomUser();
        $user['email'] = 'test';
        $this->doRegister($user);
        $this->assertTextPresent("An email address must contain a single @");
        $this->byId('email')->clear();
        $this->byId('email')->value('@bob.com');
        $this->byId('form.submitted')->click();
        $this->assertTextPresent("The username portion of the email address is invalid");
        $this->byId('email')->clear();
        $this->byId('email')->value('test@bob');
        $this->byId('form.submitted')->click();
        $this->assertTextPresent("The domain portion of the email address is invalid");
    }

}

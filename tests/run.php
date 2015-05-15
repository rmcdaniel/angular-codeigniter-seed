<?php

require_once 'vendor/autoload.php';

class Run extends Sauce\Sausage\WebDriverTestCase
{

    protected $start_url = 'http://localhost';

    public static $browsers = array(
        array(
            'browserName' => 'chrome',
            'desiredCapabilities' => array(
                'platform' => 'Linux'
          )
        )
    );

    public function testTitle()
    {
        $this->assertContains("AngularJS CodeIgniter Seed", $this->title());
    }


    public function testUrl()
    {
        $this->assertContains("http://localhost/#/home", $this->url());
    }

    public function testLogin()
    {
        $this->url('http://localhost/#/login');
        $this->assertTextNotPresent("Logout");
        $this->byName('email')->value('foo@bar.com');
        $this->byName('password')->value('password123');
        $this->byName('login')->submit();
        $this->assertTextPresent("Logout");
    }

}

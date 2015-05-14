<?php

// require_once "vendor/autoload.php";
define("APP_PATH", realpath(dirname(__FILE__).'/../../apps/UICatalog.app.zip'));
if (!APP_PATH) {
    die("App did not exist!");
}
require_once('PHPUnit/Extensions/AppiumTestCase.php');

class AccessorTests extends PHPUnit_Extensions_AppiumTestCase
{
    public function testFindElementByIOSAutomation()
    {
        $el = $this->byIOSUIAutomation('.elements()[0]');
        $name = $el->attribute('name');
        $this->assertEquals('UICatalog', $name);
    }

    public static $browsers = array(
        array(
            'local' => true,
            'port' => 4723,
            'browserName' => '',
            'desiredCapabilities' => array(
                'app' => APP_PATH,
                'platformName' => 'iOS',
                'platformVersion' => '7.1',
                'deviceName' => 'iPhone Simulator'
            )
        )
    );
}

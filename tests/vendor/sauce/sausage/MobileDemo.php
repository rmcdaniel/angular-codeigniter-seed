<?php
// To run this test, install Sausage (see http://github.com/jlipps/sausage-bun
// to get the curl one-liner to run in this directory), then run:
//     vendor/bin/phpunit SauceTest.php

require_once "vendor/autoload.php";
define("APP_URL", "http://appium.s3.amazonaws.com/TestApp6.0.app.zip");

class MobileTest extends Sauce\Sausage\MobileTestCase
{
    protected $numValues = array();

    public static $browsers = array(
        array(
            'browserName' => '',
            'desiredCapabilities' => array(
                'appium-version' => '1.0',
                'platformName' => 'iOS',
                'platformVersion' => '7.0',
                'deviceName' => 'iPhone Simulator',
                'name' => 'Appium/Sauce iOS Test, PHP',
                'app' => APP_URL
            )
        )
    );

    public function elemsByClassName($klass)
    {
        return $this->elements($this->using('class name')->value($klass));
    }

    protected function populate()
    {
        $elems = $this->elemsByClassName('UIATextField');
        foreach ($elems as $elem) {
            $randNum = rand(0, 10);
            $elem->value($randNum);
            $this->numValues[] = $randNum;
        }
    }

    public function testUiComputation()
    {
        $this->populate();
        $buttons = $this->elemsByClassName('UIAButton');
        $buttons[0]->click();
        $texts = $this->elemsByClassName('UIAStaticText');
        $this->assertEquals(array_sum($this->numValues), (int)($texts[0]->text()));
    }
}

<?php

// require_once "vendor/autoload.php";
define("APP_PATH", realpath(dirname(__FILE__).'/../../apps/selendroid-test-app.apk'));
if (!APP_PATH) {
    die("App did not exist!");
}
require_once('PHPUnit/Extensions/AppiumTestCase.php');

class ContextTests extends PHPUnit_Extensions_AppiumTestCase
{
    public function testCurrentContext()
    {
        $context = $this->context();
        $this->assertEquals('NATIVE_APP', $context);
    }

    public function testMoveToCorrectContext()
    {
        $this->enterWebview();
        $context = $this->context();
        $this->assertEquals('WEBVIEW_1', $context);
    }

    public function testActuallyInWebview()
    {
        $this->enterWebview();
        $this->byCssSelector('input[type=submit]')->click();
        $el = $this->byXPath("//h1[contains(., 'This is my way')]");
        $this->assertNotNull($el);
    }

    public function testMoveBackToNativeContext()
    {
        $this->enterWebview();
        $this->context("NATIVE_APP");
        $this->assertEquals('NATIVE_APP', $this->context());
    }

    public function testContextsList()
    {
        $expected_contexts = array(
            0 => "NATIVE_APP",
            1 => "WEBVIEW_1"
        );

        $this->enterWebview();
        $contexts = $this->contexts();
        $this->assertEquals($expected_contexts, $contexts);
    }

    public function testInvalidContext()
    {
        try {
            $this->context("invalid name");
        } catch (Exception $e) {
            $this->assertEquals("No such context found.", $e->getMessage());
        }
    }

    public static $browsers = array(
        array(
            'local' => true,
            'port' => 4723,
            'browserName' => '',
            'desiredCapabilities' => array(
                'app' => APP_PATH,
                'platformName' => 'Android',
                'platformVersion' => '4.4',
                'deviceName' => 'Android Emulator'
            )
        )
    );

    protected function enterWebview()
    {
        $btn = $this->element($this->using('name')->value('buttonStartWebviewCD'));
        $btn->click();
        $this->context('WEBVIEW');
    }
}

<?php

require_once 'vendor/autoload.php';

class WebDriverDemo extends Sauce\Sausage\WebDriverTestCase
{
    public static $browsers = array(
        // run FF15 on Windows 8 on Sauce
        array(
            'browserName' => 'firefox',
            'desiredCapabilities' => array(
                'version' => '15',
                'platform' => 'Windows 2012',
            )
        )//,
        // run Mobile Safari on iOS
        //array(
            //'browserName' => '',
            //'desiredCapabilities' => array(
                //'app' => 'safari',
                //'device' => 'iPhone Simulator',
                //'version' => '6.1',
                //'platform' => 'Mac 10.8',
            //)
        //)//,
        // run Chrome on Linux on Sauce
        //array(
            //'browserName' => 'chrome',
            //'desiredCapabilities' => array(
                //'platform' => 'Linux'
          //)
        //),
        // run Chrome locally
        // array(
        //     'browserName' => 'chrome',
        //     'local' => true,
        //     'sessionStrategy' => 'shared'
        // )
    );

    public function setUpPage()
    {
        $this->url("http://sl-test.herokuapp.com/guinea_pig/file_upload");

        // set the method which knows if this is a file we're trying to upload
        $this->fileDetector(function($filename) {
            if(file_exists($filename)) {
                return $filename;
            } else {
                return NULL;
            }
        });
    }

    public function testFileUpload()
    {
        // for some reason byId('myfile') doesn't want to work
        $filebox = $this->byName('myfile');
        $this->sendKeys($filebox, "./kirk.jpg");

        $this->byId('submit')->submit();

        // $image = $this->byTag('img');

        $this->assertTextPresent("kirk.jpg (image/jpeg)");
    }
}

<?php

require_once 'vendor/autoload.php';

class SeleniumRCDemo extends Sauce\Sausage\SeleniumRCTestCase
{
    public static $browsers = array(
        // FF 11 on Sauce
        array(
            'browser' => 'firefox',
            'browserVersion' => '11',
            'os' => 'Windows 2003'
        )//,
         //Chrome on Linux on Sauce
        //array(
            //'browser' => 'googlechrome',
            //'browserVersion' => '',
            //'os' => 'Linux'
        //),
         //Chrome on local machine
        //array(
            //'browser' => 'googlechrome',
            //'local' => true
        //)
    );

    public function setUp()
    {
        $this->setBrowserUrl('http://saucelabs.com/test/guinea-pig');
    }

    public function postSessionSetUp()
    {
        $this->open('http://saucelabs.com/test/guinea-pig');
    }

    public function testTitle()
    {
        $this->assertTitle("I am a page title - Sauce Labs");
    }

    public function testLink()
    {
        $this->click('id=i am a link');
        $driver = $this;
        $title_test = function() use ($driver) {
            return ($driver->getTitle() == "I am another page title - Sauce Labs");
        };
        $this->spinAssert("Title never matched!", $title_test);
    }

    public function testTextbox()
    {
        $test_text = "This is some text";
        $this->click('id=i_am_a_textbox');
        $this->type('id=i_am_a_textbox', $test_text);
        $this->assertElementValueEquals('id=i_am_a_textbox', $test_text);
    }

    public function testSubmitComments()
    {
        $comment = "This comment rocks lots of rocks";
        $this->type('id=comments', $comment);
        $this->click('id=submit');
        $driver = $this;

        $comment_test =
            function() use ($comment, $driver)
            {
                $text = $driver->getText('id=your_comments');
                return ($text == "Your comments: $comment");
            }
        ;

        $this->spinAssert("Comment never showed up!", $comment_test);
    }

}

<?php

require_once 'vendor/autoload.php';

class Run extends Sauce\Sausage\WebDriverTestCase
{

    protected $start_url = 'http://localhost';

    public static $browsers = array(
        array(
            'browserName' => 'firefox',
            'desiredCapabilities' => array(
                'version' => '15',
                'platform' => 'Windows 2012',
            )
        ),
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

    // public function testLink()
    // {
    //     $link = $this->byId('i am a link');
    //     $link->click();
    //     $this->assertContains("I am another page title", $this->title());
    // }

    // public function testTextbox()
    // {
    //     $test_text = "This is some text";
    //     $textbox = $this->byId('i_am_a_textbox');
    //     $textbox->click();
    //     $textbox->clear();
    //     $this->keys($test_text);
    //     $this->assertEquals($textbox->value(), $test_text);
    // }

    // public function testSubmitComments()
    // {
    //     $comment = "This is a very insightful comment.";
    //     $this->byId('comments')->value($comment);
    //     $this->byId('submit')->submit();
    //     $driver = $this;

    //     $comment_test = function() use ($comment, $driver) {
    //         $text = $driver->byId('your_comments')->text();
    //         return $text == "Your comments: $comment";
    //     };

    //     $this->spinAssert("Comment never showed up!", $comment_test);

    // }

}

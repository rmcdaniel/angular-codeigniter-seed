<?php

/**
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 **/


define("APP_PATH", realpath(dirname(__FILE__).'/../../apps/ApiDemos-debug.apk'));
if (!APP_PATH) {
    die("App did not exist!");
}
require_once('PHPUnit/Extensions/AppiumTestCase.php');

define("SLEEPY_TIME", 2);

class TouchActionTests extends PHPUnit_Extensions_AppiumTestCase
{
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

    public function testTap()
    {
        $el = $this->byAccessibilityId('Animation');

        $action = $this->initiateTouchAction();
        $action->tap(array('element' => $el))->perform();

        $el = $this->byAccessibilityId('Bouncing Balls');
        $this->assertNotNull($el);
    }

    public function testTapXY()
    {
        sleep(SLEEPY_TIME);
        $el = $this->byAccessibilityId('Animation');

        $action = $this->initiateTouchAction();
        $action->tap(array('element' => $el, 'x' => 100, 'y' => 10))->perform();

        # give it some time
        sleep(SLEEPY_TIME);

        $el = $this->byAccessibilityId('Bouncing Balls');
        $this->assertNotNull($el);
    }

    public function testTapTwice()
    {
        $el = $this->byAccessibilityId('Text');

        $action = $this->initiateTouchAction();
        $action->tap(array('element' => $el))->perform();

        sleep(2);

        $el = $this->byAccessibilityId('LogTextBox');
        $action = $this->initiateTouchAction();
        $action->tap(array('element' => $el))->perform();

        $el = $this->byAccessibilityId('Add');
        $action = $this->initiateTouchAction();
        $action->tap(array('element' => $el, 'count' => 2))->perform();

        $els = $this->elements($this->using('class name')->value('android.widget.TextView'));
        // echo $els[1]->text();
        $this->assertEquals("This is a test\nThis is a test\n", $els[1]->text());
    }

    public function testPressAndRelease()
    {
        $el = $this->byAccessibilityId('Animation');
        $action = $this->initiateTouchAction();
        $action->press(array('element' => $el))->release()->perform();

        sleep(2);

        $el = $this->byAccessibilityId('Bouncing Balls');
        $this->assertNotNull($el);
    }

    public function testPressAndReleaseXY()
    {
        $el = $this->byAccessibilityId('Animation');
        $action = $this->initiateTouchAction();
        $action->press(array('element' => $el, 'x' => 100, 'y' => 10))->release()->perform();

        sleep(2);

        $el = $this->byAccessibilityId('Bouncing Balls');
        $this->assertNotNull($el);
    }

    public function testPressAndWait()
    {
        $el1 = $this->byAccessibilityId('Content');
        $el2 = $this->byAccessibilityId('Animation');
        $action = $this->initiateTouchAction();
        $action->press(array('element' => $el1))->moveTo(array('element' => $el2))->release()->perform();

        $el = $this->byAccessibilityId('Views')->click();

        $el = $this->byAccessibilityId('Expandable Lists')->click();

        $el = $this->byAccessibilityId('1. Custom Adapter')->click();

        $el = $this->byName('People Names');
        $action = $this->initiateTouchAction();
        $action->press(array('element' => $el))->wait(2000)->perform();

        // 'Sample menu' only comes up with a long press, not a press
        $el = $this->byName('Sample menu');
        $this->assertNotNull($el);
    }

    public function testPressAndMoveTo()
    {
        $el1 = $this->byAccessibilityId('Content');
        $el2 = $this->byAccessibilityId('Animation');
        $action = $this->initiateTouchAction();
        $action->press(array('element' => $el1))->moveTo(array('element' => $el2))->release()->perform();

        $el = $this->byAccessibilityId('Views');
        $this->assertNotNull($el);
    }

    public function testPressAndMoveToXY()
    {
        $el1 = $this->byAccessibilityId('Content');
        $el2 = $this->byAccessibilityId('Animation');
        $action = $this->initiateTouchAction();
        $action->press(array('element' => $el1))
               ->moveTo(array('element' => $el2, 'x' => 100, 'y' => 100))
               ->release()
               ->perform();

        $el = $this->byAccessibilityId('Views');
        $this->assertNotNull($el);
    }

    public function testLongPress()
    {
        $el1 = $this->byAccessibilityId('Content');
        $el2 = $this->byAccessibilityId('Animation');
        $action = $this->initiateTouchAction();
        $action->press(array('element' => $el1))
               ->moveTo(array('element' => $el2))
               ->release()
               ->perform();

        $el = $this->byAccessibilityId('Views')->click();

        $el = $this->byAccessibilityId('Expandable Lists')->click();

        $el = $this->byAccessibilityId('1. Custom Adapter')->click();

        $el = $this->byName('People Names');
        $action = $this->initiateTouchAction();
        $action->longPress(array('element' => $el))->perform();

        // 'Sample menu' only comes up with a long press, not a press
        $el = $this->byName('Sample menu');
        $this->assertNotNull($el);
    }

    public function testLongPressXY()
    {
        $el1 = $this->byAccessibilityId('Content');
        $el2 = $this->byAccessibilityId('Animation');
        $action = $this->initiateTouchAction();
        $action->press(array('element' => $el1))
               ->moveTo(array('element' => $el2))
               ->release()
               ->perform();

        $el = $this->byAccessibilityId('Views')->click();

        $el = $this->byAccessibilityId('Expandable Lists')->click();

        $el = $this->byAccessibilityId('1. Custom Adapter')->click();

        $el = $this->byName('People Names');
        $action = $this->initiateTouchAction();
        $action->longPress(array('element' => $el, 'x' => 10, 'y' => 120))->perform();

        // 'Sample menu' only comes up with a long press, not a press
        $el = $this->byName('Sample menu');
        $this->assertNotNull($el);
    }

    public function testDragAndDrop()
    {
        sleep(SLEEPY_TIME);
        $el1 = $this->byAccessibilityId('Content');
        $el2 = $this->byAccessibilityId('Animation');
        $this->scroll($el1, $el2);

        $this->byAccessibilityId('Views')->click();

        $this->byAccessibilityId('Drag and Drop')->click();

        $dd3 = $this->byId('com.example.android.apis:id/drag_dot_3');
        $dd2 = $this->byId('com.example.android.apis:id/drag_dot_2');

        // dnd is stimulated by longpress-move_to-release
        $action = $this->initiateTouchAction();
        $action->longPress(array('element' => $dd3))
               ->moveTo(array('element' => $dd2))
               ->release()
               ->perform();

        $el = $this->byId('com.example.android.apis:id/drag_result_text');
        $this->assertEquals('Dropped!', $el->text());
    }

    public function testDriverDragAndDrop()
    {
        sleep(SLEEPY_TIME);
        $el1 = $this->byAccessibilityId('Content');
        $el2 = $this->byAccessibilityId('Animation');
        $this->scroll($el1, $el2);

        $this->byAccessibilityId('Views')->click();

        $this->byAccessibilityId('Drag and Drop')->click();

        $dd1 = $this->byId('com.example.android.apis:id/drag_dot_1');
        $dd2 = $this->byId('com.example.android.apis:id/drag_dot_2');

        $this->dragAndDrop($dd1, $dd2);

        $el = $this->byId('com.example.android.apis:id/drag_result_text');
        $this->assertEquals('Dropped!', $el->text());
    }

    public function testDriverSwipe()
    {
        try {
            $this->byName('Views');
        } catch (PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            // we expect this
        }

        $this->swipe(0, 329, 0, 183);
        sleep(SLEEPY_TIME);
        $this->byName('Views');
    }
}

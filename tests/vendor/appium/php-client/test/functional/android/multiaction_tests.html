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

class MultiActionTests extends PHPUnit_Extensions_AppiumTestCase
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

    public function testParallelActions()
    {
        sleep(2);
        $el1 = $this->byAccessibilityId('Content');
        $el2 = $this->byAccessibilityId('Animation');
        $this->scroll($el1, $el2);

        $this->byAccessibilityId('Views')->click();

        $els = $this->elements($this->using('class name')->value('android.widget.TextView'));
        $this->scroll($els[count($els) - 1], $els[0]);

        try {
            $this->byAccessibilityId('Splitting Touches across Views')->click();
        } catch (Exception $e) {
            $els = $this->elements($this->using('class name')->value('android.widget.TextView'));
            $this->scroll($els[count($els) - 1], $els[0]);
            $this->byAccessibilityId('Splitting Touches across Views')->click();
        }

        $els = $this->elements($this->using('class name')->value('android.widget.ListView'));
        $action1 = $this->initiateTouchAction();
        $action1->press(array('element' => $els[0]))
                ->moveTo(array('x' => 10, 'y' => 0))
                ->moveTo(array('x' => 10, 'y' => -75))
                ->moveTo(array('x' => 10, 'y' => -600))
                ->release();

        $action2 = $this->initiateTouchAction();
        $action2->press(array('element' => $els[1]))
                ->moveTo(array('x' => 10, 'y' => 10))
                ->moveTo(array('x' => 10, 'y' => -300))
                ->moveTo(array('x' => 10, 'y' => -600))
                ->release();

        $multiAction = $this->initiateMultiAction();
        $multiAction->add($action1);
        $multiAction->add($action2);
        $multiAction->perform();
    }

    public function testParallelActionsWithWaits()
    {
        sleep(2);
        $el1 = $this->byAccessibilityId('Content');
        $el2 = $this->byAccessibilityId('Animation');
        $this->scroll($el1, $el2);

        $this->byAccessibilityId('Views')->click();

        $els = $this->elements($this->using('class name')->value('android.widget.TextView'));
        $this->scroll($els[count($els) - 1], $els[0]);

        try {
            $this->byAccessibilityId('Splitting Touches across Views')->click();
        } catch (Exception $e) {
            $els = $this->elements($this->using('class name')->value('android.widget.TextView'));
            $this->scroll($els[count($els) - 1], $els[0]);
            $this->byAccessibilityId('Splitting Touches across Views')->click();
        }

        $els = $this->elements($this->using('class name')->value('android.widget.ListView'));
        $action1 = $this->initiateTouchAction();
        $action1->press(array('element' => $els[0]))
                ->moveTo(array('x' => 10, 'y' => 0))
                ->moveTo(array('x' => 10, 'y' => -75))
                ->wait(1000)
                ->moveTo(array('x' => 10, 'y' => -600))
                ->release();

        $action2 = $this->initiateTouchAction();
        $action2->press(array('element' => $els[1]))
                ->moveTo(array('x' => 10, 'y' => 10))
                ->moveTo(array('x' => 10, 'y' => -300))
                ->wait(500)
                ->moveTo(array('x' => 10, 'y' => -600))
                ->release();

        $multiAction = $this->initiateMultiAction();
        $multiAction->add($action1);
        $multiAction->add($action2);
        $multiAction->perform();
    }

    public function testDriverMultiTap()
    {
        $this->byAccessibilityId('Graphics')->click();

        $els = $this->elements($this->using('class name')->value('android.widget.TextView'));
        $this->scroll($els[count($els) - 1], $els[0]);

        $els = $this->elements($this->using('class name')->value('android.widget.TextView'));
        if (end($els)->attribute('name') != 'Xfermodes') {
            $this->scroll($els[count($els) - 1], $els[0]);
        }

        $this->byAccessibilityId('Touch Paint')->click();

        $this->tap(4, 100, 200);
    }

    public function testDriverMultiTapElement()
    {
        $el = $this->byAccessibilityId('Graphics');
        $this->tap(2, $el);

        $els = $this->elements($this->using('class name')->value('android.widget.TextView'));
        $this->assertEquals('API Demos', $els[0]->attribute('name'));
    }

    public function testDriverPinchZoom()
    {
        $els = $this->elements($this->using('class name')->value('android.widget.TextView'));

        $this->scroll($els[count($els) - 1], $els[0]);

        $this->byAccessibilityId('Views')->click();

        $els = $this->elements($this->using('class name')->value('android.widget.TextView'));
        $this->scroll($els[count($els) - 1], $els[0]);

        $els = $this->elements($this->using('class name')->value('android.widget.TextView'));
        if (end($els)->attribute('name') != 'WebView') {
            $this->scroll($els[count($els) - 1], $els[0]);
        }

        $this->byAccessibilityId('WebView')->click();

        sleep(SLEEPY_TIME);
        $el = $this->byId('com.example.android.apis:id/wv1');

        $this->zoom($el);
        sleep(SLEEPY_TIME);
        $this->pinch($el);
    }
}

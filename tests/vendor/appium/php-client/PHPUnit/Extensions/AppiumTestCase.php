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

require_once('PHPUnit/Extensions/AppiumTestCase/SessionStrategy/Isolated.php');
require_once('PHPUnit/Extensions/AppiumTestCase/Element.php');
require_once('PHPUnit/Extensions/AppiumTestCase/MultiAction.php');
require_once('PHPUnit/Extensions/AppiumTestCase/TouchAction.php');


abstract class PHPUnit_Extensions_AppiumTestCase extends PHPUnit_Extensions_Selenium2TestCase
{
    /**
     * @var array
     */
    private static $lastBrowserParams;

    /**
     * @var array
     */
    private $parameters;

    protected $session;

    public function __construct($name = NULL, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        // Make sure we are using the Appium session
        self::setUpSessionStrategy(array("sessionStrategy" => "isolated"));

        // Appium doesn't use the browser per se, but the system fails
        // if it is not set
        self::setBrowser("");
        self::setBrowserUrl("");
    }

    private static function defaultSessionStrategy()
    {
        return new PHPUnit_Extensions_AppiumTestCase_SessionStrategy_Isolated;
    }

    /**
     * @param boolean
     */
    public static function shareSession($shareSession)
    {
        if (!is_bool($shareSession)) {
            throw new InvalidArgumentException("The shared session support can only be switched on or off.");
        }
        if (!$shareSession) {
            self::$sessionStrategy = self::defaultSessionStrategy();
        } else {
            self::$sessionStrategy = new PHPUnit_Extensions_AppiumTestCase_SessionStrategy_Shared(self::defaultSessionStrategy());
        }
    }


    // We want to inject an Appium session into the PHPUnit-Selenium logic.
    protected function setUpSessionStrategy($params)
    {
        // This logic enables us to have a session strategy reused for each
        // item in self::$browsers. We don't want them both to share one
        // and we don't want each test for a specific browser to have a
        // new strategy
        if ($params == self::$lastBrowserParams) {
            // do nothing so we use the same session strategy for this
            // browser
        } elseif (isset($params['sessionStrategy'])) {
            $strat = $params['sessionStrategy'];
            if ($strat != "isolated" && $strat != "shared") {
                throw new InvalidArgumentException("Session strategy must be either 'isolated' or 'shared'");
            } elseif ($strat == "isolated") {
                self::$browserSessionStrategy = new PHPUnit_Extensions_AppiumTestCase_SessionStrategy_Isolated;
            } else {
                self::$browserSessionStrategy = new PHPUnit_Extensions_AppiumTestCase_SessionStrategy_Shared(self::defaultSessionStrategy());
            }
        } else {
            self::$browserSessionStrategy = self::defaultSessionStrategy();
        }
        self::$lastBrowserParams = $params;
        $this->localSessionStrategy = self::$browserSessionStrategy;
    }

    /**
     * @param string $value     e.g. '.elements()[0]'
     * @return PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function byIOSUIAutomation($value)
    {
        return $this->by('-ios uiautomation', $value);
    }

    /**
     * @param string $value     e.g. 'new UiSelector().description("Animation")'
     * @return PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function byAndroidUIAutomator($value)
    {
        return $this->by('-android uiautomator', $value);
    }

    /**
     * @param string $value     e.g. 'Animation'
     * @return PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function byAccessibilityId($value)
    {
        return $this->by('accessibility id', $value);
    }

    public function pullFile($path)
    {
        $session = $this->prepareSession();
        $data = array(
            'path' => $path
        );
        $url = $this->getSessionUrl()->descend('appium')->descend('device')->descend('pull_file');
        $response = $session->getDriver()->curl('POST', $url, $data);
        return $response->getValue();
    }

    public function pushFile($path, $base64Data)
    {
        $session = $this->prepareSession();
        $data = array(
            'path' => $path,
            'data' => $base64Data
        );
        $url = $this->getSessionUrl()->descend('appium')->descend('device')->descend('push_file');
        $session->getDriver()->curl('POST', $url, $data);
    }

    public function pullFolder($path)
    {
        $session = $this->prepareSession();
        $data = array(
            'path' => $path
        );
        $url = $this->getSessionUrl()->descend('appium')->descend('device')->descend('pull_folder');
        $response = $session->getDriver()->curl('POST', $url, $data);
        return $response->getValue();
    }

    public function backgroundApp($seconds)
    {
        $session = $this->prepareSession();
        $data = array(
            'seconds' => $seconds
        );
        $url = $this->getSessionUrl()->descend('appium')->descend('app')->descend('background');
        $session->getDriver()->curl('POST', $url, $data);
    }

    public function isAppInstalled($bundleId)
    {
        // /appium/device/app_installed
        $session = $this->prepareSession();
        $data = array(
            'bundleId' => $bundleId
        );
        $url = $this->getSessionUrl()->descend('appium')->descend('device')->descend('app_installed');
        $response = $session->getDriver()->curl('POST', $url, $data);
        return $response->getValue();
    }

    public function installApp($path)
    {
        $session = $this->prepareSession();
        $data = array(
            'appPath' => $path
        );
        $url = $this->getSessionUrl()->descend('appium')->descend('device')->descend('install_app');
        $session->getDriver()->curl('POST', $url, $data);
    }

    public function removeApp($appId)
    {
        // /appium/device/remove_app
        $session = $this->prepareSession();
        $data = array(
            'appId' => $appId
        );
        $url = $this->getSessionUrl()->descend('appium')->descend('device')->descend('remove_app');
        $session->getDriver()->curl('POST', $url, $data);
    }

    public function launchApp()
    {
        // /appium/app/launch
        $session = $this->prepareSession();
        $url = $this->getSessionUrl()->descend('appium')->descend('app')->descend('launch');
        $session->getDriver()->curl('POST', $url, null);
    }

    public function closeApp()
    {
        $session = $this->prepareSession();
        $url = $this->getSessionUrl()->descend('appium')->descend('app')->descend('close');
        $session->getDriver()->curl('POST', $url, null);
    }

    /**
     * @param array $options     'appPackage' and 'appActivity' are required;
     *                           'appWaitPackage' and 'appWaitActivity' are optional
     * @return void
     */
    public function startActivity($options)
    {
        $session = $this->prepareSession();
        $url = $this->getSessionUrl()->descend('appium')->descend('device')->descend('start_activity');
        $session->getDriver()->curl('POST', $url, $options);
    }

    public function endTestCoverage($intent, $path)
    {
        $session = $this->prepareSession();
        $data = array(
            'intent' => $intent,
            'path' => $path
        );
        $url = $this->getSessionUrl()->descend('appium')->descend('app')->descend('end_test_coverage');
        $response = $session->getDriver()->curl('POST', $url, $data);
        return $response->getValue();
    }

    public function lock($seconds)
    {
        $session = $this->prepareSession();
        $data = array(
            'seconds' => $seconds
        );
        $url = $this->getSessionUrl()->descend('appium')->descend('device')->descend('lock');
        $session->getDriver()->curl('POST', $url, $data);
    }

    public function shake()
    {
        $session = $this->prepareSession();
        $url = $this->getSessionUrl()->descend('appium')->descend('device')->descend('shake');
        $session->getDriver()->curl('POST', $url, null);
    }

    public function hideKeyboard($args=array('strategy' => 'tapOutside'))
    {
        $data = array();
        if (array_key_exists('keyName', $args)) {
            $data['keyName'] = $args['keyName'];
        } elseif (array_key_exists('key', $args)) {
            $data['key'] = $args['key'];
        }
        if (array_key_exists('strategy', $args)) {
            $data['strategy'] = $args['strategy'];
        }
        $session = $this->prepareSession();
        $url = $this->getSessionUrl()->descend('appium')->descend('device')->descend('hide_keyboard');
        $session->getDriver()->curl('POST', $url, $data);
    }

    public function openNotifications()
    {
        $session = $this->prepareSession();
        $url = $this->getSessionUrl()->descend('appium')->descend('device')->descend('open_notifications');
        $session->getDriver()->curl('POST', $url, array());
    }

    public function initiateTouchAction()
    {
        $session = $this->prepareSession();
        return new PHPUnit_Extensions_AppiumTestCase_TouchAction($session->getSessionUrl(), $session->getDriver());
    }

    public function scroll($originElement, $destinationElement)
    {
        $action = $this->initiateTouchAction();
        $action->press(array('element' => $originElement))
               ->moveTo(array('element' => $destinationElement))
               ->release()
               ->perform();
        return $this;
    }

    public function dragAndDrop($originElement, $destinationElement)
    {
        $action = $this->initiateTouchAction();
        $action->longPress(array('element' => $originElement))
               ->moveTo(array('element' => $destinationElement))
               ->release()
               ->perform();
        return $this;
    }

    public function swipe($startX, $startY, $endX, $endY, $duration=800)
    {
        $action = $this->initiateTouchAction();
        $action->press(array('x' => $startX, 'y' => $startY))
               ->wait($duration)
               ->moveTo(array('x' => $endX, 'y' => $endY))
               ->release()
               ->perform();
        return $this;
    }

    public function initiateMultiAction()
    {
        $session = $this->prepareSession();
        return new PHPUnit_Extensions_AppiumTestCase_MultiAction($session->getSessionUrl(), $session->getDriver());
    }

    public function tap($fingers, $x, $y=NULL, $duration=0)
    {
        $multiAction = $this->initiateMultiAction();

        // php doesn't support overloading, so we need to do some twiddling
        if (gettype($x) != 'integer') {
            $element = $x;
            if (!is_null($y)) {
                echo "setting duration to";
                $duration = $y;
            }

            for ($i = 0; $i < $fingers; $i++) {
                $action = $this->initiateTouchAction();
                $action->press(array('element' => $element))
                       ->wait($duration)
                       ->release();
                $multiAction->add($action);
            }
        } else {
            for ($i = 0; $i < $fingers; $i++) {
                $action = $this->initiateTouchAction();
                $action->press(array('x' => $x, 'y' => $y))
                       ->wait($duration)
                       ->release();
                $multiAction->add($action);
            }
        }

        $multiAction->perform();
    }

    public function pinch(PHPUnit_Extensions_AppiumTestCase_Element $element)
    {
        $center = $this->elementCenter($element);

        $centerX = $center['x'];
        $centerY = $center['y'];

        $a1 = $this->initiateTouchAction();
        $a1->press(array('x' => $centerX, 'y' => $centerY - 100))
           ->moveTo(array('x' => $centerX, 'y' => $centerY))
           ->release();

        $a2 = $this->initiateTouchAction();
        $a2->press(array('x' => $centerX, 'y' => $centerY + 100))
           ->moveTo(array('x' => $centerX, 'y' => $centerY))
           ->release();

        $ma = $this->initiateMultiAction();
        $ma->add($a1);
        $ma->add($a2);
        $ma->perform();
    }

    public function zoom(PHPUnit_Extensions_AppiumTestCase_Element $element)
    {
        $center = $this->elementCenter($element);

        $centerX = $center['x'];
        $centerY = $center['y'];

        $a1 = $this->initiateTouchAction();
        $a1->press(array('x' => $centerX, 'y' => $centerY))
           ->moveTo(array('x' => $centerX, 'y' => $centerY - 100))
           ->release();

        $a2 = $this->initiateTouchAction();
        $a2->press(array('x' => $centerX, 'y' => $centerY))
           ->moveTo(array('x' => $centerX, 'y' => $centerY + 100))
           ->release();

        $ma = $this->initiateMultiAction();
        $ma->add($a1);
        $ma->add($a2);
        $ma->perform();
    }

    // Get session Settings
    public function getSettings()
    {
      // /appium/settings
      $session = $this->prepareSession();
      $url = $this->getSessionUrl()->descend('appium')->descend('settings');
      $response = $session->getDriver()->curl('GET', $url);
      return $response->getValue();
    }

    // Set session Settings
    public function updateSettings($settings)
    {
      // /appium/settings
      $session = $this->prepareSession();
      $data = array(
          'settings' => $settings
      );
      $url = $this->getSessionUrl()->descend('appium')->descend('settings');
      $session->getDriver()->curl('POST', $url, $data);
    }

    // stolen from PHPUnit_Extensions_Selenium2TestCase_Element_Accessor
    // where it is mysteriously private, and therefore unusable
    public function by($strategy, $value)
    {
        $el = $this->element($this->using($strategy)->value($value));
        return $el;
    }

    public function element(PHPUnit_Extensions_Selenium2TestCase_ElementCriteria $criteria)
    {
        $session = $this->prepareSession();
        $value = $session->postCommand('element', $criteria);
        return PHPUnit_Extensions_AppiumTestCase_Element::fromResponseValue(
                $value, $session->getSessionUrl()->descend('element'), $session->getDriver());
    }

    public function elements(PHPUnit_Extensions_Selenium2TestCase_ElementCriteria $criteria)
    {
        $session = $this->prepareSession();
        $values = $session->postCommand('elements', $criteria);
        $elements = array();
        foreach ($values as $value) {
            $elements[] =
                PHPUnit_Extensions_AppiumTestCase_Element::fromResponseValue(
                    $value, $session->getSessionUrl()->descend('element'), $session->getDriver());
        }
        return $elements;
    }

    /**
     * @param string $value     e.g. 'container'
     * @return PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function byClassName($value)
    {
        return $this->by('class name', $value);
    }

    /**
     * @param string $value     e.g. 'div.container'
     * @return PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function byCssSelector($value)
    {
        return $this->by('css selector', $value);
    }

    /**
     * @param string $value     e.g. 'uniqueId'
     * @return PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function byId($value)
    {
        return $this->by('id', $value);
    }

    /**
     * @param string $value     e.g. 'Link text'
     * @return PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function byLinkText($value)
    {
        return $this->by('link text', $value);
    }

    /**
     * @param string $value     e.g. 'Link te'
     * @return PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function byPartialLinkText($value)
    {
        return $this->by('partial link text', $value);
    }

    /**
     * @param string $value     e.g. 'email_address'
     * @return PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function byName($value)
    {
        return $this->by('name', $value);
    }

    /**
     * @param string $value     e.g. 'body'
     * @return PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function byTag($value)
    {
        return $this->by('tag name', $value);
    }

    /**
     * @param string $value     e.g. '/div[@attribute="value"]'
     * @return PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function byXPath($value)
    {
        return $this->by('xpath', $value);
    }

    protected function elementCenter(PHPUnit_Extensions_AppiumTestCase_Element $element)
    {
        $size = $element->size();
        $location = $element->location();

        $centerX = $location['x'] + $size['width'] / 2;
        $centerY = $location['y'] + $size['height'] / 2;

        return array('x' => $centerX, 'y' => $centerY);
    }
}

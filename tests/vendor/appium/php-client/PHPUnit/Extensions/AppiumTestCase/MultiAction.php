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

require_once('PHPUnit/Extensions/AppiumTestCase/Driver.php');
require_once('PHPUnit/Extensions/AppiumTestCase/TouchAction.php');


class PHPUnit_Extensions_AppiumTestCase_MultiAction
{
    private $sessionUrl;
    private $driver;
    private $element;
    private $actions;


    public function __construct(PHPUnit_Extensions_Selenium2TestCase_URL $sessionUrl,
                                PHPUnit_Extensions_AppiumTestCase_Driver $driver,
                                PHPUnit_Extensions_AppiumTestCase_Element $element=NULL)
    {
        $this->sessionUrl = $sessionUrl;
        $this->driver = $driver;
        $this->element = $element;
        $this->actions = array();
    }

    public function add(PHPUnit_Extensions_AppiumTestCase_TouchAction $action)
    {
        if (is_null($this->actions)) {
            $this->actions = array();
        }

        $this->actions[] = $action;
    }

    public function perform()
    {
        $params = $this->getJSONWireGestures();

        $url = $this->sessionUrl->descend('touch')->descend('multi')->descend('perform');
        $this->driver->curl('POST', $url, $params);
    }

    public function getJSONWireGestures()
    {
        $actions = array();
        foreach ($this->actions as $action) {
            $actions[] = $action->getJSONWireGestures();
        }

        $gestures = array(
            'actions' => $actions
        );
        if (!is_null($this->element)) {
            $gestures['elementId'] = $this->element->getId();
        }

        return $gestures;
    }
}

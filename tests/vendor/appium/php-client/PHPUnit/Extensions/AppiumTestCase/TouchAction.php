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


class PHPUnit_Extensions_AppiumTestCase_TouchAction
{
    private $sessionUrl;
    private $driver;
    private $actions;


    public function __construct(PHPUnit_Extensions_Selenium2TestCase_URL $sessionUrl,
            PHPUnit_Extensions_AppiumTestCase_Driver $driver)
    {
        $this->sessionUrl = $sessionUrl;
        $this->driver = $driver;
        $this->actions = array();
    }

    public function tap($params)
    {
        $options = $this->getOptions($params);

        if (array_key_exists('count', $params)) {
            $options['count'] = $params['count'];
        } else {
            $options['count'] = 1;
        }

        $this->addAction('tap', $options);
        return $this;
    }

    public function press($params)
    {
        $options = $this->getOptions($params);

        $this->addAction('press', $options);
        return $this;
    }

    public function longPress($params)
    {
        $options = $this->getOptions($params);

        if (array_key_exists('duration', $params)) {
            $options['duration'] = $params['duration'];
        } else {
            $options['duration'] = 800;
        }

        $this->addAction('longPress', $options);
        return $this;
    }

    public function moveTo($params)
    {
        $options = $this->getOptions($params);

        $this->addAction('moveTo', $options);
        return $this;
    }

    public function wait($params)
    {
        $options = array();

        if (gettype($params) == 'array') {
            if (array_key_exists('ms', $params)) {
                $options['ms'] = $params['ms'];
            } else {
                $options['ms'] = 0;
            }
        } else {
            $options['ms'] = $params;
        }

        $this->addAction('wait', $options);
        return $this;
    }

    public function release()
    {
        $this->addAction('release', array());
        return $this;
    }

    public function perform()
    {
        $params = array(
            'actions' => $this->actions
        );
        $url = $this->sessionUrl->descend('touch')->descend('perform');
        $this->driver->curl('POST', $url, $params);
    }

    public function getJSONWireGestures()
    {
        $actions = array();
        foreach ($this->actions as $action) {
            $actions[] = $action;
        }
        return $actions;
    }


    protected function getOptions($params) {
        $opts = array();

        if (array_key_exists('element', $params) && $params['element'] != NULL) {
            $opts['element'] = $params['element']->getId();
        }

        # it makes no sense to have x but no y, or vice versa.
        if (array_key_exists('x', $params) && array_key_exists('y', $params)) {
            $opts['x'] = $params['x'];
            $opts['y'] = $params['y'];
        }

        return $opts;
    }

    protected function addAction($action, $options)
    {
        $gesture = array(
            'action' => $action,
            'options' => $options
        );

        $this->actions[] = $gesture;
    }
}

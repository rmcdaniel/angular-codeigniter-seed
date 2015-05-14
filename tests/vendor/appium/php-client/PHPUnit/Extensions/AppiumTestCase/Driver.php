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

require_once('PHPUnit/Extensions/AppiumTestCase/Session.php');

class PHPUnit_Extensions_AppiumTestCase_Driver
    extends PHPUnit_Extensions_Selenium2TestCase_Driver
{
    private $seleniumServerUrl;
    private $seleniumServerRequestsTimeout;

    public function __construct(PHPUnit_Extensions_Selenium2TestCase_URL $seleniumServerUrl, $timeout = 60)
    {
        parent::__construct($seleniumServerUrl, $timeout);

        $this->seleniumServerUrl = $seleniumServerUrl;
        $this->seleniumServerRequestsTimeout = $timeout;
    }

    public function startSession(array $desiredCapabilities,
                                 PHPUnit_Extensions_Selenium2TestCase_URL $browserUrl)
    {
        $sessionCreation = $this->seleniumServerUrl->descend("/wd/hub/session");
        $response = $this->curl('POST', $sessionCreation, array(
            'desiredCapabilities' => $desiredCapabilities
        ));
        $sessionPrefix = $response->getURL();

        $timeouts = new PHPUnit_Extensions_Selenium2TestCase_Session_Timeouts(
            $this,
            $sessionPrefix->descend('timeouts'),
            $this->seleniumServerRequestsTimeout * 1000
        );
        return new PHPUnit_Extensions_AppiumTestCase_Session(
            $this,
            $sessionPrefix,
            $browserUrl,
            $timeouts
        );
    }

    public function getServerUrl()
    {
        return $this->seleniumServerUrl;
    }
}

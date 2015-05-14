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

class PHPUnit_Extensions_AppiumTestCase_SessionStrategy_Isolated
    implements PHPUnit_Extensions_Selenium2TestCase_SessionStrategy
{
    public function session(array $parameters)
    {
        $seleniumServerUrl = PHPUnit_Extensions_Selenium2TestCase_URL::fromHostAndPort($parameters['host'], $parameters['port']);
        $driver = new PHPUnit_Extensions_AppiumTestCase_Driver($seleniumServerUrl, $parameters['seleniumServerRequestsTimeout']);
        $capabilities = array_merge($parameters['desiredCapabilities'],
                                    array(
                                        'browserName' => $parameters['browserName']
                                    ));
        $session = $driver->startSession($capabilities, $parameters['browserUrl']);
        return $session;
    }

    public function notSuccessfulTest()
    {
    }

    public function endOfTest(PHPUnit_Extensions_Selenium2TestCase_Session $session = NULL)
    {
        if ($session !== NULL) {
            $session->stop();
        }
    }
}

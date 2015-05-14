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

 class PHPUnit_Extensions_AppiumTestCase_SessionCommand_Context
    extends PHPUnit_Extensions_Selenium2TestCase_Command
{
    public function __construct($name, $commandUrl)
    {
        if (is_string($name)) {
            $jsonParameters = array('name' => $name);
        } else if ($name == NULL) {
            $jsonParameters = NULL;
        } else {
            throw new BadMethodCallException("Wrong Parameters for context().");
        }

        parent::__construct($jsonParameters, $commandUrl);
    }

    public function httpMethod()
    {
        if ($this->jsonParameters) {
            return 'POST';
        }
        return 'GET';
    }
}

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

class PHPUnit_Extensions_AppiumTestCase_Element
    extends PHPUnit_Extensions_Selenium2TestCase_Element
{
    /**
     * @return \self
     * @throws InvalidArgumentException
     */
    public static function fromResponseValue(
            array $value,
            PHPUnit_Extensions_Selenium2TestCase_URL $parentFolder,
            PHPUnit_Extensions_Selenium2TestCase_Driver $driver)
    {
        if (!isset($value['ELEMENT'])) {
            throw new InvalidArgumentException('Element not found.');
        }
        $url = $parentFolder->descend($value['ELEMENT']);
        return new self($driver, $url);
    }

    public function byIOSUIAutomation($value)
    {
        return $this->by('-ios uiautomation', $value);
    }

    public function byAndroidUIAutomator($value)
    {
        return $this->by('-android uiautomator', $value);
    }

    public function byAccessibilityId($value)
    {
        return $this->by('accessibility id', $value);
    }

    public function setImmediateValue($value)
    {
        $data = array(
            'elementId' => $this->getId(),
            'value' => $value
        );
        $url = $this->getSessionUrl()->descend('appium')->descend('element')->descend($this->getId())->descend('value');
        $this->driver->curl('POST', $url, $data);
    }

    public function setText($keys)
    {
        $data = array(
            'elementId' => $this->getId(),
            'value' => array($keys)
        );
        $url = $this->getSessionUrl()->descend('appium')->descend('element')->descend($this->getId())->descend('replace_value');
        $this->driver->curl('POST', $url, $data);
    }

    public function by($strategy, $value)
    {
        $el = $this->element($this->using($strategy)->value($value));
        return $el;
    }

    // override to return Appium element
    public function element(PHPUnit_Extensions_Selenium2TestCase_ElementCriteria $criteria)
    {
        $value = $this->postCommand('element', $criteria);
        return PHPUnit_Extensions_AppiumTestCase_Element::fromResponseValue(
                $value, $this->getSessionUrl()->descend('element'), $this->driver);
    }

    public function elements(PHPUnit_Extensions_Selenium2TestCase_ElementCriteria $criteria)
    {
        $session = $this->prepareSession();
        $values = $session->postCommand('elements', $criteria);
        $elements = array();
        foreach ($values as $value) {
            $elements[] =
                PHPUnit_Extensions_AppiumTestCase_Element::fromResponseValue(
                    $value, $session->getSessionUrl()->descend('element'), $session->driver);
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
}

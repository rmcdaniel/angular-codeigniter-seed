# Appium PHP Client


An extension library to add Selenium 3 features to Appium.

The library is installable using the [Composer](https://getcomposer.org/) dependency manager. Just add `"appium/appium-php": "dev-master"` (or any other branch/tag you might like) to your `composer.json` file's `require`s, and the [repository on GitHub](https://github.com/appium/php-client) to the `repositories`:

```json
{
    "name": "username/my-php-project",
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/appium/php-client"
        }
    ],
    "require": {
        "appium/php-client": "dev-master"
    }
}
```

Then install the dependencies and run your tests:

```shell
composer install
vendor/phpunit/phpunit/phpunit <mytest.php>
```

## Usage and changes

There are a number of methods added to [Selenium 3/Appium 1](https://github.com/appium/appium/blob/master/docs/en/migrating-to-1-0.md). The central change is in the test case that serves as the base of your tests, and the elements with which you interact. Both are subclasses of the [PHPUnit Selenium](https://github.com/sebastianbergmann/phpunit-selenium/) classes. Your tests should be subclasses of [PHPUnit_Extensions_AppiumTestCase](https://github.com/appium/php-client/blob/master/PHPUnit/Extensions/AppiumTestCase.php), and all elements that get returned will be of the class [PHPUnit_Extensions_AppiumTestCase_Element](https://github.com/appium/php-client/blob/master/PHPUnit/Extensions/AppiumTestCase/Element.php).


```php
require_once('PHPUnit/Extensions/AppiumTestCase.php');
require_once('PHPUnit/Extensions/AppiumTestCase/Element.php');

class MySuperTests extends PHPUnit_Extensions_AppiumTestCase
{
    public static $browsers = array(
        array(
            'local' => true,
            'port' => 4723,
            'browserName' => '',
            'desiredCapabilities' => array(
                'app' => APP_PATH
            )
        )
    );

    public function testStuff()
    {
        $element = $this->byAccessibilityId('Element on screen');

        $this->assertInstanceOf('PHPUnit_Extensions_AppiumTestCase_Element', $element);
    }
}
```


## Methods added

### Methods in `PHPUnit_Extensions_AppiumTestCase`

* `byIOSUIAutomation`
* `byAndroidUIAutomator`
* `byAccessibilityId`
* `keyEvent`
* `pullFile`
* `pushFile`
* `backgroundApp`
* `isAppInstalled`
* `installApp`
* `removeApp`
* `launchApp`
* `closeApp`
* `endTestCoverage`
* `lock`
* `shake`
* `hideKeyboard`
* `initiateTouchAction`
* `initiateMultiAction`
* `scroll`
* `dragAndDrop`
* `swipe`
* `tap`
* `pinch`
* `zoom`
* `startActivity`
* `getSettings`
* `setSettings`

### Methods in `PHPUnit_Extensions_AppiumTestCase_Element`

* `byIOSUIAutomation`
* `byAndroidUIAutomator`
* `byAccessibilityId`
* `setImmediateValue`

### Methods for Touch Actions and Multi Gesture Touch Actions

Appium 1.0 allows for much more complex ways of interacting with your app through Touch Actions and Multi Gesture Touch Actions. The `PHPUnit_Extensions_AppiumTestCase_TouchAction` class allows for the following events:

* `tap`
* `press`
* `longPress`
* `moveTo`
* `wait`
* `release`

All of these except `tap` and `release` can be chained together to create arbitrarily complex actions. Instances of the `PHPUnit_Extensions_AppiumTestCase_TouchAction` class are obtained through the Test Class's `initiateTouchAction` method, and dispatched through the `perform` method.

The Multi Gesture Touch Action API allows for adding an arbitrary number of Touch Actions to be run in parallel on the device. Individual actions created as above are added to the multi action object (an instance of `PHPUnit_Extensions_AppiumTestCase_MultiAction` obtained from the Test Class's `initiateMultiAction` method) through the `add` method, and the whole thing is dispatched using `perform`.

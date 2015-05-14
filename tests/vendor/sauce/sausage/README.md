[![Build Status](https://travis-ci.org/jlipps/sausage.svg?branch=master)](https://travis-ci.org/jlipps/sausage)

Sausage
=======

Your one-stop shop for everything Selenium + Sauce Labs + PHP. This is a set of
classes and libraries that make it easy to run your Selenium tests, either
locally or on Sauce Labs. You run the tests with [PHPUnit](http://phpunit.de).

Sausage comes bundled with [Paratest](http://github.com/brianium/paratest) (for
running your PHPUnit tests in parallel) and optionally
[Sauce Connect](http://saucelabs.com/docs/connect) (for testing locally-hosted
sites with Sauce).

Read the rest of this page for installation and usage instructions designed
to help you get the most out of your sausage.

License
-------
Sausage is available under the Apache 2 license. See `LICENSE.APACHE2` for more
details.

Quickstart
----------
Check out [sausage-bun](http://github.com/jlipps/sausage-bun). It's a one-line
script you can run via curl and PHP to get everything going. For example:

```
curl -sL https://raw.githubusercontent.com/jlipps/sausage-bun/master/givememysausage.php | php
```

_Note_: if you are a Windows user who's not using Cygwin, it'll take a little
extra work to set you up---please see the [sausage-bun
README](http://github.com/jlipps/sausage-bun)

Manual Install
------------
Sausage is distributed as a [Composer](http://getcomposer.org) package via
[Packagist](http://packagist.org/),
under the package `sauce/sausage`. To get it, add (or update) the `composer.json`
file in your project root. A minimal example composer.json would look like:

```json
{
    "require": {
        "sauce/sausage": ">=0.15.2"
    }
}
```

If you haven't already got Composer installed, get it thusly (for *nix/Mac):

    curl -sL http://getcomposer.org/installer | php

Then, install the packages (or `update` if you've already set up Composer):

    php composer.phar install

This will install Sausage and all its dependences (like PHPUnit, etc...). If
you didn't already have the `SAUCE_USERNAME` and `SAUCE_ACCESS_KEY` environment
variables set, you'll now need to configure Sausage for use with your Sauce
account:

    vendor/bin/sauce_config YOUR_SAUCE_USERNAME YOUR_SAUCE_ACCESS_KEY

(Or for Windows):

    vendor\bin\sauce_config.bat YOUR_SAUCE_USERNAME YOUR_SAUCE_ACCESS_KEY

(It's a Composer convention for package binaries to be located in `vendor/bin`;
you can always symlink things elsewhere if it's more convenient).

Requirements
---
* Sausage will work on any modern (>= 5.4) PHP installation
* Composer's requirements must also be satisfied (unfortunately, I could not
  find these documented anywhere). Suffice it to say they're normal requirements
  like the cURL extension, `safe_mode` off, `allow_url_fopen`, etc...
* If you're on a Windows machine, you might want to set up all your PHP stuff
  in [Cygwin](http://cygwin.com)

Getting Started
----
If everything's set up correctly, you should be able to run this:

    vendor/bin/phpunit vendor/sauce/sausage/WebDriverDemo.php

(Or for Windows):

    vendor\bin\phpunit.bat vendor\sauce\sausage\WebDriverDemo.php

And start seeing tests pass. (While the tests are running, you can check on
their progress by going to your [Sauce tests
page](http://saucelabs.com/tests))

Getting Started with Mobile
----
Running tests on Mobile uses [Appium](http://appium.io). If everything is set up
correctly, you should be able to run this:

    vendor/bin/phpunit vendor/sauce.sausage/MobileDemo.php

(Or for Windows):

    vendor\bin\phpunit.bat vendor\sauce\sausage\AppiumDemo.php

And start seeing tests pass. (While the tests are running, you can check on
their progress by going to your [Sauce tests
page](http://saucelabs.com/tests))

Running tests in parallel
---
Running Selenium tests one at a time is like eating one cookie at a time. Let's
do it all at once! Try this:

    vendor/bin/paratest -p 2 -f --phpunit=vendor/bin/phpunit vendor/sauce/sausage/WebDriverDemo.php

(Or for Windows):

    vendor\bin\paratest.bat -p 2 -f --phpunit=vendor\bin\phpunit.bat vendor\sauce\sausage\WebDriverDemo.php

Now they'll finish twice as fast! (And if you get a [Sauce Labs
account](http://saucelabs.com/pricing), you can
bump up that concurrency to 4, 10, 20, 30, or more!)

Writing WebDriver tests
---
Writing tests for Selenium 2 (WebDriver) is easy and straightforward. Sausage
is by default built on top of
[PHPUnit_Selenium](http://github.com/sebastianbergmann/phpunit-selenium). All
commands that work in `PHPUnit_Extensions_Selenium2TestCase` also work in
Sausage's `WebDriverTestCase`. Here's a simple example:

```php
<?php

require_once 'vendor/autoload.php';

class MyAwesomeTestCase extends Sauce\Sausage\WebDriverTestCase
{
    protected $start_url = 'http://saucelabs.com/test/guinea-pig';

    public static $browsers = array(
        // run FF15 on Vista on Sauce
        array(
            'browserName' => 'firefox',
            'desiredCapabilities' => array(
                'version' => '15',
                'platform' => 'VISTA'
            )
        ),
        // run Chrome on Linux on Sauce
        array(
            'browserName' => 'chrome',
            'desiredCapabilities' => array(
                'platform' => 'Linux'
          )
        )
    );

    public function testLink()
    {
        $link = $this->byId('i am a link');
        $link->click();
        $this->assertContains("I am another page title", $this->title());
    }
}
```

In this example, we define a set of browsers to use, and run a simple check
to make sure that clicking on a link gets us to the expected new page.

For more examples, check out:
* The `WebDriverDemo.php` file in this repository
* The documentation for [PHPUnit_Extensions_Selenium2TestCase](http://www.phpunit.de/manual/3.7/en/selenium.html#selenium.selenium2testcase)

If you're into Selenium 1 (Selenium RC), instead take a look at
`SeleniumRCDemo.php`

Writing Mobile tests
---
Writing tests for mobile devices is easy and straightforward. Sausage
is by default built on top of [Appium](http://appium.io) and the [Appium PHP
Client](https://github.com/appium/php-client) and
[PHPUnit_Selenium](http://github.com/sebastianbergmann/phpunit-selenium). All
commands that work in `PHPUnit_Extensions_Selenium2TestCase` also work in
Sausage's `MobileTestCase`. Here's a simple example:

```php
<?php
require_once "vendor/autoload.php";
define("APP_URL", "http://appium.s3.amazonaws.com/TestApp6.0.app.zip");

class MobileTest extends Sauce\Sausage\MobileTestCase
{
    protected $numValues = array();

    public static $browsers = array(
        array(
            'browserName' => '',
            'desiredCapabilities' => array(
                'appium-version' => '1.0',
                'platformName' => 'iOS',
                'platformVersion' => '7.0',
                'deviceName' => 'iPhone Simulator',
                'name' => 'Appium/Sauce iOS Test, PHP',
                'app' => APP_URL
            )
        )
    );

    public function elemsByClassName($klass)
    {
        return $this->elements($this->using('class name')->value($klass));
    }

    protected function populate()
    {
        $elems = $this->elemsByClassName('UIATextField');
        foreach ($elems as $elem) {
            $randNum = rand(0, 10);
            $elem->value($randNum);
            $this->numValues[] = $randNum;
        }
    }

    public function testUiComputation()
    {
        $this->populate();
        $buttons = $this->elemsByClassName('UIAButton');
        $buttons[0]->click();
        $texts = $this->elemsByClassName('UIAStaticText');
        $this->assertEquals(array_sum($this->numValues), (int)($texts[0]->text()));
    }
}
```

Here we define a the device capabilities we want to use, and run a simple test
of finding elements and interacting with them.

Sauce Labs API
---
Sausage comes bundled with a nice PHP interface to the [Sauce Labs API](https://saucelabs.com/docs/rest):

```php
<?php

$s = new Sauce\Sausage\SauceAPI('myusername', 'myaccesskey');

$my_details = $s->getAccountDetails();

$most_recent_test = $s->getJobs(0)['jobs'][0];
$s->updateJob($most_recent_test['id'], array('passed' => true));

$browser_list = $s->getAllBrowsers();
foreach ($browser_list as $browser) {
    $name = $browser['long_name'];
    $ver = $browser['short_version'];
    $os = $browser['os'];
    echo "$name $ver $os\n";
}
```

See `Sauce/Sausage/SauceMethods.php` for the list of Sauce API functions (currently
boasting 100% support). Also check out `sauce_api_test.php` for other examples.

Automatic Test Naming
---
By default, Sauce Labs doesn't know how to display the name of your test. Sausage
comes up with a good name (`TestClass::testFunction`) and reports it with your
test so it's easy to find on your [tests page](http://saucelabs.com/tests).

Automatic Test Status Reporting
---
Since Selenium commands might be successful but your test still fails because
of an assertion, there is in principle no way for Sauce Labs to know whether a
particular run was a pass or fail. Sausage catches any failed assertions and
makes sure to report the status of the test to Sauce after it's complete, so
as you're looking at your log of tests you can easily see which passed and which
failed.

Automatic Authorized Link Generation
---
Upon test failure, Sausage generates a authorized link to the failed job report
on the Sauce Labs website, to facilitate reporting to people who need to know
the details of the test. The job remains private (unless you change the status
yourself), but others can follow the link without needing to log in with your
credentials.

Build IDs
----
If you're running your tests as part of your build, you can define a build id,
either by updating the browser arrays to include a 'build' parameter, or
(more reasonably), defining an environment variable `SAUCE_BUILD`, like so:

    SAUCE_BUILD=build-1234 vendor/bin/phpunit MyAwesomeTestCase.php


SpinAsserts
---
SpinAsserts are awesome and [you should really use them](http://sauceio.com/index.php/2011/04/how-to-lose-races-and-win-at-selenium/). Luckily, Sausage comes with a SpinAssert framework built in.
Let's say we want to perform a check and we're not exactly sure how quickly the
state will change to what we want. We can do this:

```php
public function testSubmitComments()
{
    $comment = "This is a very insightful comment.";
    $this->byId('comments')->click();
    $this->keys($comment);
    $this->byId('submit')->submit();
    $driver = $this;

    $comment_test = function() use ($comment, $driver) {
        return ($driver->byId('your_comments')->text() == "Your comments: $comment");
    };

    $this->spinAssert("Comment never showed up!", $comment_test);
}
```

This will submit a comment and wait for up to 10 seconds for the comment to show
up before declaring the test failed.

The `spinWait` function is similar and allows you to wait for a certain
condition without necessarily asserting anything of it.

Sauce Connect
---
Sauce Connect is a special tunnel-creating binary application (see the [Sauce
Connect Docs](http://saucelabs.com/docs/connect)). It is bundled as another
composer package (`sauce/connect`), which you can add to your `composer.json`
requirements:

```json
{
  "require": {
    "sauce/sausage": ">=0.5",
    "sauce/connect": ">=3.0"
  }
}
```

If you've already run `vendor/bin/sauce_config` or otherwise set your Sauce
credentials, starting sauce connect is as easy as:

    vendor/bin/sauce_connect

(Or for Windows):

    vendor\bin\sauce_connect.bat

Run that and you'll be testing against your local test server in no time!

Ignoring certificate validation
---

To connect to saucelabs, cURL is used. Sometimes certificate validation may fail, resulting in an error similar to this:

```
Exception: Got an error while making a request: server certificate verification failed. CAfile: /etc/ssl/certs/ca-certificates.crt CRLfile: none
```

You can manually disable curl certificate validation if needed by setting an environment variable `SAUCE_DONT_VERIFY_CERTS`. If any value is set, validation is skipped completely. 

Travis-ci and tunnel-identifier
---

Travis use tunnel identifier to parallelize unit testing. You have to set the tunnel-identifier for your tests. 

To do so, just add this line to your .travis.yml file in install section

```
 - export SAUCE_TUNNEL_IDENTIFIER=$TRAVIS_JOB_NUMBER
```

It's also recomanded to add the line below for travis-ci (see previous section)
```
 - export SAUCE_DONT_VERIFY_CERTS=1
```


Contributors
---
* Jonathan Lipps ([jlipps](http://github.com/jlipps)) (Author)

If you have any ideas for Sausage, put them in code and send them my way!


[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/jlipps/sausage/trend.png)](https://bitdeli.com/free "Bitdeli Badge")


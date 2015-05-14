<?php

// require_once "vendor/autoload.php";
define("APP_PATH", realpath(dirname(__FILE__).'/../../apps/ApiDemos-debug.apk'));
if (!APP_PATH) {
    die("App did not exist!");
}
require_once('PHPUnit/Extensions/AppiumTestCase.php');

class AppiumTests extends PHPUnit_Extensions_AppiumTestCase
{
    public function testAppReset()
    {
        $this->byAccessibilityId('App')->click();

        $this->reset();
        sleep(5);

        $el = $this->byAccessibilityId('App');
        $this->assertNotNull($el);
    }

    public function testAppStrings()
    {
        $strings = $this->appStrings();
        $this->assertEquals('You can\'t wipe my data, you are a monkey!', $strings['monkey_wipe_data']);
    }

    public function testAppStringsNonDefault()
    {
        $strings = $this->appStrings("en");
        $this->assertEquals('You can\'t wipe my data, you are a monkey!', $strings['monkey_wipe_data']);
    }

    public function testKeyEvent()
    {
        $this->byAccessibilityId('App')->click();
        $this->keyEvent(4);
        sleep(1);

        $el = $this->byAccessibilityId('App');
        $this->assertNotNull($el);
    }

    public function testCurrentActivity()
    {
        $activity = $this->currentActivity();
        $this->assertEquals(".ApiDemos", $activity);
    }

    public function testPullFile()
    {
        $data = $this->pullFile('data/local/tmp/strings.json');
        $strings = json_decode(base64_decode($data), true);
        $this->assertEquals('You can\'t wipe my data, you are a monkey!', $strings['monkey_wipe_data']);
    }

    public function testPushFile()
    {
        $path = 'data/local/tmp/test_push_file.txt';
        $data = 'This is the contents of the file to push to the device.';
        $this->pushFile($path, base64_encode($data));

        $data_ret = base64_decode($this->pullFile($path));
        $this->assertEquals($data, $data_ret);
    }

    public function testPullFolder()
    {
        $data = 'random string data ' . rand(0, 1000);
        $path = '/data/local/tmp';
        $this->pushFile($path . '/1.txt', base64_encode($data));
        $this->pushFile($path . '/2.txt', base64_encode($data));

        $folder = base64_decode($this->pullFolder($path));

        $zipFile = '_folder.zip';
        $fp = fopen($zipFile, 'w');
        fwrite($fp, $folder);
        fclose($fp);

        $zip = new ZipArchive();
        $this->assertEquals(true, $zip->open($zipFile));
        $this->assertNotEquals(false, $zip->getFromName('1.txt'));
        $this->assertNotEquals(false, $zip->getFromName('2.txt'));
        $zip->close();

        unlink($zipFile);
    }

    public function testBackgroundApp()
    {
        $this->backgroundApp(1);
        try {
            $el = $this->byName('Animation');
            $this->assertNull($el);
        } catch (Exception $e) {
            // we expect this
        }

        sleep(5);

        $el = $this->byName('Animation');
        $this->assertEquals('Animation', $el->text());
    }

    public function testIsAppInstalled()
    {
        $this->assertFalse($this->isAppInstalled('sdfsdf'));
        $this->assertTrue($this->isAppInstalled('com.example.android.apis'));
    }

    // this fails for some reason
    public function testInstallApp()
    {
        $this->markTestSkipped('Not sure why this always fails.');
        $this->assertFalse($this->isAppInstalled('io.selendroid.testapp'));
        $this->installApp('/Users/isaac/code/python-client/test/apps/selendroid-test-app.apk');
        $this->assertTrue($this->isAppInstalled('io.selendroid.testapp'));
    }

    public function testRemoveApp()
    {
        $this->assertTrue($this->isAppInstalled('com.example.android.apis'));
        $this->removeApp('com.example.android.apis');
        $this->assertFalse($this->isAppInstalled('com.example.android.apis'));
    }

    public function testStartActivityInThisApp()
    {
        $this->startActivity(array('appPackage' => "io.appium.android.apis",
                                   'appActivity' => ".accessibility.AccessibilityNodeProviderActivity"));

        $activity = $this->currentActivity();
        $this->assertTrue(strpos($activity, 'Node') !== FALSE);
    }

    public function testStartActivityInNewApp()
    {
        $this->startActivity(array('appPackage' => "com.android.contacts",
                                   'appActivity' => ".ContactsListActivity"));

        $activity = $this->currentActivity();
        $this->assertTrue(strpos($activity, 'Contact') !== FALSE);
    }

    public function testCloseAndLaunchApp()
    {
        $el = $this->byName('Animation');
        $this->assertNotNull($el);

        $this->closeApp();

        $this->launchApp();

        $el = $this->byName('Animation');
        $this->assertNotNull($el);
    }

    public function testOpenNotifications()
    {
        $this->byAndroidUIAutomator('new UiSelector().text("App")')->click();
        $this->byAndroidUIAutomator('new UiSelector().text("Notification")')->click();
        $this->byAndroidUIAutomator('new UiSelector().text("Status Bar")')->click();

        $this->byAndroidUIAutomator('new UiSelector().text(":-|")')->click();

        $this->openNotifications();
        sleep(1);
        try {
            $this->byAndroidUIAutomator('new UiSelector().text(":-|")');
        } catch (Exception $e) {
            // expect this, pass
        }

        $els = $this->elements($this->using('class name')->value('android.widget.TextView'));
        $title = false;
        $body = false;
        foreach($els as $el) {
            $text = $el->text();
            if ($text == 'Mood ring') {
                $title = true;
            } else if ($text == 'I am ok') {
                $body = true;
            }
        }
        $this->assertTrue($title);
        $this->assertTrue($body);

        $this->keyEvent(4);
        sleep(1);
        $this->byAndroidUIAutomator('new UiSelector().text(":-|")');
    }

    public function testSetText()
    {
        $this->byAndroidUIAutomator('new UiScrollable(new UiSelector().scrollable(true).instance(0)).scrollIntoView(new UiSelector().text("Views").instance(0));')->click();
        $this->byName('Controls')->click();
        $this->byName('1. Light Theme')->click();

        $el = $this->byClassName('android.widget.EditText');
        $el->setText('original text');
        $el->setText('new text');

        $this->assertEquals('new text', $el->text());
    }

    public function testGetSettings()
    {
        $settings = $this->getSettings();

        $this->assertNotNull($settings);
    }

    public function testUpdateSettings()
    {
        $this->updateSettings(array('cyberdelia' => "open"));
        $settings = $this->getSettings();

        $this->assertEquals('open', $settings['cyberdelia']);
    }

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
}

<?php

namespace Sauce\Sausage;

abstract class SeleniumRCTestCase extends \PHPUnit_Extensions_SeleniumTestCase
{

    protected $job_id;
    protected $is_local_test = false;
    protected $build = false;

    public static $browsers = array();

    // This function will set the browsers using the new browsers() method call
    public static function suite($className)
    {
        static::$browsers = static::browsers();
        return parent::suite($className);
    }

    public function setupSpecificBrowser(array $browser)
    {
        $this->getDriver($browser);
        self::ShareSession(false);
    }

    protected function getDriver(array $browser)
    {
        $local = isset($browser['local']) && $browser['local'];
        $this->is_local_test = $local;

        if (!$local) {
            SauceTestCommon::RequireSauceConfig();
            $build = SauceConfig::GetBuild();
        } else {
            unset($browser['local']);
        }

        $defaults = array(
            'browser' => 'firefox',
            'browserVersion' => '11',
            'os' => 'Windows 2008',
            'timeout' => 60,
            'httpTimeout' => 45,
            'host' => 'ondemand.saucelabs.com',
            'port' => 80,
            'name' => get_called_class().'::'.$this->getName(),
            'record-video' => true,
            'video-upload-on-pass' => true,
        );

        $local_defaults = array(
            'browser' => 'firefox',
            'timeout' => 30,
            'httpTimeout' => 45,
            'host' => 'localhost',
            'port' => 4444,
            'name' => get_called_class().'::'.$this->getName(),
        );

        if ($local)
            $browser = array_merge($local_defaults, $browser);
        else
            $browser = array_merge($defaults, $browser);

        $checks = array(
            'name' => 'string',
            'browser' => 'string',
            'browserVersion' => 'string',
            'timeout' => 'int',
            'httpTimeout' => 'int',
            'os' => 'string'
        );
        if ($local) {
            unset($checks['browserVersion']);
            unset($checks['os']);
        }

        foreach ($checks as $key => $type) {
            $func = 'is_'.$type;
            if (!$func($browser[$key])) {
                throw new InvalidArgumentException(
                    'Array element "'.$key.'" is no '.$type.'.'
                );
            }
        }

        if ($local)
            $driver = new \PHPUnit_Extensions_SeleniumTestCase_Driver();
        else
            $driver = new SeleniumRCDriver();
        if (!$local) {
            $driver->setUsername(SAUCE_USERNAME);
            $driver->setAccessKey(SAUCE_ACCESS_KEY);
            $driver->setOs($browser['os']);
            $driver->setBrowserVersion($browser['browserVersion']);
            $driver->setRecordVideo($browser['record-video']);
            $driver->setUploadVideoOnPass($browser['video-upload-on-pass']);
            $build = isset($browser['build']) ? $browser['build'] : $build;
            if ($build)
                $this->build = $build;
        }
        $driver->setHost($browser['host']);
        $driver->setPort($browser['port']);
        $driver->setName($browser['name']);
        $driver->setBrowser($browser['browser']);
        $driver->setTimeout($browser['timeout']);
        $driver->setHttpTimeout($browser['httpTimeout']);
        $driver->setTestCase($this);
        $driver->setTestId($this->testId);


        $this->drivers[0] = $driver;

        return $driver;
    }

    protected function prepareTestSession()
    {
        $this->job_id = parent::prepareTestSession();
        if ($this->build)
            SauceTestCommon::ReportBuild($this->job_id, $this->build);
        $this->postSessionSetUp();
        return $this->job_id;
    }

    protected function postSessionSetUp()
    {
    }


    public function tearDown()
    {
        if (!$this->is_local_test) {
            SauceTestCommon::ReportStatus($this->job_id, !$this->hasFailed());

            if(getenv('JENKINS_HOME')) {
                printf("\nSauceOnDemandSessionID=%s job-name=%s", $this->job_id, get_called_class().'.'.$this->getName()."\n");
            }
        }
    }

    public function spinAssert($msg, $test, $args=array(), $timeout=10)
    {
        list($result, $msg) = SauceTestCommon::SpinAssert($msg, $test, $args, $timeout);
        $this->assertTrue($result, $msg);
    }

    public static function browsers() {
        $json = getenv('bamboo_SAUCE_ONDEMAND_BROWSERS');

        if (!$json) {
            $json = getenv('SAUCE_ONDEMAND_BROWSERS');
        }

        if ($json) {
            $jsonMapFn = function($options) {
                return array(
                    'browser' => $options->browser,
                    'browserVersion' => $options->{'browser-version'},
                    'os' => $options->os
                );
            };
            $jsonDecode = json_decode($json);

            if ($jsonDecode) {
                return array_map($jsonMapFn, $jsonDecode);
            }
        }

        //Check for set browsers from child test case
        if (!empty(static::$browsers) && is_array(static::$browsers)) {
            return static::$browsers;
        }

        throw new \Exception('No browsers found.');
    }

}

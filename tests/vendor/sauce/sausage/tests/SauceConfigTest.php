<?php

require_once('vendor/autoload.php');
require_once('src/Sauce/Sausage/SauceConfig.php');

class SauceConfigTest extends PHPUnit_Framework_TestCase {

	/**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
	public function testLoadConfig_DontVerifyCertsPresent_VerifyCertsFalse() {
		putenv('SAUCE_DONT_VERIFY_CERTS=1');
		Sauce\Sausage\SauceConfig::LoadConfig(false);

		$this->assertFalse(SAUCE_VERIFY_CERTS);
	}

	/**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
	public function testLoadConfig_DontVerifyCertsPresentButEmpty_VerifyCertsTrue() {
		putenv('SAUCE_DONT_VERIFY_CERTS=');
		Sauce\Sausage\SauceConfig::LoadConfig(false);

		$this->assertTrue(SAUCE_VERIFY_CERTS);
	}

	/**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
	public function testLoadConfig_VerifyCertsNotPresent_VerifyCertsTrue() {
		putenv('SAUCE_DONT_VERIFY_CERTS');
		Sauce\Sausage\SauceConfig::LoadConfig(false);
		$this->assertTrue(SAUCE_VERIFY_CERTS);
	}

}

?>
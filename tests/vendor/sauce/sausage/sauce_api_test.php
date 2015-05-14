<?php

require_once "vendor/autoload.php";

use Sauce\Sausage\SauceConfig;

list($username, $access_key) = SauceConfig::GetConfig();

$s = new Sauce\Sausage\SauceAPI($username, $access_key);

$res = $s->updateJob('0e2ae11933664d0ba26948d379fc67a6', array('passed'=>TRUE));
print_r($res);
//$res = $s->getAccountDetails();
//print_r($res);
//$res = $s->getAccountLimits();
//print_r($res);
//$res = $s->createSubaccount(array('username'=>'jlippstest', 'email'=>'jlipps2@adsf.com', 'password'=>'testpass', 'name'=>"New Guy"));
//print_r($res);
//$res = $s->setSubaccountSubscription('sah', 'free');
//print_r($res);
//$res = $s->login('testpass', 'jlippstest');
//print_r($res);
//$res = $s->getUsage(null, null, 'jlippstest');
//print_r($res);
//$res = $s->getUsage('2012-03-01', '2012-05-01');
//print_r($res);
//$res = $s->deleteSubaccountSubscription('jlippstest');
//print_r($res);
//$res = $s->getAllBrowsers();
//print_r($res);
//$res = $s->getSeleniumRCBrowsers();
//print_r($res);
//$res = $s->getWebDriverBrowsers();
//print_r($res);
//$res = $s->getStatus();
//print_r($res);
//$res = $s->getSauceTestsCount();
//print_r($res);
//$res = $s->getScoutBrowsers();
//print_r($res);
//$res = $s->getScoutBrowsers(true);
//print_r($res);
//$res = $s->getJobs(null, null, 1);
//print_r($res);
//$res = $s->updateJob('0213180a592449948a0f46b3b2c23cdb', array('build'=>'1234'));
//$res = $s->getJobsForBuild('1234');
//print_r($res);
//$res = $s->getActivity();
//print_r($res);
//$res = $s->getUpdatedJobs(0);
//print_r($res);
//$res = $s->getJob('0e2ae11933664d0ba26948d379fc67a6');
//print_r($res);
//$res = $s->stopJob('0e2ae11933664d0ba26948d379fc67a6');
//print_r($res);
//$res = $s->createErrorReport("This is an error report, woot!");
//print_r($res);
//$res = $s->getTunnels();
//print_r($res);
//$res = $s->getTunnel('00af7b9575f6405b82176d983ac6aa94');
//print_r($res);
//$res = $s->deleteTunnel('00af7b9575f6405b82176d983ac6aa94');
//print_r($res);

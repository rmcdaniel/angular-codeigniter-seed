<?php

namespace Sauce\Sausage;

abstract class SauceTestCommon
{

    public static function RequireSauceConfig()
    {
        SauceConfig::LoadConfig(true);
    }

    public static function ReportStatus($session_id, $passed)
    {
        self::RequireSauceConfig();
        $api = new SauceAPI(SAUCE_USERNAME, SAUCE_ACCESS_KEY, SAUCE_VERIFY_CERTS);
        $api->updateJob($session_id, array('passed'=>$passed));
    }

    public static function SpinAssert($msg, $test, $args=array(), $timeout=10)
    {
        $num_tries = 0;
        $result = false;
        while ($num_tries < $timeout && !$result) {
            try {
                $result = call_user_func_array($test, $args);
            } catch (\Exception $e) {
                $result = false;
            }

            if (!$result)
                sleep(1);

            $num_tries++;
        }

        $msg .= " (Failed after $num_tries tries)";

        return array($result, $msg);
    }


    public static function ReportBuild($session_id, $build)
    {
        self::RequireSauceConfig();
        $api = new SauceAPI(SAUCE_USERNAME, SAUCE_ACCESS_KEY);
        $api->updateJob($session_id, array('build'=>$build));
    }

}

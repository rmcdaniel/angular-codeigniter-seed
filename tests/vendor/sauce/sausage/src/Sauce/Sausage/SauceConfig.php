<?php

namespace Sauce\Sausage;

define('CONFIG_PATH', dirname(__FILE__).'/../../../.sauce_config');

class SauceConfig
{

    public static function LoadConfig($fail_if_no_config = true)
    {
        if (!defined('SAUCE_USERNAME') && !defined('SAUCE_ACCESS_KEY')) {
            if (is_file(CONFIG_PATH)) {
                $config = file_get_contents(CONFIG_PATH);
                list($username, $access_key) = explode(',', $config);
                $username = trim($username);
                $access_key = trim($access_key);
            } elseif (getenv('SAUCE_USERNAME') && getenv('SAUCE_ACCESS_KEY')) {
                $username = getenv('SAUCE_USERNAME');
                $access_key = getenv('SAUCE_ACCESS_KEY');
            } elseif (getenv('SAUCE_USER_NAME') && getenv('SAUCE_API_KEY')) {
                $username = getenv('SAUCE_USER_NAME');
                $access_key = getenv('SAUCE_API_KEY');
            } elseif ($fail_if_no_config) {
                $msg = <<<EOF
We could not find your Sauce username or access key (which you can get from
https://saucelabs.com/account). You have two options for setting them:

1) run vendor/bin/sauce_config USERNAME ACCESS_KEY
2) export environment variables SAUCE_USERNAME and SAUCE_ACCESS_KEY

Please take one of these two steps and try again!
EOF;
                echo $msg;
                exit(1);
            } else {
                $username = $access_key = NULL;
            }

            if(getenv('SAUCE_DONT_VERIFY_CERTS')) {
                $env_sauce_dont_verify_certify = getenv('SAUCE_DONT_VERIFY_CERTS');
                define('SAUCE_VERIFY_CERTS', empty($env_sauce_dont_verify_certify));
            } else {
                define('SAUCE_VERIFY_CERTS', true);
            }
            define('SAUCE_USERNAME', $username);
            define('SAUCE_ACCESS_KEY', $access_key);
        }
        $build_envs = array(
            'SAUCE_BUILD',
            'BUILD_TAG',
            'BUILD_NUMBER',
            'TRAVIS_BUILD_NUMBER',
            'CIRCLE_BUILD_NUM'
        );
        foreach ($build_envs as $build_env) {
            if (!defined('SAUCE_BUILD') && getenv($build_env)) {
                define('SAUCE_BUILD', getenv($build_env));
            }
        }
    }

    public static function GetConfig($fail_if_no_config = true)
    {
        self::LoadConfig($fail_if_no_config);
        return array(SAUCE_USERNAME, SAUCE_ACCESS_KEY);
    }

    public static function GetBuild()
    {
        self::LoadConfig(false);
        if (defined('SAUCE_BUILD')) {
            return SAUCE_BUILD;
        }
        return false;
    }

    public static function WriteConfig($username, $access_key) {
        file_put_contents(CONFIG_PATH, "{$username},{$access_key}");
    }

}

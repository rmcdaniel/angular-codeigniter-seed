<?php

namespace Sauce\Sausage;

define('SAUCE_API_PREFIX', '/rest/v1/');

class SauceMethods
{

    protected $username;

    protected static $user_fields = array(
        'username',
        'name',
        'email',
        'password'
    );

    public function __construct($username)
    {
        $this->username = $username;
    }

    protected function requireParam($param_name, $param_val,
        $check_truthiness=true)
    {
        if($param_val === NULL || ($check_truthiness && !$param_val))
            throw new \Exception("$param_name is required");
    }

    protected function requireParams(array $params)
    {
        foreach ($params as $param_set)
            call_user_func_array(array($this, 'requireParam'), $param_set);
    }

    /* user methods */

    public function getAccountDetails()
    {
        return array(SAUCE_API_PREFIX.'users/'.$this->username);
    }

    public function getAccountLimits()
    {
        return array(SAUCE_API_PREFIX.$this->username.'/limits');
    }

    public function createUser($user_details)
    {
        throw new \Exception("Create user is only for authorized partners");
    }

    public function login($password, $username = null)
    {
        $this->requireParam("password", $password);
        $username = $username ? $username : $this->username;

        return array(
            SAUCE_API_PREFIX.'users/'.$username.'/login',
            "POST",
            array('password'=>$password)
        );
    }

    public function createSubaccount(array $subacct_details)
    {
        $this->requireParam("subacct_details", $subacct_details);

        foreach ($subacct_details as $key => $val)
            if (!in_array($key, self::$user_fields))
                throw new \Exception("$key is not a valid subaccount field");

        foreach (self::$user_fields as $key)
            if (!isset($subacct_details[$key]))
                throw new \Exception("$key is a required subaccount field");

        return array(
            SAUCE_API_PREFIX.'users/'.$this->username,
            "POST",
            $subacct_details
        );
    }

    public function setSubaccountSubscription($username, $plan)
    {
        $this->requireParam("username", $username);
        $this->requireParam("plan", $plan);

        return array(
            SAUCE_API_PREFIX.'users/'.$username.'/subscription',
            "POST",
            array('plan' => $plan)
        );
    }

    public function deleteSubaccountSubscription($username)
    {
        $this->requireParam("username", $username);

        return array(
            SAUCE_API_PREFIX.'users/'.$username.'/subscription',
            "DELETE"
        );
    }

    /* usage and activity methods */

    public function getUsage($start = null, $end = null, $username = null)
    {
        $username = $username ? $username : $this->username;

        $q = http_build_query(array('start' => $start, 'end' => $end));

        return array(SAUCE_API_PREFIX.'users/'.$username.'/usage?'.$q);
    }

    /* job methods */

    public function getJobs($from = null, $to = null, $limit = null,
        $skip = null, $username = null, $full = false)
    {
        $username = $username ? $username : $this->username;
        $q = http_build_query(array(
            'from' => $from,
            'to' => $to,
            'limit' => $limit,
            'skip' => $skip,
            'full' => $full
        ));
        return array(SAUCE_API_PREFIX.$username.'/jobs_safe?'.$q);
    }

    public function getJobsForBuild($build, $limit = null, $skip = null, $username = null, $full = false)
    {
        $this->requireParam("build", $build);
        $username = $username ? $username : $this->username;
        $q = http_build_query(array(
            'limit' => $limit,
            'skip' => $skip,
            'full' => $full
        ));
        return array(SAUCE_API_PREFIX.$username.'/build/'.$build.'/jobs?'.$q);
    }

    public function getUpdatedJobs($since, $username = null)
    {
        $this->requireParam("since", $since, false);
        $username = $username ? $username : $this->username;
        $q = http_build_query(array('since' => $since));
        return array(SAUCE_API_PREFIX.$username.'/updated_jobs_safe?'.$q);
    }

    public function getJob($job_id)
    {
        $this->requireParam('job_id', $job_id);
        return array(SAUCE_API_PREFIX.$this->username.'/jobs/'.$job_id);
    }

    public function getActivity($username = null)
    {
        $username = $username ? $username : $this->username;
        return array(SAUCE_API_PREFIX.$username.'/activity');
    }

    public function updateJob($job_id, $job_details)
    {
        $this->requireParams(array(
            array("job_id", $job_id),
            array("job_details", $job_details)
        ));

        return array(
            SAUCE_API_PREFIX.$this->username.'/jobs/'.$job_id,
            "PUT",
            $job_details
        );

    }

    public function stopJob($job_id)
    {
        $this->requireParam("job_id", $job_id);
        return array(
            SAUCE_API_PREFIX.$this->username.'/jobs/'.$job_id.'/stop',
            "PUT"
        );
    }
    
    public function getJobAssets($job_id, $username = null)
    {
        $this->requireParam('job_id', $job_id);
        $username = $username ? $username : $this->username;
        return array(SAUCE_API_PREFIX . $username . '/jobs/' . $job_id . '/assets');
    }
    
    /* tunnel methods */

    public function getTunnels($username = null)
    {
        $username = $username ? $username : $this->username;
        return array(SAUCE_API_PREFIX.$username.'/tunnels');
    }

    public function getTunnel($tunnel_id, $username = null)
    {
        $this->requireParam("tunnel_id", $tunnel_id);
        $username = $username ? $username : $this->username;
        return array(SAUCE_API_PREFIX.$username.'/tunnels/'.$tunnel_id);
    }

    public function deleteTunnel($tunnel_id, $username = null)
    {
        $this->requireParam("tunnel_id", $tunnel_id);
        $username = $username ? $username : $this->username;
        return array(
            SAUCE_API_PREFIX.$username.'/tunnels/'.$tunnel_id,
            "DELETE"
        );
    }

    /* Reporting methods */

    public function createErrorReport($info, $tunnel = null, $username = null)
    {
        $username = $username ? $username : $this->username;
        $params = array('info' => $info);
        if ($tunnel !== null)
            $params['Tunnel'] = $tunnel;

        return array(SAUCE_API_PREFIX.$username.'/errors', "POST", $params);
    }

    /* Sauce Labs informational methods */

    public function getAllBrowsers()
    {
        return array(SAUCE_API_PREFIX.'info/browsers/all');
    }

    public function getSeleniumRCBrowsers()
    {
        return array(SAUCE_API_PREFIX.'info/browsers/selenium-rc');
    }

    public function getWebDriverBrowsers()
    {
        return array(SAUCE_API_PREFIX.'info/browsers/webdriver');
    }

    public function getStatus()
    {
        return array(SAUCE_API_PREFIX.'info/status');
    }

    public function getSauceTestsCount()
    {
        return array(SAUCE_API_PREFIX.'info/counter');
    }

    public function getScoutBrowsers($sanitized=false)
    {
        $endpoint = SAUCE_API_PREFIX.'info/scout';
        $endpoint .= $sanitized ? '/sanitized' : '';
        return array($endpoint);
    }
}

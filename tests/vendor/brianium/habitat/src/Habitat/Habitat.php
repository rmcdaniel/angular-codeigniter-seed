<?php
namespace Habitat;

use Habitat\Environment\Environment;
use Habitat\Sapi\SapiResolver;

class Habitat
{
    /**
     * @var \Habitat\Habitat
     */
    private static $instance = null;

    /**
     * @var Environment\Environment
     */
    protected $environment;

    /**
     * Constructor
     */
    private function __construct()
    {
        $resolver = new SapiResolver();
        $this->environment = new Environment($resolver);
    }

    /**
     * @return \Habitat\Environment\Environment
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Returns an environment variable. Delegates to PHP's
     * native getenv()
     *
     * @param $var
     * @return mixed
     */
    public static function getenv($var)
    {
        return Habitat::getInstance()
            ->getEnvironment()
            ->getenv($var);
    }

    /**
     * Sets an environment variable. Delegats to PHP's
     * native putenv()
     *
     * @param $var
     * @return mixed
     */
    public static function putenv($var)
    {
        return Habitat::getInstance()
            ->getEnvironment()
            ->putenv($var);
    }

    /**
     * Returns all environment variables. If $_ENV
     * is set it will be returned, otherwise $_ENV
     * will be set to information parsed from phpinfo()
     * and then returned
     *
     * @return array
     */
    public static function getAll()
    {
        return Habitat::getInstance()
            ->getEnvironment()
            ->getAll();
    }

    /**
     * Singleton access for Habitat
     *
     * @return Habitat
     */
    private static function getInstance()
    {
        if (is_null(static::$instance))
            static::$instance = new Habitat();
        return static::$instance;
    }
}
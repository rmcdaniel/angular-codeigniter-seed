<?php
namespace Habitat\Environment;

use Habitat\Parser\ParserFactory;
use Habitat\Sapi\SapiResolverInterface;

class Environment
{
    /**
     * @var \Habitat\Parser\ParserInterface
     */
    protected $parser;

    /**
     * Constructor
     *
     * @param SapiResolverInterface $resolver
     */
    public function __construct(SapiResolverInterface $resolver)
    {
        $factory = new ParserFactory($resolver);
        $this->parser = $factory->create();
    }

    /**
     * Return all environment variables.
     * If $_ENV is set, then we are good to go. If
     * $_ENV has not been populated yet, read the
     * environment variables from phpinfo()
     *
     * @return array
     */
    public function getAll()
    {
        if ($_ENV) return $_ENV;
        $info = $this->getPhpInfo();
        $_ENV = $this->parser->parse($info);
        return $_ENV;
    }

    /**
     * Sits on top of getenv()
     *
     * @param $var
     * @return string
     */
    public function getenv($var)
    {
        return getenv($var);
    }

    /**
     * Sits on top of putenv
     *
     * @param $var
     * @return mixed
     */
    public function putenv($var)
    {
        return putenv($var);
    }

    /**
     * Get the contents of the phpinfo() call
     *
     * @return string
     */
    protected function getPhpInfo()
    {
        ob_start();
        phpinfo(INFO_ENVIRONMENT);
        $info = ob_get_clean();
        return $info;
    }
}
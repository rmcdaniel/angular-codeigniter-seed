<?php
namespace Habitat\Sapi;

class SapiResolver implements SapiResolverInterface
{
    /**
     * @var string
     */
    protected $sapiName;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sapiName = php_sapi_name();
    }

    /**
     * {@inheritdoc}
     */
    public function isCli()
    {
        return strpos($this->sapiName, 'cli') !== false;
    }
}
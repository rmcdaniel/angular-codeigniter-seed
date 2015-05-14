<?php
namespace Habitat\Sapi;

class SapiResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Habitat\Sapi\SapiResolver
     */
    protected $resolver;

    public function setUp()
    {
        $this->resolver = new SapiResolver();
    }

    public function test_isCli_is_true()
    {
        $this->assertTrue($this->resolver->isCli());
    }
}
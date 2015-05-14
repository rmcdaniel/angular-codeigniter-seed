<?php
namespace Habitat\Environment;

class EnvironmentTest extends \PHPUnit_Framework_TestCase
{
    protected $environment;

    public function setUp()
    {
        $resolver = $this->getMock('\Habitat\Sapi\SapiResolverInterface');
        $resolver->expects($this->once())
            ->method('isCli')
            ->will($this->returnValue(true));
        $this->environment = new Environment($resolver);
        putenv("HABITAT=value");
    }

    public function tearDown()
    {
        $_ENV = array();
        putenv("HABITAT");
    }

    public function test_getenv_returns_env_variable()
    {
        $hab = $this->environment->getenv('HABITAT');
        $this->assertEquals('value', $hab);
    }

    public function test_putenv_puts_environment_var()
    {
        $set = $this->environment->putenv('HABITAT=new');
        $this->assertTrue($set);
        $this->assertEquals('new', $this->environment->getenv('HABITAT'));
    }

    public function test_getAll_returns_ENV_if_set()
    {
        $all = array('test' => 'one');
        $_ENV = $all;
        $this->assertEquals($all, $this->environment->getAll());
    }

    public function test_getAll_returns_env_vars_from_phpinfo_if_no_ENV_super_global()
    {
        $this->assertNotEmpty($this->environment->getAll());
    }
}
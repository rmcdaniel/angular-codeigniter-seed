<?php
namespace Habitat;

class HabitatTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        putenv("HABITAT=value");
    }

    public function tearDown()
    {
        $_ENV = array();
        putenv("HABITAT");
    }

    public function test_getenv_returns_env_variable()
    {
        $hab = Habitat::getenv('HABITAT');
        $this->assertEquals('value', $hab);
    }

    public function test_putenv_puts_environment_var()
    {
        $set = Habitat::putenv('HABITAT=new');
        $this->assertTrue($set);
        $this->assertEquals('new', Habitat::getenv('HABITAT'));
    }

    public function test_getAll_returns_ENV_if_set()
    {
        $all = array('test' => 'one');
        $_ENV = $all;
        $this->assertEquals($all, Habitat::getAll());
    }

    public function test_getAll_returns_env_vars_from_phpinfo_if_no_ENV_super_global()
    {
        $this->assertNotEmpty(Habitat::getAll());
    }
}
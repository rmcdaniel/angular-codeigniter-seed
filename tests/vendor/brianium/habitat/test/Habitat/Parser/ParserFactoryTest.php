<?php
namespace Habitat\Parser;

class ParserFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * A mock object of a SapiResolverInterface
     */
    protected $resolver;

    /**
     * @var \Habitat\Parser\ParserFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->resolver = $this->getMock('\Habitat\Sapi\SapiResolverInterface');
        $this->factory = new ParserFactory($this->resolver);
    }

    public function test_create_creates_CliParser_if_isCli_true()
    {
        $this->resolver->expects($this->once())
            ->method('isCli')
            ->will($this->returnValue(true));

        $parser = $this->factory->create();

        $this->assertInstanceOf('\Habitat\Parser\CliParser', $parser);
    }

    public function test_create_creates_HtmlParser_if_isCli_false()
    {
        $this->resolver->expects($this->once())
            ->method('isCli')
            ->will($this->returnValue(false));

        $parser = $this->factory->create();

        $this->assertInstanceOf('\Habitat\Parser\HtmlParser', $parser);
    }
}
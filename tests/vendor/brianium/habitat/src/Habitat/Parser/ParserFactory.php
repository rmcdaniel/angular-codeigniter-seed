<?php
namespace Habitat\Parser;

use Habitat\Sapi\SapiResolverInterface;

class ParserFactory
{
    /**
     * @var \Habitat\Sapi\SapiResolverInterface
     */
    protected $resolver;

    /**
     * Constructor
     *
     * @param SapiResolverInterface $resolver
     */
    public function __construct(SapiResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * Create an appropriate parser
     * based on the current sapi
     *
     * @return CliParser|HtmlParser
     */
    public function create()
    {
        if ($this->resolver->isCli())
            return new CliParser();
        return new HtmlParser();
    }
}
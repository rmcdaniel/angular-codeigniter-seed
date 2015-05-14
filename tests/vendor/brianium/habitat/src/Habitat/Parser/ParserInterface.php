<?php
namespace Habitat\Parser;

interface ParserInterface
{
    /**
     * Parse the environment variables into an associative array
     *
     * @param $content
     * @return array
     */
    public function parse($content);
}
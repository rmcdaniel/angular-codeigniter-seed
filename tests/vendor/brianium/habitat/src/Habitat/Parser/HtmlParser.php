<?php
namespace Habitat\Parser;

use DOMXPath;

class HtmlParser implements ParserInterface
{
    /**
     * @var \DOMDocument
     */
    protected $document;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->document = new \DOMDocument();
    }

    /**
     * {@inheritdoc}
     */
    public function parse($html)
    {
        $this->document->loadHTML($html);
        $expression = '//tr//td[contains(@class, "e")]';
        $xpath = new DOMXpath($this->document);
        $tds = $xpath->query($expression);
        $variables = array();
        foreach ($tds as $td)
            $variables[trim($td->nodeValue)] = trim($td->nextSibling->nodeValue);
        return $variables;
    }
}
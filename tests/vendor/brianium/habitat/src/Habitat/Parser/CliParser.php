<?php
namespace Habitat\Parser;

class CliParser implements ParserInterface
{
    /**
     * A pattern for removing extraneous php information
     * in the phpinfo() output
     *
     * @var string
     */
    private static $phpinfo = '/phpinfo\(\)|Environment|Variable[\s]+=>[\s]+Value/i';

    /**
     * A pattern for splitting the lines output to the cli
     *
     * @var string
     */
    private static $lines = '/^(.*)[\s]+=>[\s]+(.*)$/m';

    /**
     * {@inheritdoc}
     */
    public function parse($content)
    {
        preg_match_all(static::$lines, $this->trim($content), $matches);
        $variables = array();
        if ($matches) {
            $keys = $matches[1];
            $values = $matches[2];
            for ($i = 0; $i < count($keys); $i++)
                $variables[$keys[$i]] = $values[$i];
        }
        return $variables;
    }

    /**
     * Removes unnecessary php information and
     * trims whitespace
     *
     * @param $content
     * @return string
     */
    protected function trim($content)
    {
        $content = preg_replace(static::$phpinfo, '', $content);
        return trim($content);
    }
}
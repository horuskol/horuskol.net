<?php

declare(strict_types=1);


namespace App;


use Mni\FrontYAML\Markdown\MarkdownParser;

class ParsedownParser implements MarkdownParser
{
    /**
     * @var \App\Parsedown
     */
    protected $parser;



    /**
     * ParsedownParser constructor.
     */
    public function __construct()
    {
        $this->parser = new Parsedown();
    }



    /**
     * @param  string $markdown
     * @return  string
     */
    public function parse($markdown)
    {
        return $this->parser->text($markdown);
    }
}
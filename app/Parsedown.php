<?php

declare(strict_types=1);


namespace App;

use Highlight\Highlighter;
use Parsedown as BaseParsedown;

class Parsedown extends BaseParsedown
{
    /**
     * @var \Highlight\Highlighter
     */
    protected $highlighter;



    /**
     * Parsedown constructor.
     *
     * Prepare a fresh instance of the syntax highlighter.
     */
    public function __construct()
    {
        $this->highlighter = new Highlighter();
    }



    /**
     * Add syntax highlighting to a fenced code block.
     *
     * @param  array $block
     * @return  array
     */
    protected function blockFencedCodeComplete($block)
    {
        try {
            if ($class = array_get($block, 'element.text.attributes.class', false)) {
                if (starts_with($class, 'language-')) {
                    $code = array_get($block, 'element.text.text', '');
                    $code = $this->highlighter->highlight(str_after($class, 'language-'), $code)->value;
                    array_set($block, 'element.text.text', $code);
                    $block['element']['text']['attributes']['class'] = "hljs {$class}";
                } else {
                    $block = parent::blockFencedCodeComplete($block);
                }
            }

        } catch (\Exception $e) {
            $block = parent::blockFencedCodeComplete($block);
        }

        return $block;
    }
}
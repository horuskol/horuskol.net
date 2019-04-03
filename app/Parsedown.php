<?php

declare(strict_types=1);


namespace App;

use Highlight\Highlighter;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Parsedown as BaseParsedown;

class Parsedown extends BaseParsedown
{
    /**
     * @var Highlighter
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
            if ($class = Arr::get($block, 'element.text.attributes.class', false)) {
                if (Str::startsWith($class, 'language-')) {
                    $code = Arr::get($block, 'element.text.text', '');
                    $code = $this->highlighter->highlight(Str::after($class, 'language-'), $code)->value;
                    Arr::set($block, 'element.text.text', $code);
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
<?php

return [
    'baseUrl' => '',
    'production' => false,
    'collections' => [
        'posts' => [
            'path' => 'blog/{date|Y-m-d}/{filename}',
            'sort' => '-date',
            'excerpt' => function ($page, $wordLength = 25) {
                $strippedContent = strip_tags($page->getContent());

                $words = explode(" ", $strippedContent);
                array_splice($words, $wordLength);

                return implode(" ", $words);
            }
        ]
    ],
];

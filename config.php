<?php

return [
    'baseUrl' => 'http://horuskol.net',
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
        ],
        'presentations' => [
            'path' => 'presentations/{date|Y-m-d}/{-presentation}/{filename}',
            'sort' => 'filename',
        ],
    ],
];

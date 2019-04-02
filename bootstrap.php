<?php


use App\Listeners\AddTagIndexes;
use App\ParsedownParser;
use Mni\FrontYAML\Markdown\MarkdownParser;
use TightenCo\Jigsaw\Jigsaw;
use TightenCo\Jigsaw\Loaders\DataLoader;
use TightenCo\Jigsaw\Loaders\CollectionRemoteItemLoader;

/** @var $container \Illuminate\Container\Container */
/** @var $events \TightenCo\Jigsaw\Events\EventBus */

/**
 * You can run custom code at different stages of the build process by
 * listening to the 'beforeBuild', 'afterCollections', and 'afterBuild' events.
 *
 * For example:
 *
 * $events->beforeBuild(function (Jigsaw $jigsaw) {
 *     // Your code here
 * });
 */

$container->bind(MarkdownParser::class, ParsedownParser::class);

$container->bind(AddTagIndexes::class, function ($c) {
    return new AddTagIndexes($c[DataLoader::class], $c[CollectionRemoteItemLoader::class]);
});

$events->afterCollections(function (Jigsaw $jigsaw) use ($container) {
    $container->make(AddTagIndexes::class)->handle($jigsaw);
});
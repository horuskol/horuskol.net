<?php

declare(strict_types=1);

namespace App\Listeners;

use TightenCo\Jigsaw\Jigsaw;
use TightenCo\Jigsaw\Loaders\CollectionRemoteItemLoader;
use TightenCo\Jigsaw\Loaders\DataLoader;

class AddTagIndexes
{
    /**
     * @var DataLoader
     */
    protected $dataLoader;

    /**
     * The jigsaw instance so we can access the loaded collections and add in our tag collection.
     * @var Jigsaw
     */
    protected $jigsaw;

    /**
     * @var CollectionRemoteItemLoader
     */
    protected $remoteItemLoader;

    public function __construct(DataLoader $dataLoader, CollectionRemoteItemLoader $remoteItemLoader)
    {
        $this->dataLoader = $dataLoader;
        $this->remoteItemLoader = $remoteItemLoader;
    }

    /**
     * Handle `afterCollections` hook to add new tag collections before building the sites pages.
     */
    public function handle(Jigsaw $jigsaw): void
    {
        $this->jigsaw = $jigsaw;

        // extract tags from our blog posts
        $tags = $this->jigsaw->getCollection('posts')
            ->flatMap // flatten the posts collection
            ->tags // load all tags from all posts
            ->unique() // we only want unique tags
            ->values(); // reset keys in the array

        // create a tag collection configuration
        // this is used to create temporary source files that can then be created into actual pages
        $tagCollectionConfiguration = collect([
            'tags' => [
                'extends' => '_layouts.tag',
                'section' => 'tag', // otherwise it defaults to 'content' and interferes with the master template
                'path' => 'blog/tags/{tag}',
                'items' => $tags->map(function ($tag) {
                    return [
                        'filename' => $tag, // otherwise the filenames are names tag_n
                        'tag' => $tag,
                        'title' => $tag,
                        'path' => 'blog/tags/{tag}',
                    ];
                })
            ]
        ]);

        // add the new collection configuration to jigsaw
        $this->jigsaw->app->config->get('collections')
            ->put('tags', $tagCollectionConfiguration['tags']);

        // load the site data using the additional configuration
        $siteData = $this->dataLoader->loadSiteData($this->jigsaw->app->config);

        // writes the site data to a temporary location
        $this->remoteItemLoader->write($siteData->collections, $this->jigsaw->getSourcePath());

        // gets all the collection data from the temporary location
        $collectionData = $this->dataLoader->loadCollectionData($siteData, $this->jigsaw->getSourcePath());

        $jigsaw->getSiteData()->addCollectionData($collectionData);
    }
}
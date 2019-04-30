<?php

namespace App\Listeners;

use Illuminate\Support\Collection;
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
    public function handle(Jigsaw $jigsaw)
    {
        $this->jigsaw = $jigsaw;

        $this->configureTagCollection();
        $this->loadTagCollection();
    }

    protected function configureTagCollection()
    {
        $tags = $this->extractTagsFrom('posts');
        $tagCollectionConfiguration = $this->createCollectionConfiguration($tags);

        $this->jigsaw->app->config->get('collections')
            ->put('tags', $tagCollectionConfiguration['tags']);
    }

    protected function loadTagCollection()
    {
        $siteData = $this->dataLoader->loadSiteData($this->jigsaw->app->config);
        $this->remoteItemLoader->write($siteData->collections, $this->jigsaw->getSourcePath());
        $collectionData = $this->dataLoader->loadCollectionData($siteData, $this->jigsaw->getSourcePath());
        $this->jigsaw->getSiteData()->addCollectionData($collectionData);
    }

    protected function extractTagsFrom(string $collectionName): Collection
    {
        return $this->jigsaw->getCollection($collectionName)
            ->flatMap // flatten the collection
            ->tags // load all tags from all items
            ->unique() // we only want unique tags
            ->values(); // reset keys in the array
    }

    protected function createCollectionConfiguration(Collection $tags): Collection
    {
        return collect([
            'tags' => [
                'extends' => '_layouts.tag', // the builder needs to know how what template to use
                'section' => 'tag', // otherwise it defaults to 'content' and interferes with the master template
                'path' => 'blog/tags/{tag}',
                'items' => $tags->map(function ($tag) {
                    return [
                        'tag' => $tag,
                        'title' => $tag,
                    ];
                })
            ]
        ]);
    }
}
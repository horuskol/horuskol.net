---
extends: _layouts.post
title: Tagging blog posts in Jigsaw (part 2)
author: Stuart Jones
date: 2019-04-09
section: post
tags: [development,jigsaw,php]
image: https://horuskol.net/assets/images/Toicon-icon-avocado-tag.svg.png
description: Using the Jigsaw lifecycle to generate tag pages
---

![mail tag](/assets/images/Toicon-icon-avocado-tag.svg)

In [my last post](/blog/2019-04-09/tagging-blog-posts-in-jigsaw-part-2) I outlined a couple of fairly simple ways
of introducing tags to posts using [Jigsaw](http://jigsaw.tighten.co/). This post is about using the Jigsaw 
[lifecycle](https://jigsaw.tighten.co/docs/event-listeners/) to put a bit more automation in.

When looking for how to do this, there didn't seem to be a lot of other writeups. The best one I could find is by 
[Nenad Živanović](https://nenadzivanovic.in.rs/blog/2018/08/30/jigsaw-tags-and-archives/) (bonus points: Nenad refers
to an older blog post by [Alan Holmes](https://www.aeholmes.co.uk/posts/2017/07/09/tags-with-jigsaw/) that uses 
an approach similar to what I wrote last week). I was able to work a solution from Nenad's post into my site, but I think 
the article made a few assumptions and missed a couple of explanation, so here's my take on it.

## Jigsaw events

Without completely repeating the official documentation - there are three events during site generation that we can 
hook into and affect the build:

* `beforeBuild` - this lets you add in additional configuration, or preprocess your source files/data;
* `afterCollections` - this lets you work on the mapped/parsed information before the actual build step;
* `afterBuild` - this lets you use with the generated output for any postprocessing.

Where does tagging posts fit in?

The tags we want are listed in the front matter of each of our blog posts - and this information is available after the
`afterCollections` has been fired. We also want to generate the tag pages as part of the build so, obviously, we want 
to hook into the `afterCollections` event and create a new collection for Jigsaw to build from.

This is where it gets a bit sticky - we need to define a configuration for the tag collection, but we don't know what
tags we have until after we've parsed and extracted that information from the pages. Luckily, there is a way we can 
create additional configuration and then create temporary source files that can then be parsed.

## Remote collections

[Remote collections](https://jigsaw.tighten.co/docs/collections-remote-collections/) allow you to pull in content/data
from external source into a collection for generation. Alternatively, you can use a simple static list in the 
configuration file. Either way, Jigsaw will build a set of temporary source files after the `beforeBuild` event, so it
can parse them along with everything else to generate the collections used in the final build step. This is what we'll
take advantage of to get our tag pages.

## Get on with it

### Create a listener

In `app\Listeners`, create a listener class:

```php
<?php

namespace App\Listeners;

use TightenCo\Jigsaw\Jigsaw;
use TightenCo\Jigsaw\Loaders\CollectionRemoteItemLoader;
use TightenCo\Jigsaw\Loaders\DataLoader;

class AddTagIndexes
{
/**
 * @var DataLoader Used by Jigsaw to load the site data from the configuration
 */
    protected $dataLoader;
    
/**
 * @var CollectionRemoteItemLoader Used by Jigsaw to load remote collection data
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
    }
}
```

If you haven't already, you should add a PSR-4 namespace to your composer configuration so you can autoload the class:

```json
{
    ...
    
    "autoload": {
        "psr-4": {
            "App\\": "app/"
        }
    },
    
    ...
}
```

### Bootstrap

In the bootstrap file, you'll need to first bind our listener so we can inject the DataLoader and 
CollectionRemoteItemLoader. Then you can hook the listener to the afterCollections event.

```php
<?php

use App\Listeners\AddTagIndexes;
use TightenCo\Jigsaw\Jigsaw;
use TightenCo\Jigsaw\Loaders\DataLoader;
use TightenCo\Jigsaw\Loaders\CollectionRemoteItemLoader;

$container->bind(AddTagIndexes::class, function ($c) {
    return new AddTagIndexes($c[DataLoader::class], $c[CollectionRemoteItemLoader::class]);
});

$events->afterCollections(function (Jigsaw $jigsaw) use ($container) {
    $container->make(AddTagIndexes::class)->handle($jigsaw);
});
```

So, now we have a listener which is called at the appropriate time in the lifecycle and...

...does nothing.

## The final pieces

In the listener, we have an empty handler:

```php
    public function handle(Jigsaw $jigsaw)
    {
        $this->jigsaw = $jigsaw;
    }
```

There are a couple of steps we need to undertake:

* Configure the tag collection
* Load the tag collection as if it's a remote collection

### Configuring the tag collection

```php
    protected function configureTagCollection()
    {
        $tags = $this->extractTagsFrom('posts');
        $tagCollectionConfiguration = $this->createCollectionConfiguration($tags);

        $this->jigsaw->app->config->get('collections')
            ->put('tags', $tagCollectionConfiguration['tags']);
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
```

### Loading the tag collection

```php
    protected function loadTagCollection()
    {
        $siteData = $this->dataLoader->loadSiteData($this->jigsaw->app->config);
        $this->remoteItemLoader->write($siteData->collections, $this->jigsaw->getSourcePath());
        $collectionData = $this->dataLoader->loadCollectionData($siteData, $this->jigsaw->getSourcePath());
        $this->jigsaw->getSiteData()->addCollectionData($collectionData);
    }
```

### Complete the handler

```php
    public function handle(Jigsaw $jigsaw)
    {
        $this->jigsaw = $jigsaw;

        $this->configureTagCollection();
        $this->loadTagCollection();
    }
```

## And we're done

Almost... if you've followed on from last weeks post, you will need to remove the tags collection in the
`config.php` file.

However, you should be able to use the same `getPostsByTag` helper and layout template that we used there.
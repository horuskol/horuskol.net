---
extends: _layouts.post
title: Tagging blog posts in Jigsaw
author: Stuart Jones
date: 2019-04-02
section: post
tags: [development,jigsaw,php]
---

![mail tag](/assets/images/Toicon-icon-avocado-tag.svg)

I've started setting aside a small part of each day to write. I'm hoping that something will eventually stick, and I'll
be posting more regularly. Before that, I wanted to be able to tag my blog posts.

## Why bother tagging?

It makes it easier for users to look at similar blog posts by clicking on the tags at the bottom of the page. It's also
good for a little bit (teeny bit) of SEO. There's also my innate need to organise.

## It shouldn't be that hard, should it?

It isn't. However, this is my first static built site using [Jigsaw](http://jigsaw.tighten.co/). I'm used to being able
to do this using a model-view-controller framework, and query a database, and all that.

Actually, it can be absurdly easy in a static site, if you don't mind a bit of manual labour whenever you add a new
blog post you want to tag - but I decided I wanted to be a little bit cleverer. This page shows the simplest way, and
then a way that brings in a little automation.

## The most simple solution

First up, you will need to add a new collection to you `config.php`:

```php
<?php

return [
    ...

    'tags' => [
        'path' => 'tag/{filename}',    
    ],

    ...
];
```

In the Jigsaw source directory, you can simply create a markdown file for each tag, and add the page links you want to
tag into the relevant file. Something like this:

```markdown
---
extends: _layouts.tag
tag: php
---
* [Tagging blog posts in Jigsaw](/blog/2019-04-02/tagging-blog-posts-in-jigsaw)
```

This is very likely to get old... very quickly. Especially as, if you want something with nice formatting and text,
you're going to have to do more copy/paste and edit.

```markdown
---
extends: _layouts.tag
tag: php
---
[Tagging blog posts in Jigsaw](/blog/2019-04-02/tagging-blog-posts-in-jigsaw)

April 2, 2019

I've started setting aside a small part of each day to write. I'm hoping that something will eventually stick, and 
I'll be posting more regularly. Before... [continue reading](/blog/2019-04-02/tagging-blog-posts-in-jigsaw)

---
```

## Getting Jigsaw to do some of the work

You're still going to have to manually create a file for the tag, but you only need to put the front matter 
(the YAML block) at the top - we're going to get Jigsaw to generate the list of posts.

Firstly, we need to add a helper to the site's `config.php` file:

```php
<?php

return [
    ...
    
    'getPostsByTag' => function ($page, $posts) {
        return $posts->filter(function ($post) use ($page) {
            return in_array($page->tag, $post->tags ?? []);
        });
    },
    
    ...
];
```

Then, in your `_layouts/tag.blade.php` (or wherever), you can use this helper to get only the posts that have that tag, 
and create a list of posts:

```html
@verbatim
@foreach ($page->getPostsByTag($posts) as $post)
    <div class="border-grey border-b-2">
        <h2 class="pt-8 pb-4">
            <a href="{{ $post->getPath() }}" class="no-underline text-blue-dark hover:text-blue-darker">{{ $post->title }}</a>
        </h2>
        <p class="pb-4">
            {{ date("F j, Y", $post->date) }}
        </p>
        <p class="pb-4">
            {{ $post->excerpt() }}... 
            <a href="{{ $post->getPath() }}" class="text-blue-dark hover:text-blue-darker no-underline font-bold">continue reading</a>
        </p>
    </div>
@endforeach
@endverbatim
```

You should also add `'tags' => [],` to your posts collection configuration in `config.php` as a default (especially if
you have a lot of old posts that you don't want to go back and add in the tags in each post's front matter).

So, now you don't have to manually add a link and excerpt for every post into every tag file you want it listed in.

It still feels a little clunky - you still have to add a file for every tag you want to use so that Jigsaw can 
generate it. If you forget to do this, then you might have a link for the new tag at the bottom of your post, and then
the user will get a 404 instead of a list of related posts.

One advantage for having the tag file is that you could put some preamble in there, describing the tag, which you can
then include into the template for generation before you list the tagged posts.

## Next

This post seems long enough - and there's a lot going on with the solution I ended up going with, so that will go
into a part two.
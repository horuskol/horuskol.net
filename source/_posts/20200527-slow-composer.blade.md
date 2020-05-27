---
extends: _layouts.post
title: Some tips to help speed up Composer install/update
author: Stuart Jones
date: 2020-05-27
section: post
tags: [php]
image: https://horuskol.net/assets/images/posts-20200522-slow-composer-tortoise.jpg
description: Composer taking a while to install/update? It's either time to go and make a coffee, or try these tips to speed it up again.
---

<figure>
<img src="/assets/images/posts/20200522-slow-composer-tortoise-and-hare.jpg" alt="Stone tortoise winning a race against a stone hare">
<figcaption>Slow and steady may win the race, but is it worth the wait?</figcaption>
</figure>

[Composer] has had a great impact on PHP development - while it wasn't the first package/dependency manager for PHP, it has become the mainstay since it was released 8 years ago. When used regularly, it can take away the pain of dealing with intersecting dependencies of dependencies, and do it pretty quickly.

That's not to say that it isn't without its frustrations, though. Perhaps the biggest one I have experienced (and have heard other developers gripe about from time to time) is when it starts to churn away when you're updating your project's dependencies - especially when you've become accustomed to its normally speedy process. Sometimes it just seems to stop completely - or at least get stuck long enough for you to drink the coffee you went to make while waiting for it to be done.

Here's a few things you can do to help if you should ever find yourself thinking about making a second cup of coffee during a composer update.

## Keep Composer up to date

Simple things first - Composer is updated fairly regularly, and these updates can often include code that optimises the resolution and download of dependencies.

`composer self-update`

## Tighten up your project dependencies specifications

As projects get older, dependencies (hopefully) get updated. You might have started the project with `"laravel/framework": "^6.1"` in your `composer.json` file, but that was months ago, and the framework is currently at `v6.18.15`.

<figure>
<img src="/assets/images/posts/20200522-slow-composer-phpstorm-version.png" alt="composer.json file viewed using PHPStorm">
<figcaption>Looks like I can bump the version constraint for Jigsaw</figcaption>
</figure>

By leaving your old version constraints in your composer.json file, you're potentially asking Composer to check on every version between now and then, of every package you've required into your project. Then it has to check on every version of each package they depend on.

So, it's a good idea to update your constraints every once in a while. You can see what versions are currently installed with `composer info -D`, alternatively, some editors (such as PHPStorm) will display that information when you open the file for editing.

## Go nuclear

Clear your cache, remove the vendor directory and lockfile, and do a composer update.

```
composer cc
rm -r composer.lock vendor
composer update
```

## Speed up the download

By default, Composer will download everything serially. This can end up being slow if you're creating or cloning a project and running Composer for the first time, even though Composer does cache package data.

[prestissimo] is a plugin for Composer that enables parallel downloads.

Generally, though, my experience has been that most of the time Composer is slow during the resolution phase rather than when it is downloading.

## Get under the hood

This is not so much a way to speed up Composer, but it is important to be able to see what is going on and diagnosing any issues you might encounter with Composer.

`composer update -vvv` (three v's) will tell Composer to be extra verbose - and you will see everything it's doing. This will help you pick up if Composer is spinning its wheels trying to resolve a dependency or has encountered some other problem. 

## The future

[Composer 2] is around the corner, and will be bringing a bunch of improvements.

[Composer]: https://getcomposer.org/ (Official Composer website)
[prestissimo]: https://github.com/hirak/prestissimo (prestissimo plugin on GitHub)
[Composer 2]: https://php.watch/articles/composer-2 (Composer 2: What's new and changed - PHP.Watch)
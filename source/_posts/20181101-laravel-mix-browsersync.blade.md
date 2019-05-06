---
extends: _layouts.post
title: Laravel Mix and BrowserSync
author: Stuart Jones
date: 2018-11-01
section: post
tags: [browsersync,laravel,laravel-mix]
---

To be honest, I hadn't really bothered with [BrowserSync](https://browsersync.io/) before I'd started using Jigsaw a couple of weeks ago. But I got a taste for it. It's nice to see your page just refresh as soon as you've saved some code, and immediately see the effect. It's really nice that you can do this with more than one device at a time, and each time you navigate or submit a form in one browser, all the others currently synchronised will follow that action.

So, I decided that I wanted me some of that same instant ~~gratification~~ feedback as I'm working on my Laravel projects. And, BrowserSync is included with the [framework](https://laravel.com/docs/5.7/mix#browsersync-reloading). Wonderful! But not quite as simple as I'd hoped.

I added the configuration as documented:

```javascript
mix
    .sass('resources/sass/app.scss', 'public/css')
    .js('resources/js/app.js', 'public/js')
    // other stuff
    .browserSync();
```

I ran `npm run watch`, and the tab opened at `localhost:3000` as expected... but it kept spinning and spinning. So I opened `localhost:3001` to get the BrowserSync user interface - and noticed that it was proxying for `http://app.test`. (Jigsaw, on the other hand, simply loads from local files - but they're statically built and so don't need parsing by a server running PHP).

Because I'm developing on Linux, and just want to get things done, I've been using `php artisan serve` to browse my local development environment - and just using the default settings. You can simply provide an alternative proxy when you call BrowserSync from the mix webpack script:

```javascript
mix
    .sass('resources/sass/app.scss', 'public/css')
    .js('resources/js/app.js', 'public/js')
    // other stuff
    .browserSync({ proxy: 'localhost:8000' });
```

Re-run `npm run watch` BrowserSync giving you access to the app via `artisan serve`.

## Security warning...

If you've done this - you've now made it possible for anyone to hit your development machine on port 3000 and takeover your current session into your app. So, make sure that you have a decent firewall and control what devices can connect to that port. Don't have any confidential information in your development database. And really don't do this with a production site.
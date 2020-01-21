---
extends: _layouts.post
title: Save some keystrokes with this simple trick
author: Stuart Jones
date: 2020-01-19
section: post
tags: [php,quick-tip]
image: https://horuskol.net/assets/images/posts/20200121-shock.jpg
description: Fed up prefixing PHP command line scripts with vendor/bin/ ? - have I go the solution for you.
---

I really wish I could remember who posted this on Twitter some time last year. It actually took me a while to get
around to giving it a go, and by then I couldn't remember the actual solution (just what the outcome was) or the 
person who I'd seen suggesting it.

Some packages installed with Composer come with command line tools (like PHPUnit or PHPStan). These tools are run from
your project directory through `vendor/bin/phpunit` or `vendor/bin/phpstan`.

Did you know that could get rid of the `vendor/bin/` bit?

How?

Find your shell profile or configuration (I'm running bash on Ubuntu, so I opened up `~/.bashrc`) and open it in an
editor. At the end of the file add:

```bash
export PATH="$PATH:./vendor/bin"
```

Save the file and start a new terminal session (or enter  
`export PATH="$PATH:./vendor/bin"` in your command line if you don't want to close your current session).

Now, no matter what project you're in, any composer installed command line script for that project will be available 
directly from your project's path. Bye-bye `vendor/bin/`.
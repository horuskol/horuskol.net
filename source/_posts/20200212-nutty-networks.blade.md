---
extends: _layouts.post
title: Nutty network issues - a tale from the past
author: Stuart Jones
date: 2020-02-12
section: post
tags: [tales]
image: https://horuskol.net/assets/images/posts/20200211-acorn.png
description: Sometimes you just cannot predict the root cause of an issue.
---

<figure>
<img src="/assets/images/posts/20200211-acorn.png" alt="an acorn">
</figure>

My first job out of university was as a Technical Support Engineer for a HVAC (Heating, ventilation, and air 
conditioning) and building controls company. My remit was to support our company engineers and electricians, as well
as troubleshoot client problems.

One particular client had quite a few problems. It was a major hospital at the other end of the country, and they
were progressively upgrading the control systems across their site. The hospital was huge - over a kilometre across - 
and old. The distance between buildings meant that our regular networking and management solutions wouldn't work.
Luckily, we'd just started selling new control stations and software that could run over Ethernet/IP.

Of course, it wasn't that simple. While the entire site was networked, we learnt that some of the buildings being 
upgraded were still using a [token ring][token ring on Wikipedia] network internally. We were able to source some 
ethernet to token ring converters, but they proved to be a bit flaky. The client's solution to this was to have
one of their staff walk around to each building once a week and switch the converters off and on again.

A few months after the installation, the client called our office reporting that one of the buildings wouldn't 
reconnect anymore. The client had already done a fair bit of troubleshooting, including swapping the converter, and
so on. Rather than try and diagnose remotely, I was sent to investigate.

Sure enough, everything seemed fine, except for the connection from the building back to the main control room for the
site. After a couple of hours isolating the issue, it was almost certainly a problem with that connection, and nothing
wrong between our equipment or the converter to the outgoing connection in the building, or the incoming connection at 
the main control room.

The client's IT came and checked the network cable for that building. Remember, the site is large, and composed of 
multiple closely neighbouring buildings. To save money and construction on digging trenches between some buildings, 
some cables were strung between them instead (in ducted conduits).

And that's when I found out that squirrels like to chew on network cables.

## More nutty network issues

Funnily enough, I came across this tweet yesterday after I'd already decided to write this particular post:

<blockquote class="twitter-tweet" data-theme="dark">
  <p lang="en" dir="ltr">
    This is a wireless antenna in California. Network coverage was disrupted by an Acorn woodpecker,
    a 3 ounce bird stashing an estimated 35-50 gallons/300lbs of acorns.
    <a href="https://t.co/QYdp6ShxXZ">pic.twitter.com/QYdp6ShxXZ</a>
  </p>&mdash; Science girl (@gunsnrosesgirl3)
  <a href="https://twitter.com/gunsnrosesgirl3/status/1226490886151954435?ref_src=twsrc%5Etfw">February 9, 2020</a>
</blockquote>
<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

[token ring on Wikipedia]: (https://en.wikipedia.org/wiki/Token_ring)
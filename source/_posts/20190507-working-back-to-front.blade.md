---
extends: _layouts.post
title: Working back to front
author: Stuart Jones
date: 2019-05-07
section: post
tags: [development,viewcrafter]
image: 
description: Working from the backend first might just be working back to front
---

<figure>
<img src="/assets/images/posts/20190507-working-back-to-front--cart-before-horse.jpg" alt="">
<figcaption>Cart before the horse</figcaption>
<p><a href="http://bestandworstever.blogspot.com/2013/01/worst-thing-to-put-before-horse-ever.html">Best & Worst Ever Photo Blog</a></p>
</figure>

I think I began working on [ViewCrafter](https://viewcrafter.com/) about July last year. There wasn't much
traction at first - I hadn't yet defined exactly what needs I wanted the product to fulfil, and I was also
looking around what tools and frameworks I would use to build it out.

Then I had a long holiday - taking my wife to Paris, and visiting family and friends in the UK.

After we got back in September, I'd settled on using [Laravel Spark](https://spark.laravel.com/) to provide the 
backend, and to help deal with all the messiness of having a multi-tenancy team subscription based app. This then 
guided me into adopting the [Vue.js](https://vuejs.org/) framework for the front end - Spark used Vue, and the 
framework is generally popular with the PHP Australia community, so I had a ready venue for questions and discussions.

I started to feel my way around Spark and Vue, and began building out my API - figuring out model relationships;
testing behaviour and features; ensuring security.

But I was still spinning my wheels, poking about to get a deeper understanding of Laravel and Vue - and I was still 
trying to find the precise itch that ViewCrafter should be scratching.

## LIFTOFF!

At the end of October, I attended [Laracon AU](https://laracon.com.au/). As well as seeing a bunch of excellent
presentations, I started to talk to other developers about what I was working on. I got some welcome and encouraging 
feedback. What I got more of was _energy_. I flew back Friday night (and as luck would have it, Qantas had a 
[documentary about Aussie startups](https://www.thenewhustlemovie.com/) on the in-flight entertainment - talking to 
the founders of Canva, SafetyCulture, and Vinomofo - just to pile on the inspiration) - and woke up early Saturday 
morning, and began working.

By February, I'd built out a fairly extensive API. I'd figured out how to use Vue and [Vuex](https://vuex.vuejs.org/) 
(the state management library for Vue), and how to work the information flow from front to back. I felt like I was 
making great progress there, and having gotten the architecture right, I began to work on the user interface - 
especially since I'd been using the Spark forms and styles to quickly prototype the app, and I wanted something 
more distinct and less form-driven.

## Crash and burn

I'd fallen into a trap.

I'd produced a form-driven tool that worked really well with the backend I'd developed, but which didn't really
promise to be a great user experience. I'd also found that I'd painted myself into
an almost literal corner - any improvements to the UI seemed to need me to make changes from four different directions
in my code - especially with the interplay of Vue, Vuex, Spark, and the existing HTML and styles.

I was stuck.

Over March, I realised what my mistake had been over the last four months or so. The user interfaces for so many of
the projects I'd been working on over the past few years were incredibly simple. We also had a small product group 
which included myself, a designer, and a product owner who was also in charge of QA. This meant that me (and my 
development team) could focus on working from the backend because we knew exactly where we had to get to to make it
work with the frontend.

My mistake was doing all of this backend work without spending anywhere near enough time figuring out the frontend. I
didn't know where I needed my API and backend to be to support a useful front end, and had gotten myself lost down
a dark path. I had been thinking __back to front__.

## Working front to back again

What did I do next?

I began afresh again.

Sounds crazy, right? _"But you've already done so much work!"_ 

Sometimes, though, you have to admit it was the wrong direction (and avoid the sunk cost fallacy). I archived all 
of the work I'd done so far to provide a reference point - a lot of the basic architecture (models, controllers, 
validation, and tests) would still be very useful, and was the product of much learning.

By taking a front to back approach, and by considering how the user will work in the app, I'm finding the itches I
want to scratch. In only a month, I've already got a much better user interface and design going, and I'm filling in 
the API to support it (mostly by copying in from the first draft - but with changes to support the new direction).

Before, whenever someone asked _"how's it going? when is it going to be ready?"_, I'd reply evasively _"oh, 
it's going. Maybe in the next few months"_. Now, though, I'm looking at inviting a closed beta started next month. 
I'm clearer on what I want in the product to be able to do in that beta, and also how it will work - both in front and 
back.

## The lesson

Sometimes - it pays off to realise that your thinking might be a little too back to front - and that starting fresh
might just get you moving quicker.
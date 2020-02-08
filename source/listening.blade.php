@extends('_layouts.master', ['page' => (object) ['title' => 'Listening']])

@section('content')
    <h1 class="font-bold text-2xl pt-8 pb-4">What podcasts am I listening to?</h1>

    <dl class="pb-4 leading-normal">
        <dt class="font-semibold text-lg pb-2">
            <a class="text-blue-500 hover:text-blue-700 underline" href="https://artofproductpodcast.com/">
                The Art of Product Podcast
            </a>
        </dt>
        <dd class="italic pb-2">
            The Art of Product is a podcast chronicling the journeys of two entrepreneurs building software companies.
            Hosted by Ben Orenstein and Derrick Reimer.
        </dd>
        <dd class="pb-2">
            20-30 minutes.
        </dd>

        <dt class="font-semibold text-lg border-t border-gray-700 mt-4 pt-4 pb-2">
            <a class="text-blue-500 hover:text-blue-700 underline" href="https://basecodefieldguide.com/podcast/">
                The BaseCode Podcast
            </a>
        </dt>
        <dd class="italic pb-2">
            A podcast of development related topics hosted from opposite sides of the planet.
        </dd>
        <dd class="pb-2">
            20-30 minutes.
        </dd>

        <dt class="font-semibold text-lg border-t border-gray-700 mt-4 pt-4 pb-2">
            <a class="text-blue-500 hover:text-blue-700 underline" href="https://www.bikeshed.fm/">
                The Bike Shed
            </a>
        </dt>
        <dd class="italic pb-2">
            On The Bike Shed, hosts Chris Toomey & Steph Viccari discuss their development experience and challenges
            at thoughtbot with Ruby, Rails, JavaScript, and whatever else is drawing their attention, admiration, or
            ire this week.
        </dd>
        <dd class="pb-2">
            30-60 minutes.
        </dd>

        <dt class="font-semibold text-lg border-t border-gray-700 mt-4 pt-4 pb-2">
            <a class="text-blue-500 hover:text-blue-700 underline" href="https://www.brightandearlypodcast.com/">
                Bright & Early
            </a>
        </dt>
        <dd class="italic pb-2">
            A podcast for people building early-stage startups.
        </dd>
        <dd class="italic pb-2">
            Learn from SaaS founders, marketers, product people, and designers as they share their lessons learned
            from the early days.
        </dd>
        <dd class="pb-2">
            50-60 minutes.
        </dd>

        <dt class="font-semibold text-lg border-t border-gray-700 mt-4 pt-4 pb-2">
            <a class="text-blue-500 hover:text-blue-700 underline" href="https://saas.transistor.fm/">
                Build Your SaaS
            </a>
        </dt>
        <dd class="italic pb-2">
            What does it take to build a SaaS in 2020?
        </dd>
        <dd class="italic pb-2">
            Follow Jon and Justin as they grow Transistor.fm.
        </dd>
        <dd class="pb-2">
            30-60 minutes.
        </dd>

        <dt class="font-semibold text-lg border-t border-gray-700 mt-4 pt-4 pb-2">
            <a class="text-blue-500 hover:text-blue-700 underline" href="https://www.founderquestpodcast.com/">
                Founder Quest
            </a>
        </dt>
        <dd class="italic pb-2">
            Three devs building a business on our own terms.
        </dd>
        <dd class="pb-2">
            15-60 minutes.
        </dd>

        <dt class="font-semibold text-lg border-t border-gray-700 mt-4 pt-4 pb-2">
            <a class="text-blue-500 hover:text-blue-700 underline" href="http://www.fullstackradio.com/">
                Full Stack Radio
            </a>
        </dt>
        <dd class="italic pb-2">
            A podcast for developers interested in building great software products. Every episode, Adam Wathan is
            joined by a guest to talk about everything from product design and user experience to unit testing and
            system administration.
        </dd>
        <dd class="pb-2">
            Anywhere from 60 to 90 minutes of in-depth conversation.
        </dd>

        <dt class="font-semibold text-lg border-t border-gray-700 mt-4 pt-4 pb-2">
            <a class="text-blue-500 hover:text-blue-700 underline" href="https://www.indiehackers.com/podcast">
                The Indie Hackers Podcast
            </a>
        </dt>
        <dd class="italic pb-2">
            Raw conversations with the founders behind profitable online businesses.
        </dd>
        <dd class="pb-2">
            60 minutes.
        </dd>

        <dt class="font-semibold text-lg border-t border-gray-700 mt-4 pt-4 pb-2">
            <a class="text-blue-500 hover:text-blue-700 underline" href="https://laravel-news.com/category/podcast">
                Laravel News Podcast
            </a>
        </dt>
        <dd class="pb-2">
            A quick run through of new releases and packages for Laravel.
        </dd>
        <dd class="pb-2">
            30-45 minutes.
        </dd>


        <dt class="font-semibold text-lg border-t border-gray-700 mt-4 pt-4 pb-2">
            <a class="text-blue-500 hover:text-blue-700 underline" href="https://noplanstomerge.simplecast.com/">
                No Plans to Merge
            </a>
        </dt>
        <dd class="italic pb-2">
            Real life code talk between two working developers.
        </dd>
        <dd class="pb-2">
            A little more than 60 minutes most weeks - covers PHP and JavaScript and development in general.
        </dd>

        <dt class="font-semibold text-lg border-t border-gray-700 mt-4 pt-4 pb-2">
            <a class="text-blue-500 hover:text-blue-700 underline" href="https://www.northmeetssouth.audio/">
                North Meets South
            </a>
        </dt>
        <dd class="italic pb-2">
            Jacob Bennett and Michael Dyrynda conquer a 14.5 hour time difference to talk about life as web developers
        </dd>
        <dd class="pb-2">
            50-60 minutes.
        </dd>

        <dt class="font-semibold text-lg border-t border-gray-700 mt-4 pt-4 pb-2">
            <a class="text-blue-500 hover:text-blue-700 underline" href="https://phpugly.simplecast.com/">
                PHPUgly
            </a>
        </dt>
        <dt class="italic pb-2">
            The podcast your mother warned you about. Ramblings of a few overworked PHP Developers. We discuss
            everything, from the challenges and excitement of running our small business and development shop the
            DiegoDev Group, to general day to day coding projects, to anything geek related or any other tech topics.
        </dt>
        <dt class="pb-2">
            60 minutes.
        </dt>

        <dt class="font-semibold text-lg border-t border-gray-700 mt-4 pt-4 pb-2">
            <a class="text-blue-500 hover:text-blue-700 underline" href="https://www.stitcher.com/podcast/brian-rhea-benedikt-deicke/slow-steady">
                Slow & Steady
            </a>
        </dt>
        <dd class="italic pb-2">
            Join us as we share what it's like to build and launch a bootstrapped startup while working for yourself
            full-time. Benedikt is working on Userlist with two other co-founders and Brian is running solo on a
            product to combat loneliness on remote teams.
        </dd>
        <dd class="pb-2">
            30-40 minutes.
        </dd>

        <dt class="font-semibold text-lg border-t border-gray-700 mt-4 pt-4 pb-2">
            <a class="text-blue-500 hover:text-blue-700 underline" href="https://softskills.audio/">
                Soft Skills Engineering
            </a>
        </dt>
        <dd class="italic pb-2">
            It takes more than great code to be a great engineer.
        </dd>
        <dd class="pb-2">
            30 minutes.
        </dd>

        <dt class="font-semibold text-lg border-t border-gray-700 mt-4 pt-4 pb-2">
            <a class="text-blue-500 hover:text-blue-700 underline" href="https://syntax.fm/">
                Syntax.
            </a>
        </dt>
        <dd class="italic pb-2">
            A Tasty Treats Podcast for Web Developers.
        </dd>
        <dd class="pb-2">
            30-60 minutes. Twice a week.
        </dd>

        <dt class="font-semibold text-lg border-t border-gray-700 mt-4 pt-4 pb-2">
            <a class="text-blue-500 hover:text-blue-700 underline" href="https://testandcode.com/">
                Test & Code
            </a>
        </dt>
        <dd class="italic pb-2">
            The show covers a wide array of topics including software engineering, development, testing, Python
            programming, and many related topics.
        </dd>
        <dd class="pb-2">
            15-30 minutes.
        </dd>

        <dt class="font-semibold text-lg border-t border-gray-700 mt-4 pt-4 pb-2">
            <a class="text-blue-500 hover:text-blue-700 underline" href="http://thewestwingweekly.com/">
                The West Wing Weekly
            </a>
        </dt>
        <dd class="pb-2">
           Unfortunately, this podcast has just run its course - but I've already listened through twice.
        </dd>
        <dd class="pb-2">
            Listen to a fan and a cast member talk about episodes, and talk to other cast and crew.
        </dd>
    </dl>
@endsection
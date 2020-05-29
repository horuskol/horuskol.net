@extends('_layouts.master', ['page' => (object) ['title' => 'Listening']])

@section('content')
    <h1 class="font-bold text-2xl pt-8 pb-4">What podcasts am I listening to?</h1>

    <dl class="pb-4 leading-normal">
        <dt class="font-semibold text-lg pb-2">
            <a class="text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline" href="https://apisyouwonthate.com/podcast">
                APIs you won't hate
            </a>
        </dt>
        <dd class="italic pb-2">
            Rather sporadic discussions between Phil Sturgeon, Mike Bifulco, and Matt Trask, covering the world of API development.
        </dd>
        <dd class="pb-2">
            30-45 minutes.
        </dd>

        <dt class="font-semibold text-lg border-t border-gray-700 mt-4 pt-4 pb-2">
            <a class="text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline" href="https://artofproductpodcast.com/">
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
            <a class="text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline" href="https://www.thebritishhistorypodcast.com/">
                The British History Podcast
            </a>
        </dt>
        <dd class="italic pb-2">
            The BHP is a chronological retelling of the history of Britain with a particular focus upon the lives of the people. You won’t find a dry recounting of dates and battles here, but instead you’ll learn about who these people were and how their desires, fears, and flaws shaped the scope of this island at the edge of the world.
        </dd>
        <dd class="pb-2">
            20-30 minutes.
        </dd>

        <dt class="font-semibold text-lg border-t border-gray-700 mt-4 pt-4 pb-2">
            <a class="text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline" href="https://basecodefieldguide.com/podcast/">
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
            <a class="text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline" href="https://www.bikeshed.fm/">
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
            <a class="text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline" href="https://www.brightandearlypodcast.com/">
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
            <a class="text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline" href="https://saas.transistor.fm/">
                Build Your SaaS
            </a>
        </dt>
        <dd class="italic pb-2">
            What does it take to build a SaaS in 2020?
        </dd>
        <dd class="italic pb-2">
            Follow Jon Buda and Justin Jackson as they grow Transistor.fm.
        </dd>
        <dd class="pb-2">
            30-60 minutes.
        </dd>

        <dt class="font-semibold text-lg border-t border-gray-700 mt-4 pt-4 pb-2">
            <a class="text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline" href="https://pod.link/thecsspodcast">
                The CSS Podcast
            </a>
        </dt>
        <dd class="italic pb-2">
            Cascading Style Sheets (CSS) is the web’s core styling language. For web developers, It’s one of the quickest technologies to get started with, but one of the hardest to master. Follow Una Kravets and Adam Argyle, Developer Advocates from Google, who gleefully breakdown complex aspects of CSS into digestible episodes covering everything from accessibility to z-index.
        </dd>
        <dd class="pb-2">
            Fresh new podcast.
        </dd>

        <dt class="font-semibold text-lg border-t border-gray-700 mt-4 pt-4 pb-2">
            <a class="text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline" href="https://www.stitcher.com/podcast/anchor-podcasts/extremities">
                Extremities
            </a>
        </dt>
        <dd class="italic pb-2">
            Why and how people live in earth's most isolated and extreme settlements.
        </dd>
        <dd class="pb-2">
            20-30 minutes.
        </dd>

        <dt class="font-semibold text-lg border-t border-gray-700 mt-4 pt-4 pb-2">
            <a class="text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline" href="https://www.founderquestpodcast.com/">
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
            <a class="text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline" href="http://www.fullstackradio.com/">
                Full Stack Radio
            </a>
        </dt>
        <dd class="italic pb-2">
            A podcast for developers interested in building great software products. Every episode, Adam Wathan is
            joined by a guest to talk about everything from product design and user experience to unit testing and
            system administration.
        </dd>
        <dd class="pb-2">
            60-90 minutes.
        </dd>

        <dt class="font-semibold text-lg border-t border-gray-700 mt-4 pt-4 pb-2">
            <a class="text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline" href="https://www.indiehackers.com/podcast">
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
            <a class="text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline" href="https://laravelpodcast.com/">
                Laravel News Podcast
            </a>
        </dt>
        <dd class="italic pb-2">
            The Laravel Podcast brings you Laravel and PHP development news and discussion. Season 4 consists of guest interviews by Matt Stauffer talking about a new topic in the Laravel community for each episode.
        </dd>
        <dd class="pb-2">
            45-60 minutes.
        </dd>

        <dt class="font-semibold text-lg border-t border-gray-700 mt-4 pt-4 pb-2">
            <a class="text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline" href="https://laravel-news.com/category/podcast">
                Laravel News Podcast
            </a>
        </dt>
        <dd class="italic pb-2">
            A quick run through of new releases and packages for Laravel.
        </dd>
        <dd class="pb-2">
            30-45 minutes.
        </dd>

        <dt class="font-semibold text-lg border-t border-gray-700 mt-4 pt-4 pb-2">
            <a class="text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline" href="https://www.northmeetssouth.audio/">
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
            <a class="text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline" href="https://outofbeta.fm/">
                Out of Beta
            </a>
        </dt>
        <dt class="italic pb-2">
            Follow our journey as we build and launch two startups as a part of the TinySeed remote accelerator. Hosted by Matt Wensing, founder of Summit, and Peter Suhm, founder of Branch.
        </dt>
        <dt class="pb-2">
            30-40 minutes.
        </dt>

        <dt class="font-semibold text-lg border-t border-gray-700 mt-4 pt-4 pb-2">
            <a class="text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline" href="https://www.stitcher.com/podcast/earwolf/seth-godins-startup-school">
                Seth Godin's Startup School
            </a>
        </dt>
        <dt class="italic pb-2">
            Seth Godin is a thought leader in the marketing and business world. In this rare live recording, hear Seth as he guides thirty entrepreneurs through a workshop exploring how they can build and run their dream business.
        </dt>
        <dt class="pb-2">
            15-40 minutes. 15 episodes.
        </dt>

        <dt class="font-semibold text-lg border-t border-gray-700 mt-4 pt-4 pb-2">
            <a class="text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline" href="https://www.stitcher.com/podcast/brian-rhea-benedikt-deicke/slow-steady">
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
            <a class="text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline" href="https://softskills.audio/">
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
            <a class="text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline" href="https://www.abc.net.au/radionational/programs/sum-of-all-parts/">
                Sum of All Parts
            </a>
        </dt>
        <dd class="italic pb-2">
            Sum of All Parts is an ABC podcast that tells extraordinary stories from the world of numbers.
        </dd>
        <dd class="italic pb-2">
            It's about the amazing and powerful numbers all around us, how they work, and the people who work with them.
        </dd>
        <dd class="pb-2">
            5-30 minutes.
        </dd>

        <dt class="font-semibold text-lg border-t border-gray-700 mt-4 pt-4 pb-2">
            <a class="text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline" href="https://syntax.fm/">
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
            <a class="text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline" href="https://testandcode.com/">
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
            <a class="text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline" href="https://twentypercenttime.simplecast.com/">
                Twenty Percent Time
            </a>
        </dt>
        <dd class="italic pb-2">
            Twenty Percent Time is a podcast for programmers, designers, business owners, & more from the good folks at Tighten.
        </dd>
        <dd class="pb-2">
            20-30 minutes.
        </dd>

        <dt class="font-semibold text-lg border-t border-gray-700 mt-4 pt-4 pb-2">
            <a class="text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline" href="http://thewestwingweekly.com/">
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
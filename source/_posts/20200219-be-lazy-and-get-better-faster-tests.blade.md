---
extends: _layouts.post
title: Laravel database factories for better testing
author: Stuart Jones
date: 2020-02-19
section: post
tags: [php, laravel, database, testing]
image: https://horuskol.net/assets/images/posts/20200219-lazy.jpg
description: A quick look at how Laravel's database factories help with simpler tests, and how being lazy can speed up your tests.
---

Automated tests are awesome. Having repeatable tests on your codebase to warn you if anything trippy has happened 
because of changes you've been making, and thereby helping to preventing the release of buggy code, is a lifesaver.
They're also a bit of a pain sometimes, especially when testing code relying on a framework, since you sometimes need 
to bootstrap that framework as part of your tests.

When you're dealing with testing database interactions, you can experience even more pain. Not only do you have to 
restore state between each test, you also have to insert test data for many tests, which slows down tests and you
can end up with a lot of setup prior to your actual test and assertions.

There are a number of strategies for speeding up testing, such as these 
[general tips][tips to speed up phpunit tests - Laravel News]. Even database testing can be improved with functionality
like [Laravel's RefreshDatabase trait][Under the hood: How RefreshDatabase works in Laravel tests].

## Database factories

Laravel provides [database factories][factories for database testing on Laravel docs], which allow you to easily create 
fully populated data models within your tests, and reduce clutter within the test methods.

Say you have a simple table defined in a migration like this:

```php
Schema::create('schools', function (Blueprint $table) {
    $table->bigIncrements('id');
    $table->string('name');
    $table->timestamps();
});
```

Assuming the data model to be `App\School`, you can create a database factory like:

```php
$factory->define(School::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(),
    ];
});
```

Then in your test, you can write a single line:

```php
$school = factory(School::class)->create();
```

And now you will have a model and a database record which can be manipulated and tested as needed. Even better, because
the factory is using the [Faker library][Faker on GitHub], every school will have have a random name - reducing your
tests' reliance on hardcoded strings and values.

If you want to just create a model without persisting it in the database, you can call the `make` method instead:

```php
$school = factory(School::class)->make();
```

## Related data and factories

Schools run courses, so we're going to create a courses table (and a `Course` model):

```php
Schema::create('courses', function (Blueprint $table) {
    $table->bigIncrements('id');
    $table->bigInteger('school_id')->unsigned();
    $table->string('name');
    $table->timestamps();
});
```

Our `Course` model has the following relationship to the `School` model:

```php
public function school()
{
    return $this->belongsTo(School::class);
}
```

Now, in order for our database factory to create a valid course, we need to set the `school_id` field, since it isn't
nullable (courses _must_ be attached to a school). The easiest, naive way is to do:

```php
$factory->define(Course::class, function (Faker $faker) {
    return [
        'school_id' => 1,
        'name' => $faker->sentence(),
    ];
});
```

Unfortunately, this wouldn't also create the school, which may be important in some tests.

It is possible to inject/override values defined in the factory when using them to create or make models in your test.
So, you could do something like:

```php
$school = factory(School::class)->create();
$course = factory(Course::class)->create([
    'school_id' => $school,
]);
```

<aside>
yes, that `'school_id' => $school,` is correct - the factory knows how to resolve the ID from the school object.
</aside>

In some tests, though, this could get a bit boilerplate, especially with complex/multiple relationships, and that 
boilerplate can get in the way of seeing what the test is doing/testing. Sometimes you can push it up to a setup method 
in the test case, but sometimes I'd rather just create a valid course along with it's relations in just the one line.

To do this, you can call other factories from within a factory:

```php
$factory->define(Course::class, function (Faker $faker) {
    return [
        'school_id' => factory(School::class)->create(),
        'name' => $faker->sentence(),
    ];
});
```

Unfortunately, this brings a couple of problems. The first is that if you simply make a course instead of creating and
persisting it, you will still create a database record for the school. The other problem is that, while you can still 
inject a school from your test, you'll end up with two school records being created (the one you create in your test
and the one being made by the course's database factory which is then overridden).

```php
/**
 * @test
 */
public function that_school_can_be_injected_into_course_factory()
{
    $school = factory(School::class)->create();

    $course = factory(Course::class)->create([
        'school_id' => $school,
    ]);

    // this will pass, since the school is actually overridden
    $this->assertEquals($school->id, $course->school->id, 'school was not overridden');

    // this will fail, since two schools are created
    $this->assertEquals(1, School::all()->count(), 'there are more schools than there should be');
}
```

<aside>the tests in this post are not what you'd normally bother writing - I'm just using them to highlight what's 
happening when you use the factories</aside>

This means there are 3 inserts into the database when there should have only been 2. That extra insert adds a little
bit more time to the test, and that can add up across multiple tests.

Even worse, if you are creating multiple courses in a test:

```php
/**
 * @test
 */
public function that_school_can_be_injected_into_course_factory()
{
    $school = factory(School::class)->create();

    $courses = factory(Course::class, 3)->create([ // create 3 courses
        'school_id' => $school,
    ]);

    // this will pass, since the school is actually overridden
    $this->assertEquals($school->id, $course->school->id, 'school was not overridden');

    // this will fail, since four schools are created
    $this->assertEquals(1, School::all()->count(), 'there are more schools than there should be');
}
```

In this case, 4 schools will have been inserted into the database, when we only wanted 1.

## Fixing things by getting lazy

There's a method available to database factories that isn't covered in the documentation - the `lazy` method.

Basically, this method tells the factory to create a model/record, but only if it has to.

```php
$factory->define(Course::class, function (Faker $faker) {
    return [
        'school_id' => factory(School::class)->lazy(),
        'name' => $faker->sentence(),
    ];
});
```

Using the `lazy` method like this means that we can override the school for the course without creating redundant
data. Both of the following tests will pass when we use this method.

```php
/**
 * @test
 */
public function that_a_school_is_created_by_course_factory()
{
    $course = factory(Course::class)->create();

    $this->assertEquals(1, School::all()->count(), 'there are more schools than there should be');
    $this->assertEquals(School::first()->id, $course->school->id);
}

/**
 * @test
 */
public function that_school_can_be_injected_into_course_factory()
{
    $school = factory(School::class)->create();

    $courses = factory(Course::class, 3)->create([
        'school_id' => $school,
    ]);

    $this->assertEquals($school->id, $courses->first()->school->id, 'school was not injected');
    $this->assertEquals(1, School::all()->count(), 'there are more schools than there should be');
}
```

The limit on the `lazy` method is that it will still create a school and persist it if we don't provide an override, 
even if we don't persist the course.

```php
/**
 * @test
 */
public function that_school_is_not_created_if_course_is_not_persisted()
{
    factory(Course::class)->make();

    $this->assertEquals(0, School::all()->count(), 'there are more schools than there should be');
}
```

I assume the reason is that the only way for the factory to get a valid school ID to inject into the course is for 
there to be an actual school record in the database to provide the auto-generated ID. I don't think that this is
necessarily a requirement - the only time `school_id` must be correct and valid is when we save the course - which
we might not be doing in our test. However, then we would have to have our factory somehow detect that we are saving
the course in order to generate the school record and retrieve an ID.

Something to think about?

## Finishing up

Database factories are very helpful. The `lazy` method, makes it possible to reduce unnecessary database inserts
during the lifetime of tests. As I said, the amount of time saved in a single test is pretty small, but this can add 
up when you have a lot of tests relying on the database.

[tips to speed up phpunit tests - Laravel News]: https://laravel-news.com/tips-to-speed-up-phpunit-tests
[Under the hood: How RefreshDatabase works in Laravel tests]: https://dev.to/daniel_werner/under-the-hood-how-refreshdatabase-works-in-laravel-tests-2728
[factories for database testing on Laravel docs]: https://laravel.com/docs/5.8/database-testing#writing-factories
[Faker on GitHub]: https://github.com/fzaninotto/Faker
---
extends: _layouts.post
title: Laravel 8 database factories for even better testing
author: Stuart Jones
date: 2020-11-12
section: post
tags: [php, laravel, database, testing]
image: https://horuskol.net/assets/images/posts/20201112-laravel-8-database-factories/factory-robots-800.jpg
description: A quick look at how Laravel 8's class-based database factories help with simpler tests, and how being lazy can speed up your tests.
---

Automated tests are awesome. Having repeatable tests on your codebase to warn you if anything trippy has happened because of changes you've been making, and thereby helping to preventing the release of buggy code, is a lifesaver. They're also a bit of a pain sometimes, especially when testing code relying on a framework, since you sometimes need  to bootstrap that framework as part of your tests.

When you're dealing with testing database interactions, you can experience even more pain. Not only do you have to  restore state between each test, you also have to insert test data for many tests, which slows down tests and you can end up with a lot of setup prior to your actual test and assertions.

There are a number of strategies for speeding up testing, such as these [general tips][tips to speed up phpunit tests - Laravel News]. Even database testing can be improved with functionality like [Laravel's RefreshDatabase trait][Under the hood: How RefreshDatabase works in Laravel tests].

## Database factories

<aside>
This post discusses the new class-based database factories introduced in Laravel 8. There is an older post for <a href="/blog/2020-02-19/laravel-database-factories-for-better-testing/" class="text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline">Laravel 7</a> (and earlier) if you want to use factories in older versions.
</aside>

Laravel provides [database factories][factories for database testing on Laravel docs], which allow us to easily create fully populated data models within your tests, and reduce clutter within the test methods.

Say we have a simple table defined in a migration like this:

```php
Schema::create('schools', function (Blueprint $table) {
    $table->bigIncrements('id');
    $table->string('name');
    $table->timestamps();
});
```

We will need to include the `HasFactory` behaviour trait in the relevant model:

```php
namespace App\Models; // assuming the Laravel 8 app structure

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;
}
```

Now we can define the factory in the `database\factories` directory. We can run `php artisan make:factory SchoolFactory` to make the scaffold for us to fill in:

```php
<?php

namespace Database\Factories;

use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

class SchoolFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Color::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence,
        ];
    }
}
```

Then in when we want to use the factory in our tests, we can write a single line:

```php
$school = School::factory()->create();
```

And now there will be a model and a database record which can be manipulated and tested as needed. Even better, because the factory is using the [Faker library][Faker on GitHub], every school we create using this factory will have a random name - removing hardcoded strings and values.

It is also possible to create a model without persisting it in the database by using the `make` method instead:

```php
$school = School::factory()->create();
```

If it is important for a specific string to be tested when creating/making a model with a factory, it is possible to override the data when calling create/make:

```php
$school = School::factory()->create([
    'name' => 'Grange Hill',
]);
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

Now, in order for our database factory to create a valid course, we need to set the `school_id` field, since it isn't nullable (courses _must_ be attached to a school). The easiest, naive way is to do this in the `CourseFactory`:

```php
public function definition()
{
    return [
        'school_id' => 1,
        'name' => $this->faker->sentence(),
    ];
}
```

Unfortunately, this wouldn't also create the school, which may be important in some tests, and cause errors if we try to do something like `$course->school->name` in our code under test.

It is possible to inject/override values defined in the factory when using them to create or make models in our test. So, we can do something like:

```php
$school = School::factory()->create();
$course = Course::factory()->create([
    'school_id' => $school,
]);
```

<aside>
Yes, that <code>'school_id' => $school,</code> is correct - the factory knows how to resolve the ID from the school object.
</aside>

In some tests, though, this could get a bit boilerplate, especially with complex/multiple relationships, and that boilerplate can get in the way of seeing what the test is doing/testing. Sometimes we can push it up to a setup method in the test case, but other times I'd rather just create a valid course along with its relations in just the one line.

To do this, we can call other factories from within a factory:

```php
public function definition()
{
    return [
        'school_id' => School::factory()->create(),
        'name' => $this->faker->sentence(),
    ];
});
```

Unfortunately, this brings a couple of problems. The first is that if we simply make a course instead of creating and persisting it, we will still create a database record for the school. The other problem is that, while we can still inject a school from our test if we want to, we'll end up with two school records being created (the one we create in our test and the one being made by the course's database factory which is then overridden).

```php
/**
 * @test
 */
public function that_school_can_be_injected_into_course_factory()
{
    $school = School::factory()->create();

    $course = Course::factory()->create([
        'school_id' => $school,
    ]);

    // this will pass, since the school is actually overridden
    $this->assertEquals($school->id, $course->school->id, 'school was not overridden');

    // this will fail, since two schools are created
    $this->assertEquals(1, School::all()->count(), 'there are more schools than there should be');
}
```

<aside>The tests in this post are not what we should normally bother writing - I'm just using them to highlight what's happening when we use the factories</aside>

This means there are three inserts into the database when there should have only been two. That extra insert adds a little bit more time to the test, and that can add up across multiple tests.

Even worse, if we are creating multiple courses in a test:

```php
/**
 * @test
 */
public function that_school_can_be_injected_into_course_factory()
{
    $school = School::factory()->create();

    $courses = Course::factory()->count(3)->create([ // create 3 courses
        'school_id' => $school,
    ]);

    // this will pass, since the school is actually overridden
    $this->assertEquals($school->id, $course->school->id, 'school was not overridden');

    // this will fail, since four schools are created
    $this->assertEquals(1, School::all()->count(), 'there are more schools than there should be');
}
```

In this case, four schools will have been inserted into the database, when we only needed one.

[tips to speed up phpunit tests - Laravel News]: https://laravel-news.com/tips-to-speed-up-phpunit-tests
[Under the hood: How RefreshDatabase works in Laravel tests]: https://dev.to/daniel_werner/under-the-hood-how-refreshdatabase-works-in-laravel-tests-2728
[factories for database testing on Laravel docs]: https://laravel.com/docs/8.x/database-testing#writing-factories
[Faker on GitHub]: https://github.com/fzaninotto/Faker
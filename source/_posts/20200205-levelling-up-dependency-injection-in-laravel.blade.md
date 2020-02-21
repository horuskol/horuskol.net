---
extends: _layouts.post
title: Levelling up dependency injection in Laravel
author: Stuart Jones
date: 2020-02-05
section: post
tags: [php,laravel,oop]
image: https://horuskol.net/assets/images/horuskol-ring.png
description: Take dependency injection in Laravel controllers to the next level by using interfaces and the service container.
---

_This is part 2 of a 2-part series. If you haven't already, I'd check out my [previous post], as I gloss over some fundamentals in this post for the sake of brevity_.

At the end of last year, I'd been doing a bit of refactoring on some models and [DRY]ing up some common functionality  into traits. I'd also noticed that I had some fairly repetitive code across some controllers, too, and I wanted to refactor that somehow. This lead me to an interesting question - could I manage this refactoring while still taking advantage of Laravel's dependency injection and service container?

The answer - unsurprisingly - is yes. Luckily for me, as this would be a short blog post if it wasn't.

## The setup (or the Rule of Three)

So, this work is for [ViewCrafter], which is a GUI-driven code tool. A core functionality is building components and layouts. Components and layouts contain elements, and elements contain more elements. So I have three models - Component, Element and Layout - each of which can have child Elements, and the order of the children is important (and modifiable by the user). So the management of children in their parents is a little complicated.

Originally I had two models (Layout and Element), but after solving the basic issues there, I added Component. This is where the [Rule of Three] comes into play. When writing the Layout and Element models, I wrote a lot of duplicate code. Adding the third model meant I now had three models with the (some) of the same code. So I extracted all the common code into a [trait] - `HasChildElements`, which all three models now used.

```php
<?php

declare(strict_types=1);

namespace App\Behaviours;

use App\Element;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasChildElements
{
/**
 * Elements may have children.
 */
    public function children(): MorphMany
    {
        return $this->morphMany(Element::class, 'parent');
    }

/**
 * Insert a child element to this parent.
 *
 * If $beforeUuid is null, then simply append $element to the parent's list of children.
 * Otherwise, insert the new element into the list immediately before the existing element specified by $beforeUuid.
 *
 * @param string|null $beforeUuid
 */
    public function insert(Element $element, $beforeUuid = null): Element
    {
        // does all the necessary to insert an element into this parent.
    }

    

/**
 * Remove an element from the parent.
 */
    public function remove(Element $child): void
    {
        // does all the necessary to delete the element.
    }
}
```

## The controllers

So, the way I'd written my API is that you would insert a new element into a parent using a URL like `POST /layouts/{layoutUuid}/elements`, with some information in the payload about what tag name the new element has, and whether you were inserting the new element into the middle of the parent or just adding to the end of it.

Since you could add an element to any component, layout, or element, this would mean three URLs:

```
POST /components/{componentUuid}/elements
POST /elements/{elementUuid}/elements
POST /layouts/{layoutUuid}/elements
```

But what about the controllers.

Initially, I had `insertElement` actions on each controller - but this is not a conventional pattern. Plus, you will  have three (or more, if you add more things that you can add elements to) very similar looking blocks of code in each controller.

I thought about ComponentElement, ElementElement, etc, controllers, each with a `store` method. However, this seems a bit overkill. These would essentially be single action controllers, and you'd have to add more for each parent type you  want. You'd also have the same problem with duplicate code. I suppose you could abstract that duplicate code into  another trait that each of these single action action controllers, but there's a fair bit of boilerplating involved that couldn't be easily abstracted and I wanted to avoid even that.

My solution was to have all three routes use the same single `store` method on the ElementController. It should also be trivial to point more routes if needed.

```php
Route::post('/api/components/{componentUuid}/elements', [
    'as' => 'api.components.elements.store',
    'uses' => 'Api\ElementsController@store',
]);

Route::post('/api/elements/{elementUuid}/elements', [
    'as' => 'api.elements.elements.store',
    'uses' => 'Api\ElementsController@store',
]);

Route::post('/api/layouts/{layoutUuid}/elements', [
    'as' => 'api.layouts.elements.store',
    'uses' => 'Api\ElementsController@store',
]);
```

## I thought this was about dependency injection. When are you getting to the dependency injection?!

_Riiight about_ __now__...

```php
public function store(StoreElementRequest $request)
{
    $parent = null;
    if (null !== $request->route('componentUuid')) {
        $parent = Component::where($request->route('componentUuid'))->firstOrFail();
    }

    if (null !== $request->route('elementUuid')) {
        $parent = Component::where($request->route('elementUuid'))->firstOrFail();
    }

    if (null !== $request->route('layoutUuid')) {
        $parent = Component::where($request->route('layoutUuid'))->firstOrFail();
    }

    if (null === $parent) {
        abort(404);
    }

    $validated = $request->validated();

    $element = new Element();
    $element->tagName = $validated['tagName'];

    $parent->insert($element, $validated['insertBefore'] ?? null);

    return response()->json(new ElementResource($element), 201);
}
```

Ugh... that's a pretty fat controller action there. Couldn't we do something like:

```php
public function store($parent, StoreElementRequest $request)
{
    $validated = $request->validated();

    $element = new Element();
    $element->tagName = $validated['tagName'];

    $parent->insert($element, $validated['insertBefore'] ?? null);

    return response()->json(new ElementResource($element), 201);
}
```

The obvious stumbling block is we need to type-hint the parent parameter so that the DI framework can work its magic. What makes this hard is that we don't know if we want a Component, an Element, or a Layout. In fact, we shouldn't really have to care. As long as `$parent` contains something that can have Elements as children, then our controller can do what it needs to with that parent.

Under the hood, Laravel uses [reflection][PHP Reflection API] to extract the type-hint as a string, which it then looks for in the service container to see if any services have been bound to that type-hint. Technically, we could register the service under any arbitrary string (and there are reasons to do this), but PHP type-hinting will have a hissy fit if we don't use a valid type or class name. 

Oh... hey... all three of our models use the `HasChildElements` trait! We could just type-hint with that, couldn't we?

```php
public function store(HasChildElements $parent, StoreElementRequest $request)
```

```php
class HasChildElementsServiceProvider extends ServiceProvider
{
/**
 * Register services.
 */
    public function register(): void
    {
        $this->app->bind(HasChildElements::class, function ($app, $params = []): ?HasChildElements {
// all the ugly code for finding the right model 
        });
    }
}
```

Oooh... no - __traits are not types__. At least, not PHP traits, anyway.

The solution is to have the models implement a common interface, since interfaces are valid as types (much like abstract or otherwise extended classes):

```php
<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Element;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface HasChildElements
{
    public function children(): MorphMany;

    public function insert(Element $element, $beforeUuid = null): Element;

    public function remove(Element $child): void;
}
```

In a way, this makes sense. Just because my current three models all share behaviour through their common trait, there may be a time when I have a different model that can have child elements, but handles them differently. What's important is that all parent models have the same three methods available to call on with the same parameters and expectations on return. The controller doesn't care how it gets done, just that it can be.

## So what's actually going on in that service

```php
use App\Component;
use App\Element;
use App\Layout;
use App\Contracts\HasChildElements;

class HasChildElementsServiceProvider extends ServiceProvider
{
/**
 * Register services.
 */
    public function register(): void
    {
        $this->app->bind(HasChildElements::class, function ($app, $params = []): ?HasChildElements {
            $candidates = [
                Component::class => 'component',
                Element::class => 'element',
                Layout::class => 'layout',
            ];

            $parent = null;
            foreach ($candidates as $key => $model) {
                $uuid = request()->route($key);

                if (null !== $uuid) {
                    $parent = $model::where('uuid', $uuid)
                        ->first();

                    if (null !== $parent) {
                        break;
                    }
                }
            }

// no appropriate item has been found
            if (null === $parent) {
                throw new ModelNotFoundException();
            }

            return $parent;
        });
    }
}
```

## One last thing - you can call services from services

Yup... you can totally do this.

I reduced the line count in my `HasChildElementsServiceProvider` by having it use the existing service providers to search for candidate parents, while also adding the extra ability for me to use the service(s) more flexibly.

```php
public function register(): void
{
    $this->app->bind(HasChildElements::class, function ($app, $params = []): ?HasChildElements {
        $candidates = [
            Component::class,
            Element::class,
            Layout::class,
        ];

        $params['default'] = null;

        $parent = null;
        foreach ($candidates as $model) {
            $parent = $app->make($model, $params);

            if (null !== $parent) {
                break;
            }
        }

        return $parent;
    });
}
```

The `$params` array lets me inject search criteria (uuid and type) for situations where I'm not using route model binding. For example, when moving an element to a new parent, my route is simply `PATCH /elements/{elementUuid}` with the new parent information included in the payload. This means my `update` method needs to look for the new parent using the information in the validated payload:

```php
public function update(Element $element, UpdateElementRequest $request): JsonResponse
{
    $validated = $request->validated();

    if (isset($validated['parentUuid'])) {
        // UpdateElementRequest validates the parentUuid and parentType.
        $newParent = resolve(HasChildElements::class, [
            'uuid' => $validated['parentUuid'],
            'type' => $validated['parentType'],
        ]);

        $element->moveTo($newParent);
    }

    return response()->json(new ElementResource($element), 200);
}
```

## Just one more thing - caveats

I've only really gotten in deep with this stuff over the last month or so, and while I've turned up some issues and figured my way round them, I'm not entirely sure I've bumped on all potential pain points yet.

When I first came up with the idea of DI by interface, I wasn't sure if it was all that clever an idea, even though it feels like a purer realisation of DI (the controller action doesn't have to specify a particular entity - only that it can do the job required). I did bounce this idea off of someone I consider to be smarter than myself, and did not get a "that's a horrible idea" response, so there's that going for it.

One interesting issue I came upon - when working on a different route/action, I tried to inject both an element and it's parent:

```php
public function update(Element $element, HasChildElements $parent, UpdateElementRequest $request) 
```

This lead to an error where `$parent` is a string when the action is called, and PHP threw an exception to that.

I took a bit of a dive into the Laravel controller and dependency resolution code to find an explanation. Somehow, the resolver was confusing the Element and HasChildElements services, so it wasn't injecting the second service. Even if I swapped the two parameters, the second parameter would be a string. This kind of thing also happens if you try to inject two parameters with the exact same model/service.

Eventually, I figured that I can get the parent from the element itself through the relationship between `Element` and `HasChildElements`, so it wasn't necessary to inject the parent at all.

As far as I can tell, the regular service resolution (either through the `resolve()` helper, or through `App::make()`) doesn't have this problem.

If any more pointy bits come up over time, I'll be sure to write a followup.

[DRY]: https://en.wikipedia.org/wiki/Don%27t_repeat_yourself
[PHP Reflection API]: https://www.php.net/manual/en/book.reflection.php
[Rule of Three]: https://en.wikipedia.org/wiki/Rule_of_three_(computer_programming)
[trait]: https://www.php.net/manual/en/language.oop5.traits.php
[ViewCrafter]: https://viewcrafter.com/
[previous post]: /blog/2020-01-29/introduction-to-dependency-injection-in-laravel
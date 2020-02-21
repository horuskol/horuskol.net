---
extends: _layouts.post
title: Introduction to dependency injection in Laravel
author: Stuart Jones
date: 2020-01-29
section: post
tags: [php,laravel,oop]
image: https://horuskol.net/assets/images/horuskol-ring.png
description: Have Laravel do some of the heavy lifting for you by taking advantage of its DI framework.
---

_This is part 1 of a 2-part series. You could jump to the [next post] but you might still want to read this one first_.

Dependency injection is one of the things that makes Laravel such a great framework to build in. There's a lot of functionality already baked into the core that makes it really easy to quickly put together your app.

## Okay, but what is dependency injection?

I'm not going to go into extreme detail here, since I want to focus on using Laravel rather than principles of software design - you can find out more on [Wikipedia][dependency injection on Wikipedia].

My simple take on dependency injection (DI) is that, as much as possible, a class or method is _given_ what it needs to get things done, rather than have to either construct it or having _too much_ knowledge of other parts of your application framework to get those other parts to construct it. This leads to code that  [separates concerns][separation of concerns on Wikipedia], and can help reduce coupling - making code more readable,  more adaptable, and more flexible. There are many ways to do this, and also a few different takes on exactly what is  a dependency and what form DI should take.

My very simple take is that DI allows your code to simply say "gimme!" to the DI or application framework - and not care exactly what it is necessary for that "gimme!" to be fulfilled.

## Dependency injection in Laravel

Laravel provides a fair bit of support for dependency injection - using under the hood magic (also known as the [PHP Reflection API]), but it also gives you the ability to tap into that magic.

The simplest form of DI in Laravel is to use type hints in your controller methods to have the framework inject a  service or even a data model that you can then use. This reduces a certain amount of boilerplating (you don't need to query the database for the data you want to work with, or set up complex validation within your controller). This makes for leaner controllers.

For example, you can automatically validate a request through some custom rules by specifying a request object in your method arguments:

```php
/**
 * Create and save a new list item from validated input.
 * 
 * @param \App\Http\Requests\StoreListItem $request 
 *      This request will be authorised and validated by code in StoreListItem.
 */
public function store(StoreListItem $request)
{
    $validated = $request->validated(); // this ensures we're only using validated data from the request.
    
    ...
}
```

All you need is to create a class that can be found in `app\Http\Request\StoreListItem.php` and have it implement the Laravel [FormRequest][FormRequest on Laravel docs] interface, define some rules in there, and you can prevent  unauthorised access and validate incoming data.

So, without any wiring through configuration, Laravel knows to look for the StoreListItem FormRequest, and will provide appropriate _forbidden_ or _invalid data_ responses back to the client. Using the `FormRequest::validated()` method in your controller action will also ensure you are working only with validated data.

## Injecting models with route model binding

The next thing that Laravel can help with is injecting a model into your controller, through [route model binding][route model binding on Laravel docs].

Out of the box, you can set up a route like `Route::get('lists/{list}')` and then inject that list item into the controller method `public function show(List $list)` when a user makes a request to `example.com/lists/1`. Laravel will even return a 404 if the requested list cannot be found.

If you don't want to use list IDs in the URL, then you can specify an alternative route key in the model - such as a slug or a UUID.

## The service container

So we've seen it's pretty easy for Laravel to inject simple models or some core things like FormRequests.

But what about more complex objects? Ones that might need some setup, for example.

This is where Laravel's [service container][service container on Laravel docs] comes into the mix.

Say I wanted the following routes and methods in a `ListController`:

```php
/**
 * GET lists/{list}
 * 
 * @param \App\List $list
 */
public function show(List $list)
{
    return view('lists.show', ['list' => $list]);
}

/**
 * GET lists/create
 * GET lists/{list}/edit
 * 
 * @param \App\List $list
 *      This either contains the List being edited, or a new empty List.
 */
public function edit(List $list)
{
    return view('lists.edit', ['list' => $list]);
}

/**
 * POST lists
 * POST lists/{list}
 * 
 * @param \App\List $list
 *      This either contains the List being edited, or a new empty List.
 * 
 * @param \App\Http\Requests\SaveListRequest
 *      The FormRequest which authorises and validates the incoming request and input data.
 */
public function save(List $list, SaveListRequest $request)
{
    $validated = $request->validated();
    $list->name = $validated['name'];
    $list->slug = $validated['slug'];

    $list->save();

    return redirect('lists/' . $list->slug);
}
```

I like this pattern since most of the time I would show the same form/view to create or edit the list, and would use  the same validation rules for saving or updating the list.

However, if we just used the default binding, we would get some errors trying to create a new list, since we're not providing a route parameter to identify the list (in the routes `GET lists\create` or `POST lists`). To get  around this, we need to register a "List" service which tells Laravel to provide a new, empty List if the user isn't trying to ask for an existing one.

You can either create a new [service provider][service provider on Laravel docs], named something like `ListServiceProvider` (and add it to the list of service providers in `config/app.php`), or edit an existing provider. Ideally, service providers should be kept as small and focused as possible, so you probably should create a new one.

In the new provider's `register()` method, you can create your new service binding:

```php
public function register()
{
    $this->app->bind(List::class, function ($app) {
        $slug = request()->route('list');

        if (null !== $slug) {
            // the user is requesting a specific list using the slug in the URL
            // so we will try to load it
            // if it doesn't exist, then we want the user to see a 404
            return List::where('slug', $slug)
                ->firstOrFail();
        }

        // the user didn't request a specific list, so we'll give them a new one that can be saved
        return new List();
    });
}
```

## Dependency injection isn't just for controller actions

So far, the examples I've provided have used controller action methods - but Laravel can inject via type hinting across many other classes within you application. Basically, any class that is resolved through the service container (this includes controllers, event listeners, middleware, and even form objects) can accept injected services and entities through the constructor.

Queued jobs can accepted injected services and entities in their `handle` method.

Finally, I've even been able to inject into the methods of some other classes when I've needed to.

For example, say I have an API to add and insert items into lists. When the client submits `POST /api/lists/{list}/item` and includes a parameter named `insertBefore` which stores the unique identifier of another list item, I want to validate that this other list item indeed belongs to the list I'm trying to add to.

Using type hinting, I can load the requested list into the `rules` method of a `FormRequest` and have that pass into a validation rule `exists_in_list` which confirms that the item the client is trying to "insert before" does indeed belong to the list I'm trying to add a new item to.

```php
class StoreListItemRequest extends FormRequest
{
    ...

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(List $list): array
    {
        return [
            'text' => [
                'required',
                'string',
            ],
            'insertBefore' => [
                'bail',
                'sometimes',
                'nullable',
                'uuid',
                "exists_in_list:lists,{$list->uuid}",
            ],
        ];
    }
  
    ...
}
```

## And there's even more

[Next week][next post], I'll be posting another approach to dependency injection which I've been refining over the last month or so.

[dependency injection on Wikipedia]: https://en.wikipedia.org/wiki/Dependency_injection
[FormRequest on Laravel docs]: https://laravel.com/docs/6.x/validation#form-request-validation
[PHP Reflection API]: https://www.php.net/manual/en/book.reflection.php
[route model binding on Laravel docs]: https://laravel.com/docs/6.x/routing#route-model-binding
[separation of concerns on Wikipedia]: https://en.wikipedia.org/wiki/Separation_of_concerns
[service container on Laravel docs]: https://laravel.com/docs/6.x/container
[service provider on Laravel docs]: https://laravel.com/docs/6.x/providers#introduction
[next post]: /blog/2020-02-05/levelling-up-dependency-injection-in-laravel
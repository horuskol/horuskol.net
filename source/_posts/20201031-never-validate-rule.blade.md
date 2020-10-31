---
extends: _layouts.post
title: Fail validation if a parameter is sent regardless of its value in Laravel
author: Stuart Jones
date: 2020-10-31
section: post
tags: [php, laravel]
image: https://horuskol.net/assets/images/posts/20201031-never-validate/no-entry.jpg
description: A surprisingly simple validation rule that invalidates a request if a specific parameter exists.
---

<figure class="in-flow">
<img src="/assets/images/posts/20201031-never-validate/no-entry.jpg" alt="a no entry sign on a gate">
<figcaption>
    <a href="https://commons.wikimedia.org/wiki/File:No_entry.jpg" class="text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline">source</a> -
    <a href="https://creativecommons.org/licenses/by/2.0/deed.en" class="text-blue-700 visited:text-purple-700 hover:text-indigo-500 underline">license</a>
</figcaption>
</figure>

The Laravel framework has some powerful [validation][Laravel validation] built into it.

One of the most useful features is that after successfully validating input parameters from a request you get an array of _only_ the validated data. Any inputs that are not mentioned in the validation rules are silently removed from this array - ensuring that you are only working with valid data.

I would say that 99.9999% of the time, this is exactly the behaviour you want.

However, yesterday I came upon an unusual situation where I wanted to tell the client (over an API - not a user filled form) that they shouldn't have sent a particular parameter. I couldn't find anything in the documentation that suggested how to do this, so I reached out to the PHP Australia developer community and thanks to a suggestion there I came up with something that was surprisingly easy in hindsight.

## Introducing the 'never' rule

```php
Validator::extend('never', function () {
    return false;
}, ':attribute is not an acceptable parameter');
``` 
That's (almost) all there is to it. Since this uses the `Validator` facade, you can put this code almost anywhere (even right before you validate). Personally, I put all this in a `ValidatorServiceProvider` so that they are all available wherever I need them.

The only other thing is to put the rule into the validator:

```php
$validatedData = $request->validate([
    'want-this-uuid' => 'required|uuid|exists:some_table,uuid',
    'want-this-name' => 'required|string',
    'never-send-this' => 'never',
]);
```

So long as the request does not send the `never-send-this` parameter (and the other parameters are valid), the request will pass validation. The `$validatedData` array won't contain the parameter either since we didn't send it.

If the API client does happen to send the parameter, it will be told off.

## Caveats

This is a very unusual case - almost always I want to just silently ignore unexpected parameters (as is the default behaviour) - so I would use this very sparingly.

[Laravel validation]: https://laravel.com/docs/8.x/validation
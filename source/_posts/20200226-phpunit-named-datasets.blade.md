---
extends: _layouts.post
title: PHPUnit - name your data sets in your data providers
author: Stuart Jones
date: 2020-02-26
section: post
tags: [php, phpunit, testing]
image: https://horuskol.net/assets/posts/20200226-phpunit-named-data-sets.png
description: Name the data sets in your data providers to help identify failing tests, and generally make life easier.
---

Did you know that you can name the data sets in your data providers?

It might seem to be only decoration, but it can make life a little easier when reading both tests and test results.

Data providers are useful in that they allow us to repeat a single test with a range of inputs to ensure that we have covered various edge cases and user provided values. Unfortunately, having a large range of data sets in a provider can make it hard to keep track of what the purpose of each data set is for.

Say you have a form that is asking the user for values to set the font size, line height, and top and bottom margins of an element. These values are collected into a typography scale. You've enshrined my expectations in a validation rule, and now you want to test the logic in that rule. So, you have a couple of tests and providers like this:

```php
/**
 * @test
 * @covers \App\Rules\Typography\Scale
 */
public function message_for_missing_property(): void
{
    $rule = new Scale('body');

    $this->assertFalse($rule->passes('scale', []));

    $this->assertEquals("Invalid scale - body is missing font-size", $rule->message());
}

/**
 * @test
 * @covers \App\Rules\Typography\Scale
 */
public function message_for_invalid_property(): void
{
    $rule = new Scale('body');

    $this->assertFalse($rule->passes('scale', [
        'font-size' => '1',
        'line-height' => '1.2.5',
        'margin-bottom' => '1',
        'margin-top' => '1.25',
    ]));

    $this->assertEquals($rule->message(), "Invalid scale - body has invalid settings for line-height");
}

public function provides_valid_scales(): array
{
    return [
        [
            [
                'font-size' => '1',
                'line-height' => '1.25',
                'margin-bottom' => '1',
                'margin-top' => '1.25',
            ],
        ],
    ];
}

public function provides_invalid_scales()
{
    return [
        [
            [],
        ],
        [
            [
                'line-height' => '1.25',
                'margin-bottom' => '1',
                'margin-top' => '1.25',
            ],
        ],
        [
            [
                'font-size' => '-1',
                'line-height' => '1.25',
                'margin-bottom' => '1',
                'margin-top' => '1.25',
            ],
        ],
        [
            [
                'font-size' => '1.1.1',
                'line-height' => '1.25',
                'margin-bottom' => '1',
                'margin-top' => '1.25',
            ],
        ],
        [
            [
                'font-size' => 'a',
                'line-height' => '1.25',
                'margin-bottom' => '1',
                'margin-top' => '1.25',
            ],
        ],
        [
            [
                'font-size' => '1',
                'margin-bottom' => '1',
                'margin-top' => '1.25',
            ],
        ],
        [
            [
                'font-size' => '1',
                'line-height' => '1.25',
                'margin-top' => '1.25',
            ],
        ],
        [
            [
                'font-size' => '1',
                'line-height' => '1.25',
                'margin-bottom' => '1',
            ],
        ],
    ];
}
```

If you ran the tests and any of the conditions fail, you're just going to be told something like:

```
1) Test\Unit\Rules\Typography\ScaleTest::fails_invalid_scales with data set #0 (array())

2) Test\Unit\Rules\Typography\ScaleTest::fails_invalid_scales with data set #1 (array('1.25', '1', '1.25'))

3) Test\Unit\Rules\Typography\ScaleTest::fails_invalid_scales with data set #2 (array('-1', '1.25', '1', '1.25'))
```

Not very clear, is it?

## Name your data sets

Naming your data sets provides two benefits:

1. The intent of the data set is clearer when you are looking at the test;
2. The failure report is more explicit.

```php
public function provides_invalid_scales()
{
    return [
        'missing all properties' => [
            [],
        ],
        'missing font-size' => [
            [
                'line-height' => '1.25',
                'margin-bottom' => '1',
                'margin-top' => '1.25',
            ],
        ],
        'negative font-size' => [
            [
                'font-size' => '-1',
                'line-height' => '1.25',
                'margin-bottom' => '1',
                'margin-top' => '1.25',
            ],
        ],
        'multiple decimal points' => [
            [
                'font-size' => '1.1.1',
                'line-height' => '1.25',
                'margin-bottom' => '1',
                'margin-top' => '1.25',
            ],
        ],
        'non-numeric' => [
            [
                'font-size' => 'a',
                'line-height' => '1.25',
                'margin-bottom' => '1',
                'margin-top' => '1.25',
            ],
        ],
        'missing line-height' => [
            [
                'font-size' => '1',
                'margin-bottom' => '1',
                'margin-top' => '1.25',
            ],
        ],
        'missing margin-bottom' => [
            [
                'font-size' => '1',
                'line-height' => '1.25',
                'margin-top' => '1.25',
            ],
        ],
        'missing margin-top' => [
            [
                'font-size' => '1',
                'line-height' => '1.25',
                'margin-bottom' => '1',
            ],
        ],
    ];
}
```

And our failed tests:

```
1) Test\Unit\Rules\Typography\ScaleTest::fails_invalid_scales with data set "missing all properties" (array())

2) Test\Unit\Rules\Typography\ScaleTest::fails_invalid_scales with data set "missing font-size" (array('1.25', '1', '1.25'))

3) Test\Unit\Rules\Typography\ScaleTest::fails_invalid_scales with data set "negative font-size" (array('-1', '1.25', '1', '1.25'))
```

IDEs like PHPStorm will also use the data set name when reporting on PHPUnit results.

<figure class="in-flow">
<img src="/assets/images/posts/20200226-phpunit-named-data-sets.png" alt="">
<figcaption>test results in PHPStorm</figcaption>
</figure>
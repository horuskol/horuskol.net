---
extends: _layouts.post
title: Don't use in-memory SQLite for testing Laravel
author: Stuart Jones
date: 2018-11-10
section: post
tags: [database,laravel,php,sqlite,testing]
---

The PDO/SQLite driver for PHP lets you create a [connection to an in-memory database](http://php.net/manual/en/ref.pdo-sqlite.connection.php) - I'd been using this for testing in various projects for a while. It lets you create a safe, ephemeral database so your tests don't impact any local databases. 

However, there's a consequence with using `:memory:` as your database path when testing a Laravel application. Each individual test runs up a new instance of your app - this is good and proper, since it means each test is properly isolated. It also means a new database connection is opened with every test. The memory-based SQLite database is cleared when each connection closes. Again, this is good and proper, otherwise you'd have a serious memory leak.

What this means is that every test that requires some database to be setup, you have to run database migrations to initialise the brand new database you get with the new app instance in the test. This slows things down quite a bit (I had 37 tests that were taking about 16 seconds on my local machine).

## Use a file

Instead, if you use a file (ideally, use your `.env.testing` configuration file to use a specify a different file to your local development database), and use the [`RefreshDatabase`](https://laravel.com/docs/5.7/database-testing#resetting-the-database-after-each-test) trait, Laravel will run the migrations once for the first test, and then wrap any other database interaction within transactions that are rolled back at the conclusion of each test - restoring the original post-migration (but empty) database. The next test, with a fresh instance of the app will have a new connection to the already migrated database (and `RefreshDatabase` will already now not to try to migrate).

You could also use a testing database in MySQL (or whatever database server you are using for your application) - this needs a little more setup than the file approach, but it does mean if you application has to rely on specific syntax/feature of that database server, you can properly cover code that uses that syntax in your tests.

Using the file-based SQLite database, my tests are now running at about 9 seconds. 
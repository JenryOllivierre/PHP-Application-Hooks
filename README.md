# PHP Application Hooks

## Introduction

PHP Application Hooks is a lightweight standalone package that lets you control when and where specific blocks of code can be executed within your application.

## Installation

install via composer with `composer require "jenryollivierre/php-application-hooks": "1.1.*"` or simply download from [github](https://github.com/JenryOllivierre/PHP-Application-Hooks)

## How To Use

Get things started by creating an instance that can be accessed globally.

```php
<?php

use JenryOllivierre\Hooks\Hooks;

$hooks = new Hooks;
```

Then, at the point in your application where you will want 3rd party apps to be able to do something, you call on either of the following methods.

- `$hooks->applyActions($name, [$params])`
- `$hooks->applyFilters($name, $value, [$params])`

## Actions

We use actions when we want an action to be carried out. To let actions be carried out at a specific point in your application, you call on the `$hooks->applyActions()` method, which takes 3 arguments.

(1) string $name - the name to identify the action by. This should be unique.

(2) array $arguments - an array of arguments that you want to pass to anyone hooking into your action.

(3) bool $return - whether to return the contents of the action. This defaults to false as actions in general are not meant to return anything.

#### Example of Actions

```php
<?php

// The point in our application where we want to allow 3rd party apps to do something.

// Example - Let's allow 3rd party apps to do anything they want before a post is deleted
$postId = getPostId();
$canDelete = canPostBeDeleted($postId);
$hooks->applyActions('before_post_delete', [$postId, $canDelete]);
```

Now, for the 3rd party apps to hook into the action of your application, they would call on the global $hook instance and call on the `addAction()` method.

```php
<?php

// Example in the 3rd party app - Let's clean up the database before we delete the post
$hooks->addAction('before_post_delete', [new Post, 'cleanUpDatabase'], 10);
```

The `addAction()` method takes 4 parameters.

(1) string $name - the name of the action to hook into. 

(2) callable $callback - Closures | function name | array of a class instance and its method

(3) int $priority - defaults to 100. Higher number priority actions are ran latest.

(4) int $arguments - the number of arguments to pass to the $callback. By default, this passes all arguments. If the main application passed an array of 5 arguments to the action, the 3rd party app can state 2 to only get the first 2 parameters.

To resolve the example above, the given callback to use was Post::cleanUpDatabase(). It would work like this:

```php
<?php

class Post
{
    /**
     * @param int $postId
     * @param bool $canDelete
     * @return void
     */
    public function cleanUpDatabase($postId, $canDelete)
    {
        // Do stuff
    }
}
```

## Filters

We use filters when we want to filter a value. To let filters be carried out at a specific point in your application, you call on the `$hooks->applyFilters()` method, which takes 3 arguments.

(1) string $name - the name to identify the filter by.

(2) mixed $value - the value that is to be filtered.

(3) array $arguments - an array of arguments that you want to pass to 3rd party apps hooking into your filter.

** Note that the $value parameter will also be passed to the $arguments as the first parameter. So technically, everything in the $arguments array will be passed to 3rd party apps as the 2nd parameter onwards.

#### Example of Filters

```php
<?php

// Example of code in our application
// Let's allow 3rd party apps to determine if the post should be deleted

$postId = getPostId();
if ($hooks->applyFilters('user_can_delete_post', false, [$postId])) {
    deletePost($postId);
}
```

Now, for 3rd party apps to hook into our filter, they would call on the global $hook instance and call on the `addFilter()` method.

```php
<?php

// Example in our 3rd party app - Let's check to see if the post should be allowed to be deleted

// Closure Example
$hooks->addFilter('user_can_delete_post', function ($value, $postId) {
    // The value to filter is always the first parameter passed to your callback
    // Any other parameters passed through the 'applyFilters()' method will be available in the order they were passed to the array
    $post = getPost($postId);
    $user = getCurrentUser();

    if ($user->id === $post->author) {
        return true;
    }

    // Tip:: Always return the original value if your conditionals doesn't match
    return $value;
});

// Function name example
$hooks->addFilter('user_can_delete_post', 'someFunctionName', 10, 2);

function someFunctionName($value, $postId)
{
    $post = getPost($postId);
    $user = getCurrentUser();

    if ($user->id === $post->author->id) {
        return true;
    }

    return $value;
}

// CLass instance example
$hooks->addFilter('user_can_delete_post', [Post, 'userCanDeletePost'], 10, 1);

// In our Post class
class Post
{
    public function userCanDeletePost($canDelete)
    {
        if (getCurrentUser() === 'super_admin') {
            return true;
        }

        return $canDelete;
    }
}
```

The `addFilter()` method takes 4 parameters.

(1) string $name - the name of the filter to hook into.

(2) callable $callback - Closures | function name | array of a class instance and its method

(3) int $priority - defaults to 100. Higher number priority filters are ran latest.

(4) int $arguments - the number of arguments to pass to the $callback. By default, this passes all arguments.

## Security Vulnerability

If you discover any security vulnerability, please email Jenry Ollivierre at [jenry@jenryollivierre.com](mailto:jenry@jenryollivierre.com)

## License

PHP Application Hooks is open source software licensed under the [MIT License](https://opensource.org/licenses/MIT)
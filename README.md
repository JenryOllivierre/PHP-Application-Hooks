# PHP Application Hooks

## Introduction

PHP Application Hooks is a lightweight standalone package that lets you control when and where specific blocks of code can be executed within your application.

## Installation

install via composer with `composer require "jenryollivierre/php-application-hooks": "1.0.*"` or simply download from [github](https://github.com/JenryOllivierre/PHP-Application-Hooks)

## How To Use

Get things started by creating an instance that can be accessed globally.

```php
<?php

use JenryOllivierre\Hooks\Hooks;

$hooks = new Hooks;
```

Then, at the point in your application where you want others to be able to do something, you call on either of the following methods.

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

// Example of code in our application
// Let's allow something to be done before a post is deleted
$postId = getPostId();
$hooks->applyActions('before_post_delete', [$postId]);
```

Now, for others to hook into our action, they would call on the global $hook instance and call on the `addAction()` method.

```php
<?php

// Let's do something before the post is deleted
$hooks->addAction('before_post_delete', [new Post, 'cleanUpDatabase'], 10);
```

The `addAction()` method takes 4 parameters.

(1) string $name - the name of the action to hook into

(2) callable $callback - Closures | function name | array of a class instance and its method

(3) int $priority - defaults to 100. Higher number priority actions are ran latest.

(4) int $arguments - the number of arguments to pass to the $callback. By default, this passes all arguments.

## Filters

We use filters when we want to filter a value. To let filters be carried out at a specific point in your application, you call on the `$hooks->applyFilters()` method, which takes 3 arguments.

(1) string $name - the name to identify the filter by. This should be unique.

(2) mixed $value - the value that is to be filtered.

(3) array $arguments - an array of arguments that you want to pass to anyone hooking into your filter.

** Note that the $value parameter will also be passed to the $arguments as the first parameter. So technically, everything in the $arguments array will be the 2nd parameter onwards.

#### Example of Filters

```php
<?php

// Example of code in our application
// Let's allow others to determine if the post should be deleted
$postId = getPostId();

if ($hooks->applyFilters('user_can_delete_post', false, [$postId])) {
    deletePost($postId);
}
```

Now, for others to hook into our filter, they would call on the global $hook instance and call on the `addFilter()` method.

```php
<?php

// Let's check to see if the post should be allowed to be deleted
$hooks->addFilter('user_can_delete_post', function ($value, $postId) {
    // The value to filter is always the first parameter passed to your callback
    // Any other parameters passed through the 'applyFilters()' method will be available in the order they were passed to the array
    $post = getPost($postId);
    $user = getCurrentUser();
    if ($user->id === $post->author) {
        $value = true;
    }
    return $value;
});
```

The `addFilter()` method takes 4 parameters.

(1) string $name - the name of the filter to hook into

(2) callable $callback - Closures | function name | array of a class instance and its method

(3) int $priority - defaults to 100. Higher number priority actions are ran latest.

(4) int $arguments - the number of arguments to pass to the $callback. By default, this passes all arguments.

## Security Vulnerability

If you discover any security vulnerability, please email Jenry Ollivierre at [jenry@jenryollivierre.com](mailto:jenry@jenryollivierre.com)

## License

PHP Application Hooks is open source software licensed under the [MIT License](https://opensource.org/licenses/MIT)
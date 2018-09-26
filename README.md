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

- `$hooks->applyActions($name, $arguments)`
- `$hooks->applyFilters($name, $value, $arguments)`

## Actions

We use actions when we want an action/task to be carried out. To let 3rd party apps be able to carry out actions at a specific point in your application, you call on the `$hooks->applyActions()` method, which takes 3 arguments.

- string $name :: the name to identify the action by. This should be unique.
- array $arguments :: an array of arguments that you want to pass to 3rd party apps hooking into your action.
- bool $return :: whether to return the contents of the action. This defaults to false as actions in general are not meant to return anything.

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

- string $name :: the name of the action to hook into. 
- callable $callback :: Closures | function name | array of a class instance and its method
- int $priority :: defaults to 100. Higher number priority actions are ran latest.

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

- string $name :: the name to identify the filter by. This should be unique.
- mixed $value :: the value that is to be filtered.
- array $arguments :: an array of arguments that you want to pass to 3rd party apps hooking into your filter.

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

// Class instance example
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

- string $name :: the name of the filter to hook into.
- callable $callback :: Closures | function name | array of a class instance and its method
- int $priority :: defaults to 100. Higher number priority filters are ran latest.
- int $arguments :: the number of arguments to pass to the $callback. By default, this passes all arguments.

## Extending & Making Your Own

The package is fully extensible, allowing you to create your own 'hooks'. You simply extend the 'JenryOllivierre\Hooks\HooksFoundation' class and then define your own methods. For example:

```php
<?php

namespace MyNameSpace\Task;

use JenryOllivierre\Hooks\HooksFoundation;

class AppHooks extends HooksFoundation implements Filterable, Actionable
{
    use HasActions;
    use HasFilters;
}
```

Or rolling your own:

```php
<?php

namespace MyNameSpace\Task;

use JenryOllivierre\Hooks\HooksFoundation;

class Hooks extends HooksFoundation
{
    /**
     * Add a task hook.
     *
     * @param string $name
     * @param callable $callback
     * @param int $priority
     * @param int $params
     * @return void
     */
    public function addAppTask(string $name, callable $callback, int $priority = 100, int $params = 0)
    {
        $this->storeHook('app_tasks', $name, $callback, $priority, $params);
    }

     /**
     * Apply all the callbacks that was added to a specific task.
     *
     * @since 1.0
     * @param string $name
     * @param array $args
     * @param bool $return
     * @return mixed|void
     */
    public function applyAppTasks(string $name, array $args, bool $return = false)
    {
        return $this->resolveTasks('app_tasks', $name, $args, $return);
    }
}
```

The HooksFoundation class has a lot of useful methods to allow you to fully extend and make your own hooks class. They are:

```php
    /**
     * Add a hook.
     * @param string $type
     * @param string $name
     * @param callable $callback
     * @param int $priority
     * @param int $params
     * @return void
     */
    protected function storeHook(string $type, $name, callable $callback, int $priority = 100, int $params = 1);

    /**
     * Resolve all the values for a given hook.
     * @since 1.0
     * @param string $hookType
     * @param string $name
     * @param mixed $value
     * @param array $args
     * @return mixed
     */
    protected function resolveValues(string $hookType, string $name, $value, array $args = []);

    /**
     * Resolve all the tasks for a given hook.
     * @since 1.0
     * @param string $hookType
     * @param string $name
     * @param array $args
     * @param bool $return
     * @return void
     */
    protected function resolveTasks(string $hookType, string $name, array $args = [], bool $return = false);

    /**
     * Check if anything have been added to a particular hook type.
     * @since 1.0
     * @param string $type
     * @return bool
     */
    protected function hookTypeExists(string $type);

    /**
     * Check if there has been anything set for a specific hook, for a
     * particular hook type.
     * @since 1.0
     * @param string $hookType
     * @param string $name
     * @return bool
     */
    protected function hookExistsByType(string $hookType, string $name);

    /**
     * Get everything that has been added to a specific hook type.
     * @since 1.0
     * @param string $type
     * @return array
     */
    protected function getAllHooksByType(string $type);

    /**
     * Remove all hooks from a hook type.
     * @since 1.0
     * @param string $type
     * @return void
     */
    protected function removeAllHooksFromType(string $type)

    /**
     * Get everything that have been added to a specific hook, for a
     * particular hook type.
     * @since 1.0
     * @param string $type
     * @param string $name
     * @return array
     */
    protected function getHookByType(string $type, string $name);

    /**
     * Remove a hook type.
     * @since 1.0
     * @param string $type
     * @return void
     */
    protected function removeHookType(string $type);

    /**
     * Remove a specific hook for a particular hook type.
     * @since 1.0
     * @param string $hookType
     * @param string $name
     * @return void
     */
    protected function removeHookByType(string $hookType, string $name);
```



## Security Vulnerability

If you discover any security vulnerability, please email Jenry Ollivierre at [jenry@jenryollivierre.com](mailto:jenry@jenryollivierre.com)

## License

PHP Application Hooks is open source software licensed under the [MIT License](https://opensource.org/licenses/MIT)
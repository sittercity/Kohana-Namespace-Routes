Kohana-Namespace-Routes
---

This is a route modification to allow you to load routes with the same identifier into different applications.

Usage
===

Create a file in routes/ for each of your applications, and follow the syntax guidelines in the file.

Load the routes into your application by putting the following code into your bootstrap.php:

 * Route::load_namespace('application name');

You can also use this module to help clean up your bootstrap.php file if it has lots of routes in it.

Added/Modified Methods
===

 * Route::factory($uri, $regex = NULL)
 * Route::set($name, $uri, $regex = NULL, $namespace = NULL)
 * Route::get($name, $namespace = NULL)
 * Route::load_namespace($namespace) - Directly injects a namespace into the routing class
 * Route::clear_namespace($namespace) - Removes a namespace from the routing class
 * Route::all($flatten = TRUE) - You can get a full named array by passing FALSE.
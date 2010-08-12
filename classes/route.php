<?php
/**
 * Namespaced routes for Kohana
 *
 * @package   Kohana-Namespace-Routes
 * @copyright 2010 Sittercity, Inc.
 */

defined('SYSPATH') or die('No direct script access.');

/**
 * Namespaced Routes for Kohana
 *
 */
class Route extends Kohana_Route
{
	/**
	 * Factory method for chaining
	 *
	 * @param string $uri   URI pattern
	 * @param array  $regex regex patterns for route keys
	 *
	 * @return Route object
	 */
	public static function factory($uri = null, array $regex = null)
	{
		return new Route($uri, $regex);
	}

	/**
	 * Stores a named route and returns it, optionally by namespace.
	 * The "action" will always be set to "index" if it is not defined.
	 *
	 * 	Route::set('default', '(<controller>(/<action>(/<id>)))', null, 'www')
	 * 		->defaults(array(
	 * 			'controller' => 'welcome',
	 * 	);
	 *
	 * @param string $name      route name
	 * @param string $uri       URI pattern
	 * @param array  $regex     regex patterns for route keys
	 * @param string $namespace the namespace to set this route to
	 *
	 * @return Route
	 */
	public static function set(
		$name, $uri, array $regex = null, $namespace = null
	)
	{
		if (null !== $namespace)
			return Route::$_routes[$namespace][$name] = new Route($uri, $regex);

		return Route::$_routes[$name] = new Route($uri, $regex);
	}

	/**
	 * Retrieves a named route optionally by a namespace.
	 *
	 * 	$route = Route::get('default', 'www');
	 *
	 * @param string $name      route name
	 * @param string $namespace the namespace to fetch from
	 *
	 * @return Route
	 * @throws Kohana_Exception
	 */
	public static function get($name, $namespace = null)
	{
		// make sure the namespace is loaded
		if (null !== $namespace AND ! isset(Route::$_routes[$namespace]))
			Route::load_namespace($namespace);

		if (null !== $namespace ? ! isset(
				Route::$_routes[$namespace][$name]
			) : ! isset(Route::$_routes[$name])
		)
		{
			throw new Kohana_Exception(
				'The requested route does not exist: :route',
				array(':route' => $name)
			);
		}

		return $namespace !== null
			? Route::$_routes[$namespace][$name]
			: Route::$_routes[$name];
	}

	/**
	 * Loads a route namespace into the routes array
	 *
	 * 	Route::load_namespace('www')
	 *
	 * @param string $namespace the namespace to load
	 *
	 * @return array
	 */
	public static function load_namespace($namespace)
	{
		return Route::$_routes[$namespace] = include Kohana::find_file(
			'routes', $namespace
		);
	}

	/**
	 * Clears a namespace if it's not needed anymore
	 *
	 *     Route::clear_namespace('www')
	 *
	 * @param string $namespace the namespace to clear
	 *
	 * @return null
	 */
	public static function clear_namespace($namespace)
	{
		unset(Route::$_routes[$namespace]);
	}

	/**
	 * Overloading all() to flatten our namespace to allow Requests to work
	 * properly. This could have unintended results.
	 *
	 * @param bool $flatten return the array flattened or as a multi-dim
	 *
	 * @return null
	 */
	public static function all($flatten = true)
	{
		return $flatten ? arr::flatten(Route::$_routes) : Route::$_routes;
	}
}

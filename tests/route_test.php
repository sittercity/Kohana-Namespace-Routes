<?php
/**
 * PHPUnit test for namespaced routes
 *
 * @package   Kohana-Namespace-Routes
 * @copyright 2010 Sittercity, Inc.
 */

defined('SYSPATH') or die('No direct script access.');

/**
 * Unit Test for namespaced routes
 *
 * @group Route_Module
 */
class Route_Test extends PHPUnit_Framework_TestCase
{
	/**
	 * Provides test data for test_load()
	 * 
	 * @return array
	 */
	public function provider_load()
	{
		return array(
			array(
				'www', array(
					'contact.html' => array(
						'controller' => 'cs_contact', 'action' => 'display'
					),
				)
			),
			array(
				'www', array(
					'help/main.html' => array(
						'controller' => 'cs_help', 'action' => 'display'
					)
				)
			),
			array(
				'www', array(
					'babysitters/il/chicago/jobs.html' => array(
						'controller' => 'seo_jobs', 'action' => 'index'
					)
				)
			),
			array(
				'parents', array(
					'my-sittercity.html' => array(
						'controller' => 'mysittercity', 'action' => 'index'
					)
				)
			),
			array(
				'trial', array(
					'my-sittercity.html' => array(
						'controller' => 'mysittercity', 'action' => 'index'
					)
				)
			),
		);
	}

	/**
	 * Test loading a namespace
	 *
	 * @param string $namespace the namespace to test
	 * @param array  $uris      an array of uris to test
	 * 
	 * @dataProvider provider_load
	 * 
	 * @return null
	 */
	public function test_load($namespace, $uris)
	{
		Route::load_namespace($namespace);

		foreach ($uris as $uri => $checks)
		{
			$request = Request::factory($uri);
			foreach ($checks as $property => $expected)
				$this->assertSame($expected, $request->$property);
		}

		Route::clear_namespace($namespace);
	}

	/**
	 * Provides test data for test_parameter()
	 * 
	 * @return array
	 */
	public function provider_parameter()
	{
		return array(
			array(
				'www', array(
					'babysitters/il/chicago/jobs.html' => array(
						'caretype_key' => 'babysitters', 'statecode' => 'il'
					)
				)
			),
		);
	}

	/**
	 * Test loading a namespace
	 *
	 * @param string $namespace the namespace to test
	 * @param array  $uris      an array of uris to test
	 * 
	 * @dataProvider provider_parameter
	 * 
	 * @return null
	 */
	public function test_parameter($namespace, $uris)
	{
		Route::load_namespace($namespace);

		foreach ($uris as $uri => $checks)
		{
			$request = Request::factory($uri);
			foreach ($checks as $parameter => $expected)
				$this->assertSame($expected, $request->param($parameter));
		}

		Route::clear_namespace($namespace);
	}

	/**
	 * Provides test data for test_get()
	 * 
	 * @return array
	 */
	public function provider_get()
	{
		return array(
			array('www', 'seo city page', array(
				'caretype_key' => 'babysitters',
				'statecode' => 'il',
				'city' => 'chicago'
			), 'babysitters/il/chicago.html'),
			array('www', 'unit test', array(), null),
		);
	}

	/**
	 * Test getting a route from a namespace
	 *
	 * @param string $namespace    the namespace to test
	 * @param string $name         the route name to use
	 * @param array  $params       an array of uris to test
	 * @param string $expected_uri what the destination url should be
	 * 
	 * @dataProvider provider_get
	 * @covers route::get
	 * 
	 * @return null
	 */
	public function test_get($namespace, $name, $params, $expected_uri)
	{
		try
		{
			$route = Route::get($name, $namespace);

			$this->assertEquals($expected_uri, $route->uri($params));
		}
		catch (Exception $e)
		{
			$this->assertSame(
				$e->getMessage(),
				'The requested route does not exist: '.$name
			);
		}
	}

	/**
	 * Provides test data for test_set()
	 * 
	 * @return array
	 */
	public function provider_set()
	{
		return array(
			array('www', 'unit test', 'unit/test.html', null),
			array(null, 'unit test', 'unit/test.html', null),
		);
	}

	/**
	 * Test setting a route to a namespace
	 *
	 * @param string $namespace the namespace to test
	 * @param string $name      the route name to use
	 * @param string $uri       the uri to set
	 * @param string $regex     the regex to use
	 * 
	 * @dataProvider provider_set
	 * @covers route::set
	 * 
	 * @return null
	 */
	public function test_set($namespace, $name, $uri, $regex)
	{
		$expected = Route::set($name, $uri, $regex, $namespace);

		$this->assertSame($expected, Route::get($name, $namespace));
	}
}

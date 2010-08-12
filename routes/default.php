<?php
/**
 * Route file for an application
 *
 * Syntax for this file:
 *
 * 	<route name> => Route::factory()
 *
 * @package   Kohana-Namespace-Routes
 * @copyright 2010 Sittercity, Inc.
 */

return array(
	'default' => Route::factory('(<uri>)', array('uri' => '(.++)'))->defaults(
		array( 'controller' => 'page', 'action' => 'index')
	),
);

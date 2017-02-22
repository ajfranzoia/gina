<?php

namespace Gina;

/**
 * Basic class for route parsing
 */
class Router {

	const DEFAULT_CONTROLLER = 'Default';

	const DEFAULT_ACTION = 'index';

	/**
	 * Parses the current request and returns basic route data.
	 * Splits url using slash as delimiter, and assigns first value as
	 * the controller, second argument as the action, and the rest of the values found
	 * as the route named parameters.
	 *
	 * Example: given the url '/posts/view/123', the result will be:
	 *
	 * array(
	 *    'controller' => 'posts',
	 *    'action' => 'view',
	 *    'namedParams' => [
	 *        '123'
	 *    ]
	 * )
	 *
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function parseRequest(Request $request) {
		$url = $request->get('url');

		// Explode url params by '/' using the provided url from .htaccess
        $urlParts = explode('/', $url);

        $controller = isset($urlParts[0]) ? $urlParts[0] : self::DEFAULT_CONTROLLER;
        $action = isset($urlParts[1]) ? lcfirst(Utils::toCamelCase($urlParts[1])) : self::DEFAULT_ACTION ;
        $namedParams = array_slice($urlParts, 2);

        return compact('controller', 'action', 'namedParams');
	}

}

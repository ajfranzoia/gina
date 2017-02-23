<?php

namespace Gina;

/**
 * Basic class for route parsing
 */
class Router {

	const DEFAULT_CONTROLLER = 'Default';

	const DEFAULT_ACTION = 'index';

    /**
     * @var Config
     */
    protected $config;

    /**
     * Initializes current request and response properties.
     *
     * @param Request $request
     */
	public function __construct(Config $config) {
        $this->config = $config;
	}

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
		$url = substr($request->getRequestUri(), 1);

		// If the matched url is the root path, return configured route in parameters.ini
		if ($url === '') {
			$rootRoute = explode('/', $this->config->get('routing')['root']);
			return [
				'controller' => $rootRoute[0],
				'action' => $rootRoute[1],
				'namedParams' => []
			];
		}

		// Explode url params by '/' using the provided url from .htaccess
        $urlParts = explode('/', $url);

        $controller = isset($urlParts[0]) ? $urlParts[0] : self::DEFAULT_CONTROLLER;
        $action = isset($urlParts[1]) ? lcfirst(Utils::toCamelCase($urlParts[1])) : self::DEFAULT_ACTION ;
        $namedParams = array_slice($urlParts, 2);

        return compact('controller', 'action', 'namedParams');
	}

}

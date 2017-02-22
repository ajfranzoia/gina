<?php

namespace Gina;

use Exception;

/**
 * Framework dispatcher that processes current request and calls the
 * requested action on the correct controller.
 */
class Dispatcher {

    /**
     * @var Router
     */
    private $router;

    /**
     * @var Response
     */
    private $response;

	/**
	 * Initialize dispatcher with router object and an empty response
	 */
	public function __construct() {
		$this->router = new Router();
		$this->response = new Response();
        $this->config = Config::loadFromIni(ROOT . DS . 'config' . DS . 'parameters.ini');
	}

    /**
     * Dispatches the given request, passing params to the matching controller/action.
     * If the controller or the action aren't found, an error will be raised.
     *
     * @param Request $request
     * @return void
     */
	public function dispatch(Request $request) {
		// Parse and get current route data
        $route = $this->router->parseRequest($request);

        // Obtain the corrent controller according to the route data
		$controller = $this->getController($route['controller'], $request);

        // Generate and send response
        $response = $this->getResponse($controller, $route);
        $response->send();
	}

    /**
     * Get the proper application controller instance that will handle the request
     *
     * @param string $name
     * @param Request $request
     * @return Controller Controller instance
     */
    protected function getController($name, $request)
    {
    	// Convert controller name from route to CamelCase version
		$controller = Utils::toCamelCase($name);
		$className = 'App\\Controller\\' . $controller . 'Controller';

		// Use reflection to obtain controller class
		try {
        	$reflection = new \ReflectionClass($className);
		} catch (\Exception $e) {
	        throw new Exception("Missing controller: $className");
		}

		// Check if controller class can be instantiated
        if ($reflection->isAbstract() || $reflection->isInterface()) {
	        throw new Exception("Controller $className cannot be instantiated");
        }

        // Return a new instance of the controller, passing the current request to constructor
        return $reflection->newInstance($request, $this->response, $this->config);
    }

    /**
     * Get response from controller given the current route.
     * Calls the controller action and assigns the result to the current response.
     * If the controller already returns a response, it will be returned.
     *
     * @param Controller $controller
     * @param array $route
     * @return Response $response
     */
    protected function getResponse(Controller $controller, $route)
    {
        $result = $controller->callAction($route['action'], $route['namedParams']);

        // If $result is already a Response object, return it
        if ($result instanceof Response) {
            return $result;
        }

        // Otherwise, set the given action result as the response body
        $this->response->setContent($result);

        return $this->response;
    }

}

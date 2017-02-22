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

        // Obtain the controller from the factory given the parsed route
        $controllerFactory = new ControllerFactory();
		$controller = $controllerFactory->loadController($route['controller'], [
            'request' => $request,
            'response' => $this->response,
            'config' => $this->config
        ]);

        // Initialize models library
        $this->initializeModels();

        // Generate and send response
        $response = $this->getResponse($controller, $route);
        $response->send();
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

    /**
     * Initializes ActiveRecord library.
     * Configures models directory and database connection based on
     * the config values parsed from parameters.ini.
     *
     * @return void
     */
    protected function initializeModels() {
        $databaseParams = $this->config->get('database');

        // If no database params configured, simply do nothing
        // Useful for apps with no database backend
        if (!$databaseParams) {
            return;
        }

        extract($databaseParams);

        \ActiveRecord\Config::initialize(function($cfg) use ($host, $user, $password, $database) {
            $cfg->set_model_directory(APP . DS . 'Model');
            $cfg->set_connections([
                'default' => "mysql://$user:$password@$host/$database",
            ]);
            $cfg->set_default_connection('default');
        });

        \ActiveRecord\Connection::$datetime_format = 'Y-m-d H:i:s';
    }

}

<?php

namespace Gina;

/**
 * Base application controller class that holds business logic and
 * provides action methods that will be called by the application dispatcher.
 * Actions must return a response, which can be an integer, string, or a full Response object.
 * Current request can be accessed via $this->request or as the last argument of the action.
 * Current response can be accessed via $this->response.
 */
class Controller {

    /**
     * Current request
     *
     * @var Request
     */
	private $request;

    /**
     * Current response
     *
     * @var Response
     */
    private $response;

    /**
     * Initializes current request and response properties.
     *
     * @param Request $request
     */
	public function __construct(Request $request, Response $response) {
		$this->request = $request;
		$this->response = $response;
	}

    /**
     * Calls the given controller action with the given namedParams if passed
     * and returns the given action response.
     *
     * @param  string $action
     * @param  array $namedParams
     * @return mixed
     */
	public function callAction($action, $namedParams = []) {
        // Obtain the current method using reflection and check if it's callable
        try {
            $method = new \ReflectionMethod($this, $action);
        } catch (\ReflectionException $e) {
            throw new Exception("Missing action $action");
        }

        if (!$method->isPublic()) {
            throw new Exception("Action $action is not public");
        }

        // Call controller method and get returned result
        $callable = [$this, $action];

        return call_user_func_array($callable, array_merge($namedParams, [$this->request]));
	}

}

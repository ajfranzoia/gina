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
     * Controller name
     *
     * @var string
     */
    protected $name;

    /**
     * Current request
     *
     * @var Request
     */
	protected $request;

    /**
     * Current response
     *
     * @var Response
     */
    protected $response;

    /**
     * @var View
     */
    protected $view;

    /**
     * @var Config
     */
    protected $config;

    /**
     * Current action
     *
     * @var string
     */
    protected $currentAction;

    /**
     * Initializes current request and response properties.
     *
     * @param Request $request
     */
	public function __construct(Request $request, Response $response, Config $config) {
		$this->request = $request;
		$this->response = $response;
        $this->config = $config;
        $this->view = new View();

        // Set controller name
        $classNameParts = explode('\\', get_called_class());
        $this->name = str_replace('Controller', '', end($classNameParts));
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
            throw new \Exception("Missing action $action");
        }

        if (!$method->isPublic()) {
            throw new \Exception("Action $action is not public");
        }

        $this->currentAction = $action;

        // Call controller method and get returned result
        $callable = [$this, $action];

        return call_user_func_array($callable, array_merge($namedParams, [$this->request]));
	}

    /**
     * Handles the rendering process through the View object for the
     * given template and context variables.
     * If $template is an array, it will be considered as $context, and the
     * template will be guessed from the current action.
     * Returns the compiled template by the view.
     *
     * @param  string|array $template
     * @param  array  $context
     * @return Response
     */
    public function render($template, $context = []) {
        if (is_array($template)) {
            $context = $template;
            $template = $this->currentAction;
        }

        $output = $this->view->render($this->name . DS . $template, $context);
        $this->response->setContent($output);

        return $this->response;
    }

    /**
     * Returns a JSON string representation for the given data as a response.
     * Sets the proper Content-Type header.
     *
     * @param  mixed $data
     * @return Response
     */
    protected function respondJson($data) {
        $this->response->headers->set('Content-Type', 'application/json');
        $this->response->setContent(json_encode($data));

        return $this->response;
    }

}

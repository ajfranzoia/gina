<?php

namespace Gina;

/**
 * Factory responsible for controller instantiation given
 * the controller name.
 */
class ControllerFactory {

	/**
	 * @var string
	 */
	const CLASS_NAMESPACE = 'App\\Controller\\';

    /**
     * Load the correct application controller instance that will handle the request
     * based on the given name.
     * Context requires Request, Response and Config objects to initialize controller.
     *
     * @param string $name
     * @param array $context
     * @return Controller Controller instance
     */
    public function loadController($name, $context)
    {
    	// Convert controller name from route to CamelCase version
		$controller = Utils::toCamelCase($name);
		$className = self::CLASS_NAMESPACE . $controller . 'Controller';

		// Use reflection to obtain controller class
		try {
        	$reflection = new \ReflectionClass($className);
		} catch (\Exception $e) {
	        throw new Exception("Missing controller: $className");
		}

		// Check if controller class can be instantiated
        if (!$this->canInstantiate($reflection)) {
	        throw new Exception("Controller $className cannot be instantiated");
        }

        // Return a new instance of the controller, passing the current request to constructor
        return $reflection->newInstance($context['request'], $context['response'], $context['config']);
    }

    /**
     * Returns true if given reflection class can be instantiated
     *
     * @param  \ReflectionClass $reflection
     * @return boolean
     */
    private function canInstantiate($reflection) {
		return !$reflection->isAbstract() && !$reflection->isInterface();
    }

}

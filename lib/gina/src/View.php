<?php

namespace Gina;

/**
 * View class that utilizes Twig to render templates.
 */
class View {

	/**
	 * Default folder name for views under ´src´ directory
	 */
	const VIEWS_DIR = 'View';

	/**
	 * Default extension for templates
	 */
	const DEFAULT_EXTENSION = 'twig';

	/**
	 * Renders the given template, providing the given context as view
	 * variables. Returns the compiled Twig template.
	 *
	 * @param  string $template
	 * @param  array  $context
	 * @return string
	 */
    public function render($template, $context = []) {
    	// Initialize Twig engine
		$loader = new \Twig_Loader_Filesystem([
			APP . DS . self::VIEWS_DIR,
			APP . DS . self::VIEWS_DIR . DS . 'Layout'
		]);
		$twig = new \Twig_Environment($loader);

		// Load and render the given template
		$twigTemplate = $twig->load($template . '.' . self::DEFAULT_EXTENSION);

		return $twigTemplate->render($context);
    }

}


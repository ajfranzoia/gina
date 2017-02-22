<?php

namespace Gina;

/**
 * Utility class
 */
class Utils {

	/**
	 * Converts a string to its CamelCase version
	 *
	 * @param  $str $string
	 * @return string
	 */
	public static function toCamelCase($str) {
		return str_replace(' ', '', ucwords(preg_replace('/[^a-z0-9]+/i', ' ', $str)));
	}

	/**
	 * Returns current UTC time in the format 'Y-m-d H:i:s'
	 *
	 * @return string
	 */
	public static function now() {
		return gmdate('Y-m-d H:i:s');
	}

}

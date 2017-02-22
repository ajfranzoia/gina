<?php

namespace Gina;

/**
 * Utility to hold and provide access to app wide configurations.
 */
class Config {

	/**
	 * Config values
	 *
	 * @var array
	 */
	private $values = [];

	/**
	 * Loads config values from an INI file and returns a class instance.
	 *
	 * @param  string $path
	 * @return Config
	 */
	public static function loadFromIni($path) {
		$instance = new self();

		$values = parse_ini_file($path, true);
		foreach ($values as $key => $value) {
			$instance->set($key, $value);
		}

		return $instance;
	}

	/**
	 * Sets a config value for a given key
	 *
	 * @param string $key
	 * @param string $value
	 */
	public function set($key, $value) {
		$this->values[$key] = $value;
	}

	/**
	 * Returns the config value for the given key.
	 * If key is not found, returns null.
	 *
	 * @param  string $key
	 * @return mixed
	 */
	public function get($key) {
		return isset($this->values[$key]) ? $this->values[$key] : null;
	}

}

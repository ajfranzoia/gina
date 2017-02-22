<?php

namespace App\Lib;

use Arrayzy\ArrayImitator as A;

/**
 * Utility class for FootbalData api that uses the Guzzle http client for requests.
 * The ´authToḱen´ and ´leagueId´ parameters must be configured under the database
 * section in parameters.ini.
 */
class FootballDataApi {

	/**
	 * @var  string
	 */
	const API_ROOT_URL = 'http://api.football-data.org/v1';

	/**
	 * @param Config $config
	 */
	public function __construct($config) {
		$this->config = $config;
	}

	/**
	 * Returns all the league teams sorted by name.
	 *
	 * @return array
	 */
	public function getTeams() {
		$client = $this->getHttpClient();
		$leagueId = $this->config['leagueId'];

		$result = $client->request('GET', self::API_ROOT_URL . "/competitions/$leagueId/teams", [
			'headers' => [
				'X-Auth-Token' => $this->config['authToken']
			]
		]);

		// Parse JSON response into teams array
		$teams = json_decode($result->getBody()->getContents(), true)['teams'];

		// Sort by name and return teams
		return (new A($teams))->customSort(function($a, $b) {
		    if ($a['name'] === $b['name']) {
		        return 0;
		    }

		    return ($a['name'] < $b['name']) ? -1 : 1;
		});
	}

	/**
	 * Create and return Guzzle http client
	 *
	 * @return \GuzzleHttp\Client
	 */
	private function getHttpClient() {
		return new \GuzzleHttp\Client();
	}

}

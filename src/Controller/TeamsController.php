<?php

namespace App\Controller;

use Gina\Controller;

/**
 * Teams controller
 */
class TeamsController extends Controller {

	/**
	 * Action that lists all the teams in the league
	 *
	 * @param  Request $request
	 * @return mixed
	 */
	public function index($request) {
		$leagueId = $this->config->get('apiLeagueId');

		$client = new \GuzzleHttp\Client();
		$res = $client->request('GET', "http://api.football-data.org/v1/competitions/$leagueId/teams", [
			'headers' => [
				'X-Auth-Token' => $this->config->get('apiAuthToken')
			]
		]);
		$result = $res->getBody()->getContents();

		return $this->render([
			'teams' => json_decode($result, true)['teams']
		]);
	}

	/**
	 * Action that shows data for a selected team
	 *
	 * @param  Request $request
	 * @return mixed
	 */
	public function view($id) {
		return $this->respondJson([
			'name' => 'Arsenal FC'
		]);
	}

}

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
		$client = new \GuzzleHttp\Client();
		$res = $client->request('GET', 'http://api.football-data.org/v1/competitions/426/teams', [
			'headers' => [
				'X-Auth-Token' => '4c905f042ef44b2583a96547fd4f2e2c'
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

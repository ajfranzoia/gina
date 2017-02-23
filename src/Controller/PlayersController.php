<?php

namespace App\Controller;

use Gina\Controller;
use App\Lib\FootballDataApi;

/**
 * Players controller
 */
class PlayersController extends Controller {

	/**
	 * Action that lists all the teams in the league
	 *
	 * @param  Request $request
	 * @return mixed
	 */
	public function index($request, $teamId) {
		$footbalDataConfig = $this->config->get('footbalData');
		$api = new FootballDataApi($footbalDataConfig);

		return $this->render([
			'teamId' => $teamId,
			'players' => $api->getPlayers($teamId)
		]);
	}

}

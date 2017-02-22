<?php

namespace App\Controller;

use Gina\Controller;
use Gina\Utils;
use App\Model\TeamFavorite;
use App\Lib\FootballDataApi;

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
		$footbalDataConfig = $this->config->get('footbalData');
		$api = new FootballDataApi($footbalDataConfig);

		return $this->render([
			'teams' => $api->getTeams(),
			'favorites' => TeamFavorite::find('all')
		]);
	}

	/**
	 * Action that adds a new favorite team
	 *
	 * @param  Request $request
	 * @param  string $teamId
	 * @return mixed
	 */
	public function addFavorite($request, $teamId = null) {
		if (!$request->isMethod('post')) {
			throw new \Exception('This action requires POST', 400);
		}

		if (!$teamId) {
			throw new \Exception('Missing team id', 400);
		}

		$favorite = TeamFavorite::find_by_team_id($teamId);

		if ($favorite) {
			return $this->respondJson(['result' => true]);
		}

		TeamFavorite::create([
			'team_id' => $teamId,
			'assigned' => new \DateTime()
		]);

		return $this->respondJson(['result' => true]);
	}

	/**
	 * Action that removes an existent favorite team
	 *
	 * @param  Request $request
	 * @param  string $teamId
	 * @return mixed
	 */
	public function removeFavorite($request, $teamId = null) {
		if (!$request->isMethod('post')) {
			throw new \Exception('This action requires POST', 400);
		}

		if (!$teamId) {
			throw new \Exception('Missing team id', 400);
		}

		$favorite = TeamFavorite::find_by_team_id($teamId);

		if (!$favorite) {
			return $this->respondJson(['result' => true]);
		}

		$favorite->delete();

		return $this->respondJson(['result' => true]);
	}

}

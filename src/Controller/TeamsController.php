<?php

namespace App\Controller;

use Gina\Controller;
use Gina\Utils;
use App\Model\TeamFavorite;
use App\Lib\FootballDataApi;
use Arrayzy\ArrayImitator as A;

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

		$teams = $api->getTeams();
		$favoritesIds = TeamFavorite::getAllIds();

		return $this->render([
			'favorites' => $teams->filter(function($t) use ($favoritesIds) {
				return in_array($t['id'], $favoritesIds);
			}),
			'other' => $teams->filter(function($t) use ($favoritesIds) {
				return !in_array($t['id'], $favoritesIds);
			})
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

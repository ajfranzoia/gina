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
			return $this->getInvalidMethodResponse();
		}

		if (!$teamId) {
			return $this->getMissingTeamIdResponse();
		}

		$favorite = TeamFavorite::find_by_team_id($teamId);

		if ($favorite) {
			return $this
				->respondJson([
					'result' => false,
					'error' => 'Team is already set as favorite'
				])
				->setStatusCode(400);
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
			return $this->getInvalidMethodResponse();
		}

		if (!$teamId) {
			return $this->getMissingTeamIdResponse();
		}

		$favorite = TeamFavorite::find_by_team_id($teamId);

		if (!$favorite) {
			return $this
				->respondJson([
					'result' => false,
					'error' => 'Team is already set as non favorite'
				])
				->setStatusCode(400);
		}

		$favorite->delete();

		return $this->respondJson(['result' => true]);
	}

	/**
	 * Returns proper 405 response for non POST requests
	 *
	 * @return Response
	 */
	protected function getInvalidMethodResponse() {
		return $this
			->respondJson([
				'result' => false,
				'error' => 'This action requires POST'
			])
			->setStatusCode(405);
	}

	/**
	 * Returns proper 400 response for request that lack a team id
	 *
	 * @return Response
	 */
	protected function getMissingTeamIdResponse() {
		return $this
			->respondJson([
				'result' => false,
				'error' => 'Missing team id'
			])
			->setStatusCode(400);
	}

}

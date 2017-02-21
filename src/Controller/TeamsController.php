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
		return 'Hello! I am the index action in the TeamsController.';
	}

}

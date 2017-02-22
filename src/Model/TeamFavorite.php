<?php

namespace App\Model;

use Gina\Model;
use Arrayzy\ArrayImitator as A;

/**
 * Favorite teams model representation
 * It counts with the following fields:
 *   - id (int)
 *   - team_id (int)
 *   - assigned (DateTime)
 */
class TeamFavorite extends Model {

	/**
	 * Table name in database
	 *
	 * @var string
	 */
    static $table_name = 'teams_favorites';

    /**
     * Return all favorite team ids as array
     *
     * @return array
     */
    public static function getAllIds() {
    	$favorites = new A(TeamFavorite::find('all'));

    	return $favorites->reduce(function($result, $item) {
    		$result[] = $item->team_id;
    		return $result;
    	}, []);
    }

}

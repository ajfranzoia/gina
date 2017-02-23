(function() {

  $(initializeApp);

  var $teams = $('#teams');

  /**
   * Sets event handlers.
   *
   * @return undefined
   */
  function initializeApp() {
  	$teams.on('click', '.team-favorite-toggle', toggleFavorite);
    $teams.on('click', '.show-players', showPlayers);
  }

  /**
   * Toggle team's favorite status
   *
   * @param  {Event} event
   * @return undefined
   */
  function toggleFavorite(event) {
  	event.preventDefault();

  	var $favoriteToggle = $(event.currentTarget);

  	// Do nothing if a change is already on process
  	if ($favoriteToggle.hasClass('working')) {
  		return;
  	}

  	var $favoriteToggleIcon = $favoriteToggle.find('i');

  	// Get team data and generate request url
  	var $team = $(event.currentTarget).closest('.team');
  	var teamId = $team.data('team-id');
  	var isFavorite = $team.data('is-favorite');
  	var action = isFavorite ? 'remove_favorite' : 'add_favorite';
  	var url = '/teams/' + action  + '/' + teamId;

  	$favoriteToggle.addClass('working');

  	// Make post request to set/unset favorite status
  	$.ajax(url, {
  		method: 'post'
  	})
		  .done(function(data) {
		  	// Update element attributes
		  	$team.data('is-favorite', !isFavorite);
		  	$team.attr('data-is-favorite', !isFavorite);
  			$favoriteToggle.removeClass('working');
  			$favoriteToggleIcon.toggleClass('fa-star fa-star-o');

  			// Finally sort teams and update UI
  			sortTeams();
		  });
  }

  /**
   * Sorts teams by favorite status and then by name.
   *
   * @return undefined
   */
  function sortTeams() {
  	var sortedTeams = $('.team').sort(function (a, b) {
  		var $teamA = $(a);
  		var $teamB = $(b);

  		var favoriteOrder = $teamB.data('is-favorite') - $teamA.data('is-favorite');
  		var nameOrder = $teamA.data('team-name') > $teamB.data('team-name') ? 1 : -1;

  		if (favoriteOrder) {
  			return favoriteOrder;
  		}

  		return nameOrder;
	  });

  	// Empty teams container and append teams
	  $teams.empty();
		$.each(sortedTeams, function (index, value) {
		  $teams.append(value);
		});
  }

  /**
   * Show team players
   *
   * @param  {Event} event
   * @return undefined
   */
  function showPlayers(event) {
    event.preventDefault();

    var $showBtn = $(event.currentTarget);

    // Do nothing if a change is already on process
    if ($showBtn.is('disabled')) {
      return;
    }

    $showBtn.attr('disabled', true);
    $showBtn.html('Loading...');

    // Get team data and generate request url
    var $team = $(event.currentTarget).closest('.team');
    var $playersContainer = $team.find('.team-players');
    var teamId = $team.data('team-id');
    var url = '/players/index/' + teamId;

    // Make post request to set/unset favorite status
    $.ajax(url, {
      method: 'get'
    })
      .done(function(data) {
        $playersContainer.html(data);
      });
  }

})();

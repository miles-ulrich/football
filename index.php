<?php include 'config.php'; ?>

<h2>Your Leagues</h2>

<?php

$xml = $session->fantasy("http://fantasysports.yahooapis.com/fantasy/v2/users;use_login=1/games;game_key=nfl/leagues/teams");

foreach ($xml->users->user->games->game->leagues->league as $league) {
  krumo($league);
  print "<h3><a href='/load-league.php?league={$league->league_id}'>{$league->name}: {$league->teams->team->name}</a></h3>";
}

?>

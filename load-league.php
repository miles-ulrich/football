<?php include 'config.php'; ?>

<?php

$conn = new Mongo();
$db = $conn->football;
$leagues = $db->leagues;

$league_id = $_GET['league'];

if ($leagues->count(array('league_id' => $league_id))) {
  header("Location: /global-leagues?league=$league_id");
  exit;
}

$xml = $session->fantasy("http://fantasysports.yahooapis.com/fantasy/v2/league/nfl.l.{$league_id}/teams");

$league = array(
	'name' => (string)$xml->league->name,
	'league_key' => (string)$xml->league->league_key,
	'league_id' => (string)$xml->league->league_id,
);

$weeks = range($xml->league->start_week, $xml->league->current_week - 1);

krumo($xml);

foreach ($xml->league->teams->team as $team) {
  $league['teams'][(string)$team->team_id]['name'] = (string)$team->name;
  foreach ($weeks as $week) {
    $team_xml = $session->fantasy("http://fantasysports.yahooapis.com/fantasy/v2/team/242.l.{$league_id}.t.{$team->team_id}/roster;week={$week}/players/stats;type=week;week={$week}");
    $result = efficiency($team_xml->team);
    $league['teams'][(string)$team->team_id]['weeks'][$week] = $result;
  }
}

krumo($league);

$leagues->insert($league);

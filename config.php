<?php

require 'yos-social-php/lib/Yahoo.inc';

error_reporting(E_ALL);
ini_set('display_errors', 'On');

define('CONSUMER_KEY', "dj0yJmk9cTRuSkNPN2c3N1ZEJmQ9WVdrOWRWZzNiWE42TmpJbWNHbzlNakV4TURBNE5EVTJNZy0tJnM9Y29uc3VtZXJzZWNyZXQmeD0wNQ--");
define('CONSUMER_SECRET', "25a23bbddc2b90129a91bad17052236a86649c6d");
define('APPID', "uX7msz62");

include('krumo/class.krumo.php');

YahooLogger::setDebug(true);

$session = YahooSession::requireSession(CONSUMER_KEY,CONSUMER_SECRET,APPID);

function player_cmp($a, $b) { return $a[0] < $b[0]; }

function efficiency($team) {

  $actual_score = 0;
  $playing = array();
  $benched = array();
  $flex = array();
  $qb = 0;
  $te = 0;
  $def = 0;
  $k = 0;

  foreach ($team->roster->players->player as $player) {
    $score = (float)$player->player_points->total;
    if ($player->selected_position->position == 'BN') {
      $benched[] = $player->name->full.": $score";
    } else {
      $playing[] = $player->name->full.": $score";
      $actual_score += $score;
    }
    foreach ($player->eligible_positions as $position) {
      switch ($position->position) {
      case 'QB': if ($score > $qb) { $qb = $score; } break;
      case 'K': if ($score > $k) { $k = $score; } break;
      case 'TE': if ($score > $te) { $te = $score; } break;
      case 'DEF': if ($score > $def) { $def = $score; } break;
      case 'RB': case 'WR': $flex[] = array($score, (string)$position->position, $player->position.$player->player_id); break; }
    }
  }

  uasort($flex, 'player_cmp');

  $flex_ideal = 0.0;
  $used_rbs = 0;
  $used_wrs = 0;
  $used_ids = array();

  foreach ($flex as $player) {
    if ($used_wrs + $used_rbs >= 5) { break; }
    if ($player[1] == 'RB' && $used_rbs < 3 && !in_array($player[2], $used_ids)) {
      $flex_ideal += $player[0];
      $used_ids[] = $player[2];
      $used_rbs++;
    } elseif ($player[1] == 'WR' && $used_wrs < 3 && !in_array($player[2], $used_ids)) {
      $flex_ideal += $player[0];
      $used_ids[] = $player[2];
      $used_wrs++;
    }
  }

  $the_ideal = $qb + $k + $te + $def + $flex_ideal;

  return array(
    'actual' => $actual_score,
    'ideal' => $the_ideal
  );

}

?>

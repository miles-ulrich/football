<?php include 'krumo/class.krumo.php'; ?>

<?php

function show_table($league) {
  //krumo($league);
  echo "<table class='league'>";
  echo "<thead>";
  echo "<tr>";
  echo "<th>Team</th>";
  $top = reset($league['teams']);
  $num_weeks = count($top['weeks']);
  foreach ($top['weeks'] as $i => $week) {
    echo "<th>Wk $i</th>";
  }
  echo "<th>Avg</th>";
  echo "</tr>";
  echo "</thead>";
  echo "<tbody>";
  foreach ($league['teams'] as $tid => $team) {
    echo "<tr><td class='team-name'>{$team['name']}</td>";
    $total = 0;
    foreach ($team['weeks'] as $week => $result) {
      $performance = $result['actual'] / $result['ideal'];
      $total += $performance;
      $formatter = ($performance < 0.9999999) ? "%.1f" : "%.0f";
      echo "<td>".sprintf($formatter, $performance * 100)."</td>";
    }
    $total /= $num_weeks;
    echo "<td>".sprintf("%.3f", $total * 100)."</td>";
    echo "</tr>";
  }
  echo "</tbody>";
  echo "</table>";
}

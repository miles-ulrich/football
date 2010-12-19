<!DOCTYPE html>

<head>
  <title>Leagues</title>
  <meta charset="UTF-8">

<!--
-->
  <link rel="stylesheet" href="DataTables/media/css/demo_table_jui.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="tron/css/custom-theme/jquery-ui-1.8.7.custom.css" type="text/css" media="screen" />

  <script type="text/javascript" src="DataTables/media/js/jquery.js"></script>
  <script type="text/javascript" src="DataTables/media/js/jquery.dataTables.js"></script>
</head>

<body>
<?php

include 'display.php';

$conn = new Mongo();
$db = $conn->football;
$leagues = $db->leagues;
$query = isset($_GET['league']) ? array('league_id' => $_GET['league']) : array();
$cursor = $leagues->find($query);
$teams = array();
foreach ($cursor as $i => $league) {
  if (!$teams) {
    $teams = $league;
  } else {
    $teams['teams'] = array_merge($teams['teams'], $league['teams']);
  }
}

show_table($teams);

?>

<script type="text/javascript">
$(document).ready(function() {
  $('.league').dataTable( {
    bJQueryUI: true,
    aaSorting: [[15,'desc']],
    aaColumns: [null, null, null]
  });
});
</script>

</body>

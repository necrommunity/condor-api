<?php
header('Content-Type: application/json');

if (!empty($_GET["schema"])) {
  $schema = $_GET["schema"];
} else {
  $configFile = '/home/bot/necrobot/data/condorbot_config';
  $configData = file_get_contents($configFile);
  $rows = explode("\n", $configData);

  $configArray = [];
  foreach ($rows as $row) {
    $columns = explode('=',$row);
    $configArray[$columns[0]] = $columns[1];
  }
  $schema = $configArray['league_name'];
}

$eventData = array(
	event_name => NULL,
	schema_name => $schema,
	character => NULL,
	deadline => NULL,
	is_best_of => NULL,
	number_of_races => NULL,
	racers => array(),
	matches => array()
);

$pdo = new PDO("mysql:host=localhost",'necrobot-read','necrobot-read');
$stmt = $pdo->prepare("show databases;");
$stmt->execute();
$databases = $stmt->fetchAll(PDO::FETCH_COLUMN,'Database');

if (in_array($schema,$databases)){
  $pdo = new PDO("mysql:host=localhost;dbname=$schema",'necrobot-read','necrobot-read');
  $stmt = $pdo->prepare("SELECT m.match_id, DATE_FORMAT(suggested_time, '%Y-%m-%dT%TZ') 'suggested_time', a.rtmp_name 'racer_1', d.racer_1_wins 'racer_1_wins', b.rtmp_name 'racer_2', d.racer_2_wins 'racer_2_wins', c.twitch_name 'commentary', DATE_FORMAT(finish_time, '%Y-%m-%dT%TZ') 'finish_time' FROM matches m INNER JOIN necrobot.users a ON a.user_id=racer_1_id INNER JOIN necrobot.users b ON b.user_id=racer_2_id LEFT JOIN necrobot.users c ON c.user_id=cawmentator_id LEFT JOIN match_info d ON d.match_id=m.match_id WHERE r1_confirmed = 1 AND r2_confirmed = 1 ORDER BY suggested_time ASC;");
  $stmt->execute();
  $matches = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $eventData['matches'] = $matches;



  $stmt = $pdo->prepare("SELECT league_name, a.character 'character', is_best_of, number_of_races, deadline FROM league_info INNER JOIN necrobot.race_types a ON a.type_id = race_type;");
  $stmt->execute();
  $league_info = $stmt->fetch(PDO::FETCH_ASSOC);

  $eventData['deadline'] = $league_info['deadline'];
  $eventData['is_best_of'] = $league_info['is_best_of'];
  $eventData['number_of_races'] = $league_info['number_of_races'];
  $eventData['event_name'] = $league_info['league_name'];
  $eventData['character'] = $league_info['character'];

  $stmt = $pdo->prepare("select a.rtmp_name 'rtmp', a.twitch_name 'twitch', a.discord_name 'discord' from entrants INNER JOIN necrobot.users a ON a.user_id = entrants.user_id;");
  $stmt->execute();
  $eventData['racers'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

} else {
  echo "<pre>Input schema is not available.</pre>";
}
echo json_encode($eventData,JSON_PRETTY_PRINT);
//echo '<pre>',json_encode($league_info,JSON_PRETTY_PRINT),'</pre>';
//echo $eventData['event_name']; 
?>

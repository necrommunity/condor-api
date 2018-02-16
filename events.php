<?php

header('Content-Type: application/json');
$events = array();

$pdo = new PDO("mysql:host=localhost",'necrobot-read','necrobot-read');
$stmt = $pdo->prepare("SELECT SCHEMA_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME NOT IN ('information_schema', 'performance_schema', 'necrobot', 'mysql');");$stmt->execute();
$events['events'] = $stmt->fetchAll(PDO::FETCH_COLUMN,'Database');

echo json_encode($events,JSON_PRETTY_PRINT);

?>

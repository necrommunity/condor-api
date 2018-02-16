<?php

header('Content-Type: application/json');
$events = array();

$pdo = new PDO("mysql:host=localhost",'necrobot-read','necrobot-read');
$stmt = $pdo->prepare("SELECT DISTINCT TABLE_SCHEMA FROM information_schema.tables WHERE TABLE_SCHEMA NOT LIKE '%schema%' AND TABLE_SCHEMA NOT LIKE 'necrobot';");
$stmt->execute();
$events['events'] = $stmt->fetchAll(PDO::FETCH_COLUMN,'Database');

echo json_encode($events,JSON_PRETTY_PRINT);

?>

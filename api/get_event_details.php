<?php
include "../config/db_connect.php";

if(!isset($_GET['id'])){
    echo json_encode([]);
    exit();
}

$event_id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM events WHERE event_id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

echo json_encode($event);
?>
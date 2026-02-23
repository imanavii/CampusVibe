<?php
header("Content-Type: application/json");
include "../config/db_connect.php";

$sql = "SELECT event_id, event_title, event_date, event_time, event_location, venue, category, event_image 
        FROM events 
        ORDER BY created_at DESC";

$result = $conn->query($sql);

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

echo json_encode($events);
?>

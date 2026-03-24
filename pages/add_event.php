<?php
include '../config/db_connect.php';

$title = $_POST['event_title'];
$description = $_POST['event_description'];
$date = $_POST['event_date'];
$time = $_POST['event_time'];
$event_category = $_POST['category'];
$location = $_POST['event_location'];
$venue = $_POST['venue'];
$category = $_POST['category'];
$organizer = $_POST['organizer_name'];

$sql = "INSERT INTO events (event_title, event_description, event_date, event_time, event_location, venue, category, organizer_name) 
        VALUES ('$title', '$description', '$date', '$time', '$location', '$venue', '$category', '$organizer')";

$conn->query($sql);

header("Location: ../templates/admin.php");
exit();
?>
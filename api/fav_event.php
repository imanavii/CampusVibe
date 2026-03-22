<?php
session_start();
include "../config/db_connect.php";

if(!isset($_SESSION['user_id'])){
    header("Location: /campusvibe/pages/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$event_id = $_POST['event_id'];
$redirect = $_POST['redirect'] ?? 'dashboard.php';

// check if already favorited
$check = $conn->prepare("SELECT favorite_id FROM event_favorites WHERE user_id = ? AND event_id = ?");
$check->bind_param("ii", $user_id, $event_id);
$check->execute();
$check->store_result();

if($check->num_rows > 0){
    $delete = $conn->prepare("DELETE FROM event_favorites WHERE user_id = ? AND event_id = ?");
    $delete->bind_param("ii", $user_id, $event_id);
    $delete->execute();
} else {
    $insert = $conn->prepare("INSERT INTO event_favorites (user_id, event_id) VALUES (?, ?)");
    $insert->bind_param("ii", $user_id, $event_id);
    $insert->execute();
}

header("Location: /campusvibe/pages/" . $redirect);
exit();
?>
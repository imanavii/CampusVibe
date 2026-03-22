<?php
session_start();
include "../config/db_connect.php";

if(!isset($_SESSION['user_id'])){
    header("Location: ../templates/login.php");
    exit();
}

// Fetch events
$query = "SELECT * FROM events ORDER BY event_date ASC";
$result = $conn->query($query);

// Group events by category
$events = [
    "cultural" => [],
    "technical" => [],
    "sports" => [],
    "music" => [],
    "workshops" => [],
    "hosted_by_departments" => []
];

while($row = $result->fetch_assoc()){
    if(array_key_exists($row['category'], $events)){
        $events[$row['category']][] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - CampusVibe</title>
    <link rel="stylesheet" href="../assets/css/events.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<header class="navbar">
    <div class="nav-logo">
        <img src="../assets/images/logo.png" alt="CampusVibe">
    </div>
    <div class="nav-links">
        <a href="dashboard.php">Dashboard</a>
        <a href="events.php" class="active">Events</a>
    </div>
    <div class="nav-profile">
        <a href="profile.php">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
        </a>
    </div>
</header>

<div class="container">
    <h2>Upcoming Events</h2>

    <?php foreach($events as $category => $eventList): ?>
        <?php if(!empty($eventList)): ?>
        <div class="section">
            <h3><?php echo ucfirst(str_replace('_', ' ', $category)); ?></h3>

            <div class="card-container">
                <?php foreach($eventList as $event): ?>
                <div class="card">
                    <?php
                    $images = glob($_SERVER['DOCUMENT_ROOT'] . "/campusvibe/assets/images/" . $event['category'] . "/*.{jpg,jpeg,png}", GLOB_BRACE);
                    $random_img = !empty($images) ? "../assets/images/" . $event['category'] . "/" . basename($images[array_rand($images)]) : null;
                    ?>
                    <?php if($random_img): ?>
                        <img src="<?php echo $random_img; ?>" alt="event" class="event-img">
                    <?php else: ?>
                        <div class="event-img-placeholder"></div>
                    <?php endif; ?>

                    <div class="card-content">
                        <h4><?php echo htmlspecialchars($event['event_title']); ?></h4>
                        <p>📍 <?php echo htmlspecialchars($event['event_location']); ?></p>
                        <p>📅 <?php echo date('M d, Y', strtotime($event['event_date'])); ?></p>
                        <button onclick="fetchEvent(<?php echo $event['event_id']; ?>)">View More</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    <?php endforeach; ?>

    
</div>

<!-- EVENT DETAIL MODAL -->
<div id="eventModal" class="modal-overlay" onclick="closeModal()">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h2 id="modal-title"></h2>
            <button class="modal-close" onclick="closeModal()">✕</button>
        </div>
        <hr>
        <img id="modal-img" src="" alt="" class="modal-img" style="display:none;">
        <div class="modal-body">
            <p id="modal-desc" class="modal-desc"></p>
            <div class="modal-details">
                <p><span class="label">📅 Date</span> <span id="modal-date"></span></p>
                <p><span class="label">⏰ Time</span> <span id="modal-time"></span></p>
                <p><span class="label">📍 Location</span> <span id="modal-location"></span></p>
                <p><span class="label">🏛️ Venue</span> <span id="modal-venue"></span></p>
                <p><span class="label">👤 Organizer</span> <span id="modal-organizer"></span></p>
            </div>
            <a id="modal-register" href="#" target="_blank" class="btn-modal-register">Register</a>
        </div>
    </div>
</div>

<script>
function fetchEvent(id){
    fetch('../api/get_event_details.php?id=' + id)
    .then(res => res.json())
    .then(event => {
        document.getElementById('modal-title').innerText = event.event_title;
        document.getElementById('modal-desc').innerText = event.event_description;
        document.getElementById('modal-date').innerText = event.event_date;
        document.getElementById('modal-time').innerText = event.event_time;
        document.getElementById('modal-location').innerText = event.event_location;
        document.getElementById('modal-venue').innerText = event.venue;
        document.getElementById('modal-organizer').innerText = event.organizer_name;

        const regLink = event.registration_link;
        if(regLink){
            const link = regLink.startsWith('http') ? regLink : 'https://' + regLink;
            document.getElementById('modal-register').href = link;
        } else {
            document.getElementById('modal-register').href = '#';
        }

        document.getElementById('eventModal').style.display = 'flex';
    });
}

function closeModal(){
    document.getElementById('eventModal').style.display = 'none';
}
</script>

</body>
</html>
<?php
session_start();
include "../config/db_connect.php";

if(!isset($_SESSION['user_id'])){
    header("Location: ../pages/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// fetch user
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// fetch favorite events
$fav_query = "
    SELECT e.* FROM event_favorites f
    JOIN events e ON f.event_id = e.event_id
    WHERE f.user_id = '$user_id'
    ORDER BY f.favorited_at DESC
";
$fav_result = $conn->query($fav_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Campus Vibe</title>
    <link rel="stylesheet" href="../assets/css/profile.css">
</head>
<body>

<header class="topbar">
    <div class="logo">
        <img src="../assets/images/logo.png" alt="Campus Vibe Logo">
    </div>
</header>

<div class="container">

   <aside class="sidebar">
    <div class="profile-pic">
        <?php
        $profile_img = (!empty($user['profile_image']) && $user['profile_image'] != 'default_user.png') 
            ? "../assets/images/profile_image/" . htmlspecialchars($user['profile_image']) 
            : "../assets/images/default_user.png";
        ?>
        <img src="<?php echo $profile_img; ?>" alt="Profile Picture">
    </div>


    <nav class="menu">
        <a href="dashboard.php">Dashboard</a>
        <a href="events.php">Events</a>
        <a href="/campusvibe/api/logout.php">Logout</a>
    </nav>
</aside>

    <main class="main">

        <h2>PROFILE</h2>

        <div class="card profile-info">
            <div class="info-row">
                Name: <span><?php echo htmlspecialchars($user['name']); ?></span>
            </div>
            <div class="info-row">
                Email ID: <span><?php echo htmlspecialchars($user['email']); ?></span>
            </div>
            <div class="info-row">
                Phone: <span><?php echo htmlspecialchars($user['phone']); ?></span>
            </div>
            <div class="info-row">
                User ID: <span><?php echo htmlspecialchars($user['user_id']); ?></span>
            </div>
        </div>

        <h2>MY EVENTS ❤️</h2>

      <div class="card-container">
    <?php if($fav_result && $fav_result->num_rows > 0): ?>
        <?php while($event = $fav_result->fetch_assoc()): ?>
        <div class="card">
            <?php
            $prof_images = glob($_SERVER['DOCUMENT_ROOT'] . "/campusvibe/assets/images/" . $event['category'] . "/*.{jpg,jpeg,png}", GLOB_BRACE);
            $prof_img = !empty($prof_images) ? "../assets/images/" . $event['category'] . "/" . basename($prof_images[array_rand($prof_images)]) : null;
            ?>
            <?php if($prof_img): ?>
                <img src="<?php echo $prof_img; ?>" alt="event">
            <?php else: ?>
                <div class="event-img-placeholder"></div>
            <?php endif; ?>
                    <div class="card-content">
                        <h4><?php echo htmlspecialchars($event['event_title']); ?></h4>
                        <p>📍 <?php echo htmlspecialchars($event['event_location']); ?></p>
                        <p>📅 <?php echo date("M d, Y", strtotime($event['event_date'])); ?></p>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No favorite events yet.</p>
            <?php endif; ?>
        </div>

    </main>
</div>

</body>
</html>
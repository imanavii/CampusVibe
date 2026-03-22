<?php
session_start();
include "../config/db_connect.php";

// protect page - must be logged in
if(!isset($_SESSION['user_id'])){
    header("Location: ../templates/login.php");
    exit();
}

$user_name = $_SESSION['name'];
$user_id = $_SESSION['user_id'];

// get category filter
$category = isset($_GET['category']) ? $_GET['category'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// fetch "Events Near You" - upcoming events
$query = "SELECT * FROM events WHERE event_date >= CURDATE()";
if($category != ''){
    $query .= " AND category = '$category'";
}
if($search != ''){
    $query .= " AND (event_title LIKE '%$search%' OR event_location LIKE '%$search%')";
}
$query .= " ORDER BY event_date ASC";
$events_result = $conn->query($query);

// fetch "Must Attend" - most recent/featured events (different category or latest)
$must_attend_result = $conn->query("SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY created_at DESC LIMIT 3");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CampusVibe</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="nav-logo">
        <img src="../assets/images/logo.png" alt="CampusVibe">
    </div>
    <div class="nav-links">
        <a href="dashboard.php" class="active">Dashboard</a>
        <a href="events.php">Events</a>
    </div>
    <div class="nav-profile">
        <a href="profile.php">
            <div class="profile-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </div>
        </a>
    </div>
</nav>

<!-- MAIN CONTENT -->
<main class="main">

    <!-- GREETING -->
    <h1 class="greeting">Hello <?php echo htmlspecialchars($user_name); ?>!</h1>

    <!-- SEARCH + FILTERS -->
    <div class="search-filter-row">
        <form method="GET" action="" class="search-form">
            <div class="search-box">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" name="search" placeholder="Search" value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="filter-tags">
                <a href="?category=" class="tag <?php echo $category == '' ? 'active' : ''; ?>">🏆 Trending</a>
                <a href="?category=cultural" class="tag <?php echo $category == 'cultural' ? 'active' : ''; ?>">🎉 Cultural</a>
                <a href="?category=technical" class="tag <?php echo $category == 'technical' ? 'active' : ''; ?>">💻 Tech</a>
                <a href="?category=music" class="tag <?php echo $category == 'music' ? 'active' : ''; ?>">🎵 Music</a>
                <a href="?category=sports" class="tag <?php echo $category == 'sports' ? 'active' : ''; ?>">🏅 Sports</a>
            </div>
        </form>
    </div>

    <!-- TWO COLUMN LAYOUT -->
    <div class="content-grid">

        <!-- LEFT: EVENTS NEAR YOU -->
        <section class="events-section">
            <h2>Events Near You!</h2>

            <?php if($events_result && $events_result->num_rows > 0): ?>
                <?php while($event = $events_result->fetch_assoc()): ?>
                <div class="event-card">
                    <?php
                    $images = glob($_SERVER['DOCUMENT_ROOT'] . "/campusvibe/assets/images/" . $event['category'] . "/*.{jpg,jpeg,png}", GLOB_BRACE);
                    $random_img = !empty($images) ? "../assets/images/" . $event['category'] . "/" . basename($images[array_rand($images)]) : null;
                    ?>
                    <?php if($random_img): ?>
                        <img src="<?php echo $random_img; ?>" alt="event" class="event-img">
                    <?php else: ?>
                        <div class="event-img-placeholder"></div>
                        <?php endif; ?>

                    <div class="event-info">
                        <h3><?php echo htmlspecialchars($event['event_title']); ?></h3>
                        <p class="event-date">
                            📅 <?php echo date("M d, Y", strtotime($event['event_date'])); ?> 
                            &bull; <?php echo date("g:i A", strtotime($event['event_time'])); ?>
                        </p>
                        <p class="event-location">📍 <?php echo htmlspecialchars($event['event_location']); ?></p>

                       <div class="card-actions">
                        <?php if($event['registration_link']): ?>
                         <?php
                          $reg_link = $event['registration_link'];
                           if(!preg_match("~^(?:f|ht)tps?://~i", $reg_link)){
            $reg_link = "https://" . $reg_link;
        }
        ?>
        <a href="<?php echo htmlspecialchars($reg_link); ?>" target="_blank" class="btn-register">Register</a>
    <?php else: ?>
        <button class="btn-register">Register</button>
    <?php endif; ?>

    <?php
    $fav_check = $conn->prepare("SELECT favorite_id FROM event_favorites WHERE user_id = ? AND event_id = ?");
    $fav_check->bind_param("ii", $user_id, $event['event_id']);
    $fav_check->execute();
    $fav_check->store_result();
    $is_faved = $fav_check->num_rows > 0;
    ?>
    <form method="POST" action="/campusvibe/api/fav_event.php" style="margin:0;">
        <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">
        <input type="hidden" name="redirect" value="dashboard.php">
        <button type="submit" class="btn-fav"><?php echo $is_faved ? '❤️' : '🤍'; ?></button>
    </form>
</div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-events">No events found.</p>
            <?php endif; ?>
        </section>

        <!-- RIGHT: MUST ATTEND -->
        <section class="must-attend-section">
            <h2>Must Attend!</h2>

            <?php if($must_attend_result && $must_attend_result->num_rows > 0): ?>
                <?php while($event = $must_attend_result->fetch_assoc()): ?>
                <div class="must-card">
                    <?php
$must_images = glob($_SERVER['DOCUMENT_ROOT'] . "/campusvibe/assets/images/" . $event['category'] . "/*.{jpg,jpeg,png}", GLOB_BRACE);
$must_random_img = !empty($must_images) ? "../assets/images/" . $event['category'] . "/" . basename($must_images[array_rand($must_images)]) : null;
?>

<?php if($must_random_img): ?>
    <img src="<?php echo $must_random_img; ?>" alt="event" class="must-img">
<?php else: ?>
    <div class="must-img-placeholder"></div>
<?php endif; ?>
                    <div class="must-info">
                        <h3><?php echo htmlspecialchars($event['event_title']); ?></h3>
                        <p>📅 <?php echo date("M d, Y", strtotime($event['event_date'])); ?></p>
                        <p>📍 <?php echo htmlspecialchars($event['event_location']); ?></p>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-events">No featured events.</p>
            <?php endif; ?>
        </section>

    </div>
</main>

</body>
</html>
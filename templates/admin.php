
<?php include '../config/db_connect.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal - Campus Vibe</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <div class="logo-icon">🎓</div>
            <div class="logo-text">
                <h2>Campus Vibe</h2>
                <p>Event Manager</p>
            </div>
        </div>

        <nav class="navigation">
            <h3>NAVIGATION</h3>
            <ul>
                <li class="active">
                    <span class="icon">🏠</span>
                    <span>Homepage</span>
                </li>
                <li>
                    <span class="icon">📅</span>
                    <span>All Events</span>
                </li>
                <li>
                    <span class="icon">🔍</span>
                    <span>Filters</span>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="search-container">
                <input type="text" id="searchInput" placeholder="Search events, organizers..." onkeyup="searchEvents()">
            </div>
            <div class="user-info">
                <div class="user-avatar">AD</div>
            </div>
        </div>

        <!-- Greeting -->
        <div class="greeting">
            <h1>Good evening, <span class="admin-name">Admin</span> 👋</h1>
            <p class="date-text">Thursday, March 13, 2026</p>
        </div>

        <!-- Add New Event Button (Top Right) -->
        <div class="add-event-header">
            <button class="btn-add-new-event" onclick="openAddEventModal()">+ Add New Event</button>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card orange-border">
                <div class="stat-content">
                    <p class="stat-label">Today's Events</p>
                    <h2 class="stat-number" id="todayEventsCount">0</h2>
                    <p class="stat-detail">2 ongoing · 1 upcoming</p>
                </div>
                <div class="stat-icon">📅</div>
            </div>

            <div class="stat-card cyan-border">
                <div class="stat-content">
                    <p class="stat-label">Total Attendees</p>
                    <h2 class="stat-number">1,247</h2>
                    <p class="stat-detail">Expected today</p>
                    <p class="stat-growth">↑ 12% from last week</p>
                </div>
                <div class="stat-icon">👥</div>
            </div>

            <div class="stat-card purple-border">
                <div class="stat-content">
                    <p class="stat-label">Active Venues</p>
                    <h2 class="stat-number">5</h2>
                    <p class="stat-detail">Across campus</p>
                </div>
                <div class="stat-icon">📍</div>
            </div>

            <div class="stat-card yellow-border">
                <div class="stat-content">
                    <p class="stat-label">This Week</p>
                    <h2 class="stat-number">12</h2>
                    <p class="stat-detail">Events scheduled</p>
                    <p class="stat-growth">↑ 8% from last week</p>
                </div>
                <div class="stat-icon">📊</div>
            </div>
        </div>

        <!-- Today's Events Section -->
        <section class="events-section">
            <div class="section-header">
                <h2>⏰ Today's Events</h2>
                <a href="#" class="view-all">View all ></a>
            </div>

            <div class="events-grid" id="eventsGrid">
<?php
$sql = "SELECT * FROM events WHERE event_date = CURDATE()";
$result = $conn->query($sql);

if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
?>
    <div class="event-card">
        <div class="event-card-header">
            <div class="event-title-container">
                <h3 class="event-title"><?php echo $row['event_title']; ?></h3>
                <span class="event-category"><?php echo ucfirst($row['category']); ?></span>
            </div>
            <span class="event-status status-upcoming">Upcoming</span>
        </div>
        <div class="event-card-body">
            <div class="event-info-row">
                📅 <span><?php echo date('D, d M Y', strtotime($row['event_date'])); ?></span>
            </div>
            <div class="event-info-row">
                🕐 <span><?php echo date('h:i A', strtotime($row['event_time'])); ?></span>
            </div>
            <div class="event-info-row">
                📍 <span><?php echo $row['event_location']; ?></span>
            </div>
            <div class="event-info-row">
                👤 <span><?php echo $row['organizer_name']; ?></span>
            </div>
        </div>
        <div class="event-card-actions">
            <button class="btn-edit">Edit</button>
            <button class="btn-delete">Delete</button>
        </div>
    </div>
<?php 
    }
} else {
    echo '<div class="no-events"><p>No events scheduled for today.</p></div>';
}
?>
</div>
</section>
</main>

    <!-- Add Event Modal -->
    <div id="addEventModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Event</h2>
                <span class="close-btn" onclick="closeAddEventModal()">&times;</span>
            </div>
            <form id="addEventForm" method="POST" action="../pages/add_event.php">
                <div class="form-group">
                    <label for="eventName">Event Name *</label>
                    <input type="text" id="eventName" name="event_title" required>
                    <span class="error-msg" id="eventNameError"></span>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="eventDate">Date *</label>
                        <input type="date" id="eventDate" name="event_date" required>
                        <span class="error-msg" id="eventDateError"></span>
                    </div>
                    <div class="form-group">
                        <label for="eventTime">Time *</label>
                        <input type="time" id="eventTime" name="event_time" required>
                        <span class="error-msg" id="eventTimeError"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="eventLocation">Location *</label>
                    <input type="text" id="eventLocation" name="event_location" required>
                    <span class="error-msg" id="eventLocationError"></span>
                </div>

                <div class="form-group">
                    <label for="eventDescription">Description *</label>
                    <textarea id="eventDescription" name="event_description" rows="3" required></textarea>
                    <span class="error-msg" id="eventDescriptionError"></span>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="coordinatorName">Coordinator Name *</label>
                        <input type="text" id="coordinatorName" required>
                        <span class="error-msg" id="coordinatorNameError"></span>
                    </div>
                    <div class="form-group">
                        <label for="coordinatorPhone">Coordinator Phone *</label>
                        <input type="tel" id="coordinatorPhone" required pattern="[0-9]{10}">
                        <span class="error-msg" id="coordinatorPhoneError"></span>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeAddEventModal()">Cancel</button>
                    <button type="submit" class="btn-submit">Add Event</button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Event Details Modal -->
    <div id="viewEventModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Event Details</h2>
                <span class="close-btn" onclick="closeViewEventModal()">&times;</span>
            </div>
            <div class="event-details" id="eventDetailsContent">
                <!-- Event details will be dynamically added here -->
            </div>
            <div class="modal-actions">
                <button class="btn-close" onclick="closeViewEventModal()">Close</button>
            </div>
        </div>
    </div>

    <!-- Edit Event Modal -->
    <div id="editEventModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Event</h2>
                <span class="close-btn" onclick="closeEditEventModal()">&times;</span>
            </div>
            <form id="editEventForm" onsubmit="saveEditEvent(event)">
                <input type="hidden" id="editEventId">
                
                <div class="form-group">
                    <label for="editEventName">Event Name *</label>
                    <input type="text" id="editEventName" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="editEventDate">Date *</label>
                        <input type="date" id="editEventDate" required>
                    </div>
                    <div class="form-group">
                        <label for="editEventTime">Time *</label>
                        <input type="time" id="editEventTime" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="editEventLocation">Location *</label>
                    <input type="text" id="editEventLocation" required>
                </div>

                <div class="form-group">
                    <label for="editEventDescription">Description *</label>
                    <textarea id="editEventDescription" rows="3" required></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="editCoordinatorName">Coordinator Name *</label>
                        <input type="text" id="editCoordinatorName" required>
                    </div>
                    <div class="form-group">
                        <label for="editCoordinatorPhone">Coordinator Phone *</label>
                        <input type="tel" id="editCoordinatorPhone" required pattern="[0-9]{10}">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeEditEventModal()">Cancel</button>
                    <button type="submit" class="btn-submit">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../assets/js/admin.js"></script>
</body>
</html>

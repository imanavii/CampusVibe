// Admin Portal JavaScript

// Store events in memory (in production, this would be in database)
let events = [];
let eventIdCounter = 1;

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    loadEvents();
    updateEventCount();
});

// Add Event Function
function addEvent(e) {
    e.preventDefault();
    
    // Clear previous errors
    clearErrors();
    
    // Get form values
    const eventName = document.getElementById('eventName').value.trim();
    const eventDate = document.getElementById('eventDate').value;
    const eventTime = document.getElementById('eventTime').value;
    const eventLocation = document.getElementById('eventLocation').value.trim();
    const eventDescription = document.getElementById('eventDescription').value.trim();
    const coordinatorName = document.getElementById('coordinatorName').value.trim();
    const coordinatorPhone = document.getElementById('coordinatorPhone').value.trim();
    
    // Validation
    let isValid = true;
    
    if (eventName.length < 3) {
        showError('eventNameError', 'Event name must be at least 3 characters');
        isValid = false;
    }
    
    const selectedDate = new Date(eventDate);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    if (selectedDate < today) {
        showError('eventDateError', 'Event date cannot be in the past');
        isValid = false;
    }
    
    if (eventLocation.length < 3) {
        showError('eventLocationError', 'Location must be at least 3 characters');
        isValid = false;
    }
    
    if (eventDescription.length < 10) {
        showError('eventDescriptionError', 'Description must be at least 10 characters');
        isValid = false;
    }
    
    if (coordinatorName.length < 3) {
        showError('coordinatorNameError', 'Coordinator name must be at least 3 characters');
        isValid = false;
    }
    
    if (!/^[0-9]{10}$/.test(coordinatorPhone)) {
        showError('coordinatorPhoneError', 'Phone number must be 10 digits');
        isValid = false;
    }
    
    if (!isValid) {
        return false;
    }
    
    // Create event object
    const newEvent = {
        id: eventIdCounter++,
        name: eventName,
        date: eventDate,
        time: eventTime,
        location: eventLocation,
        description: eventDescription,
        coordinator: {
            name: coordinatorName,
            phone: coordinatorPhone
        },
        status: getEventStatus(eventDate, eventTime)
    };
    
document.getElementById('addEventForm').submit();    
   // Submit form to PHP
}

// Get Event Status
function getEventStatus(date, time) {
    const eventDateTime = new Date(date + ' ' + time);
    const now = new Date();
    
    const todayDate = new Date();
    todayDate.setHours(0, 0, 0, 0);
    const eventDate = new Date(date);
    eventDate.setHours(0, 0, 0, 0);
    
    if (eventDate.getTime() === todayDate.getTime()) {
        if (eventDateTime > now) {
            return 'upcoming';
        } else {
            return 'live';
        }
    }
    
    return eventDateTime > now ? 'upcoming' : 'completed';
}

// Load Events
function loadEvents() {
    // Load from localStorage
    const stored = localStorage.getItem('campusEvents');
    if (stored) {
        events = JSON.parse(stored);
        eventIdCounter = events.length > 0 ? Math.max(...events.map(e => e.id)) + 1 : 1;
    }
    
    displayEvents(events);
}

// Save Events to localStorage
function saveEvents() {
    localStorage.setItem('campusEvents', JSON.stringify(events));
}

// Display Events
function displayEvents(eventsToDisplay) {
    const eventsGrid = document.getElementById('eventsGrid');
    const noEventsMessage = document.getElementById('noEventsMessage');
    
    if (eventsToDisplay.length === 0) {
        eventsGrid.style.display = 'none';
        noEventsMessage.style.display = 'block';
        return;
    }
    
    eventsGrid.style.display = 'grid';
    noEventsMessage.style.display = 'none';
    
    // Get today's date
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    // Filter today's events
    const todayEvents = eventsToDisplay.filter(event => {
        const eventDate = new Date(event.date);
        eventDate.setHours(0, 0, 0, 0);
        return eventDate.getTime() === today.getTime();
    });
    
    if (todayEvents.length === 0) {
        eventsGrid.innerHTML = '<p class="no-events">No events scheduled for today.</p>';
        return;
    }
    
    eventsGrid.innerHTML = todayEvents.map(event => `
        <div class="event-card" onclick="viewEventDetails(${event.id})">
            <div class="event-card-header">
                <div class="event-title-container">
                    <h3 class="event-title">${event.name}</h3>
                    <span class="event-status status-${event.status}">
                        ${event.status === 'live' ? 'Live Now' : 'Upcoming'}
                    </span>
                </div>
            </div>
            <div class="event-card-body">
                <div class="event-info-row">
                    📅 <span>Today</span>
                </div>
                <div class="event-info-row">
                    🕐 <span>${formatTime(event.time)}</span>
                </div>
                <div class="event-info-row">
                    📍 <span>${event.location}</span>
                </div>
                <p class="event-description">${event.description}</p>
            </div>
            <div class="event-card-actions" onclick="event.stopPropagation()">
                <button class="btn-edit" onclick="editEvent(${event.id})">Edit</button>
                <button class="btn-delete" onclick="deleteEvent(${event.id})">Delete</button>
            </div>
        </div>
    `).join('');
}

// Format Time
function formatTime(time) {
    const [hours, minutes] = time.split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const displayHour = hour % 12 || 12;
    return `${displayHour}:${minutes} ${ampm}`;
}

// Search Events
function searchEvents() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();
    
    if (searchTerm === '') {
        loadEvents();
        return;
    }
    
    const filteredEvents = events.filter(event => 
        event.name.toLowerCase().includes(searchTerm) ||
        event.description.toLowerCase().includes(searchTerm) ||
        event.location.toLowerCase().includes(searchTerm) ||
        event.coordinator.name.toLowerCase().includes(searchTerm)
    );
    
    displayEvents(filteredEvents);
}

// View Event Details
function viewEventDetails(eventId) {
    const event = events.find(e => e.id === eventId);
    if (!event) return;
    
    const detailsContent = document.getElementById('eventDetailsContent');
    detailsContent.innerHTML = `
        <div class="detail-row">
            <div class="detail-label">Event Name</div>
            <div class="detail-value">${event.name}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Date</div>
            <div class="detail-value">${formatDate(event.date)}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Time</div>
            <div class="detail-value">${formatTime(event.time)}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Location</div>
            <div class="detail-value">${event.location}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Description</div>
            <div class="detail-value">${event.description}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Coordinator Info</div>
            <div class="detail-value">
                <strong>Name:</strong> ${event.coordinator.name}<br>
                <strong>Phone:</strong> ${event.coordinator.phone}
            </div>
        </div>
    `;
    
    openViewEventModal();
}

// Format Date
function formatDate(dateStr) {
    const date = new Date(dateStr);
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return date.toLocaleDateString('en-US', options);
}

// Edit Event
function editEvent(eventId) {
    const event = events.find(e => e.id === eventId);
    if (!event) return;
    
    // Populate edit form
    document.getElementById('editEventId').value = event.id;
    document.getElementById('editEventName').value = event.name;
    document.getElementById('editEventDate').value = event.date;
    document.getElementById('editEventTime').value = event.time;
    document.getElementById('editEventLocation').value = event.location;
    document.getElementById('editEventDescription').value = event.description;
    document.getElementById('editCoordinatorName').value = event.coordinator.name;
    document.getElementById('editCoordinatorPhone').value = event.coordinator.phone;
    
    openEditEventModal();
}

// Save Edit Event
function saveEditEvent(e) {
    e.preventDefault();
    
    const eventId = parseInt(document.getElementById('editEventId').value);
    const eventIndex = events.findIndex(e => e.id === eventId);
    
    if (eventIndex === -1) return;
    
    // Update event
    events[eventIndex] = {
        ...events[eventIndex],
        name: document.getElementById('editEventName').value.trim(),
        date: document.getElementById('editEventDate').value,
        time: document.getElementById('editEventTime').value,
        location: document.getElementById('editEventLocation').value.trim(),
        description: document.getElementById('editEventDescription').value.trim(),
        coordinator: {
            name: document.getElementById('editCoordinatorName').value.trim(),
            phone: document.getElementById('editCoordinatorPhone').value.trim()
        },
        status: getEventStatus(
            document.getElementById('editEventDate').value,
            document.getElementById('editEventTime').value
        )
    };
    
    saveEvents();
    loadEvents();
    updateEventCount();
    closeEditEventModal();
    
    alert('Event updated successfully!');
    
    return false;
}

// Delete Event
function deleteEvent(eventId) {
    if (!confirm('Are you sure you want to delete this event? This action cannot be undone.')) {
        return;
    }
    
    events = events.filter(e => e.id !== eventId);
    saveEvents();
    loadEvents();
    updateEventCount();
    
    alert('Event deleted successfully!');
}

// Update Event Count
function updateEventCount() {
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    const todayEvents = events.filter(event => {
        const eventDate = new Date(event.date);
        eventDate.setHours(0, 0, 0, 0);
        return eventDate.getTime() === today.getTime();
    });
    
    document.getElementById('todayEventsCount').textContent = todayEvents.length;
}

// Modal Functions
function openAddEventModal() {
    document.getElementById('addEventModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeAddEventModal() {
    document.getElementById('addEventModal').classList.remove('show');
    document.body.style.overflow = 'auto';
    document.getElementById('addEventForm').reset();
    clearErrors();
}

function openViewEventModal() {
    document.getElementById('viewEventModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeViewEventModal() {
    document.getElementById('viewEventModal').classList.remove('show');
    document.body.style.overflow = 'auto';
}

function openEditEventModal() {
    document.getElementById('editEventModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeEditEventModal() {
    document.getElementById('editEventModal').classList.remove('show');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.classList.remove('show');
        document.body.style.overflow = 'auto';
    }
}

// Error Handling
function showError(elementId, message) {
    const errorElement = document.getElementById(elementId);
    errorElement.textContent = message;
    errorElement.classList.add('show');
}

function clearErrors() {
    const errorElements = document.querySelectorAll('.error-msg');
    errorElements.forEach(element => {
        element.textContent = '';
        element.classList.remove('show');
    });
}
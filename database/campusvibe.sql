CREATE DATABASE IF NOT EXISTS campusvibe;
USE campusvibe;

CREATE TABLE users ( user_id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, email VARCHAR(100) UNIQUE NOT NULL, password VARCHAR(255) NOT NULL, phone VARCHAR(10), user_type ENUM('student', 'admin') DEFAULT 'student', profile_image VARCHAR(255) DEFAULT 'default-avatar.jpg', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, last_login TIMESTAMP NULL ) AUTO_INCREMENT=100;
CREATE TABLE events ( event_id INT AUTO_INCREMENT PRIMARY KEY, event_title VARCHAR(200) NOT NULL, event_description TEXT, event_image VARCHAR(255), event_date DATE NOT NULL, event_time TIME NOT NULL, event_location VARCHAR(200) NOT NULL, venue VARCHAR(150), category ENUM('cultural', 'sports', 'technical', 'music', 'workshops', 'hosted_by_departments') NOT NULL, registration_link VARCHAR(500), organizer_name VARCHAR(100), created_by INT, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (created_by) REFERENCES users(user_id) ON DELETE SET NULL ) AUTO_INCREMENT=1000;


CREATE TABLE event_favorites (
    favorite_id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    user_id INT NOT NULL,
    favorited_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(event_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    UNIQUE KEY unique_favorite (event_id, user_id)
);


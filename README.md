CampusVibe – Campus Event Management Portal
Bringing every campus event to one place.
GitHub Repo

Overview
CampusVibe is a PHP-based event management portal built for college campuses. It gives students a single place to discover what's happening around them, while admins can create and manage events without hassle.
Built as an academic project using core web technologies — no frameworks, just PHP, MySQL, HTML, and CSS doing the work.

Features

Event Listings — Browse all upcoming campus events in one place
Event Management — Admins can create, edit, and delete events
User Authentication — Secure login and signup for students and admins
Admin Panel — Dedicated dashboard for managing campus activity


Tech Stack
LayerTechnologyBackendPHPDatabaseMySQLFrontendHTML, CSS, JavaScriptServerApache via XAMPP

Getting Started
Prerequisites

XAMPP installed on your machine

Installation
bash# Clone the repository
git clone https://github.com/imanavii/CampusVibe.git

Move the project folder to your XAMPP htdocs directory
Open XAMPP and start Apache and MySQL
Go to http://localhost/phpmyadmin
Create a database named campusvibe
Import the SQL file from the database/ folder
Visit http://localhost/CampusVibe


Project Structure
CampusVibe/
├── api/          # Backend API handlers
├── assets/       # Images and static files
├── config/       # Database configuration
├── database/     # SQL schema
├── includes/     # Reusable PHP components
├── pages/        # Individual page files
└── templates/    # Layout templates

Planned Improvements

Mobile-responsive layout
Student event RSVP and registration
Email notifications for upcoming events
Search and filter by category or date
Admin analytics dashboard


Author
Manavi Mutyalwar — @imanavii

Feedback
Found a bug or have a suggestion?

Report Bugs — GitHub Issues
Feature Requests — Open an issue with the enhancement label

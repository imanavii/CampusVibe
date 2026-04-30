# CampusVibe – Campus Event Management Portal
Bringing every campus event to one place.

---

## Overview

CampusVibe is a PHP-based event management portal built for college campuses. It gives students a single place to discover what's happening around them, while admins can create and manage events without hassle.

Built as an academic project using core web technologies — no frameworks, just PHP, MySQL, HTML, and CSS.

---

## Features

- **Event Listings** — Browse all upcoming campus events in one place
- **Event Management** — Admins can create, edit, and delete events
- **User Authentication** — Secure login and signup for students and admins
- **Admin Panel** — Dedicated dashboard for managing campus activity

---

## Tech Stack

| Layer | Technology |
|-------|------------|
| Backend | PHP |
| Database | MySQL |
| Frontend | HTML, CSS, JavaScript |
| Server | Apache via XAMPP |

---

## Getting Started

### Prerequisites
- [XAMPP](https://www.apachefriends.org/) installed on your machine

### Installation

```bash
git clone https://github.com/imanavii/CampusVibe.git
```

1. Move the folder to your XAMPP `htdocs` directory
2. Start **Apache** and **MySQL** from XAMPP
3. Open `http://localhost/phpmyadmin`, create a database named `campusvibe`
4. Import the SQL file from the `database/` folder
5. Visit `http://localhost/CampusVibe`

---

## Project Structure
CampusVibe/
├── assets/       # Images and static files
├── config/       # Database configuration
├── database/     # SQL schema
├── includes/     # Reusable PHP components
├── pages/        # Individual page files
└── templates/    # Layout templates

---

## Planned Improvements

- Mobile-responsive layout
- Student event RSVP and registration
- Email notifications for upcoming events
- Search and filter by category or date

---

## Author

**Manavi Mutyalwar** — [@imanavii](https://github.com/imanavii)]

---

## Feedback

- **Report Bugs** — [GitHub Issues](https://github.com/imanavii/CampusVibe/issues)
- **Feature Requests** — Open an issue with the `enhancement` label

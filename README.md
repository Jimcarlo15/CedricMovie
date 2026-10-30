# CedCine — Movie Catalog & Rating Platform

A responsive, cinema-inspired web application designed and developed as a **College BSIT Student Project**. CedCine provides movie catalog exploration, star ratings, plot synopses, user authentication, a personalized favorites watchlist, and a comprehensive **Admin Dashboard** with full CRUD capabilities.

---

## 🛠️ Technology Stack

| Layer | Technology | Purpose |
| :--- | :--- | :--- |
| **Frontend Structure** | **HTML5** | Semantic structure and forms |
| **Frontend Styling** | **Bootstrap 5.3 + Custom CSS3** | Cinema dark palette (`#0a0e17`, `#0f172a`), responsive layouts |
| **Frontend Interactivity** | **JavaScript (ES6+)** | AJAX debounced live search, genre filters, player modal, dynamic favorites |
| **Data Interchange** | **JSON** | Asynchronous communication between frontend and PHP endpoints (`api/`) |
| **Backend Logic** | **PHP 8 (PDO)** | Session authentication, CRUD operations, prepared statements, partial views |
| **Database** | **MySQL / MariaDB** | Relational schema (`cedcine_db`) with `movies`, `users`, and `favorites` tables |

> [!NOTE]  
> This project intentionally avoids heavy external frameworks (like React, Vue, or Laravel) to maintain clean, transparent, and defensible code suitable for an academic presentation.

---

## 🚀 Quick Start & Installation

### Prerequisites
- **XAMPP** (with Apache and MySQL enabled)
- Web browser (Chrome, Edge, Firefox)

### Setup Steps
1. Place the project folder into your XAMPP web root:
   ```text
   C:\xampp\htdocs\CedricMovie\
   ```
2. Open the **XAMPP Control Panel** and ensure both **Apache** and **MySQL** services are started (`Running`).
3. Import the database:
   - Open **phpMyAdmin** (`http://localhost/phpmyadmin/`).
   - Create a new database named `cedcine_db` (or simply import [`database/cedcine.sql`](file:///c:/xampp/htdocs/CedricMovie/database/cedcine.sql)).
   - Alternatively, CedCine features **automatic database detection**: opening the website will auto-create `cedcine_db` from `cedcine.sql` if it does not yet exist!
4. Access the website in your browser:
   ```text
   http://localhost/CedricMovie/
   ```

---

## 🔑 First Administrator Account Setup

Demo accounts have been removed. You can register your own personal account on the website:

1. Click **Register** on the navigation bar (or navigate to `http://localhost/CedricMovie/register.php`).
2. Fill out your Full Name, Username, Email, and Password.
3. **Automatic Administrator Initialization**: Because no user accounts currently exist in the database, the **very first user registered automatically receives the `admin` role**.
4. Once created, you will be redirected straight to the **Admin Dashboard** (`admin/index.php`).
5. All subsequent registrations will automatically be assigned the standard **`user`** role. Only existing administrators can promote other users to admin status inside the Admin Dashboard.

---

## 📂 Project Architecture

```text
CedCine/
├── admin/                     # Protected Administrator Module
│   ├── index.php              # Dashboard overview with live summary stats
│   ├── movies.php             # Movie CRUD table & management
│   ├── add_movie.php          # Add new movie form with live poster preview
│   ├── edit_movie.php         # Edit existing movie form
│   ├── delete_movie.php       # Safe movie deletion with CSRF protection
│   └── users.php              # User account management & role promotion
├── assets/
│   ├── css/
│   │   ├── style.css          # Cinema dark theme & card styling
│   │   ├── auth.css           # Login & registration layout
│   │   └── admin.css          # Admin sidebar, metric cards & tables
│   └── js/
│       ├── app.js             # AJAX search, genre filter, favorites toggle, player modal
│       └── admin.js           # Live table search, poster preview, delete prompts
├── api/
│   ├── movies.php             # JSON endpoint: movie query, search & filtering
│   └── favorite.php           # JSON endpoint: toggle user movie favorites
├── config/
│   └── db.php                 # MySQL PDO connection & auto-setup handler
├── database/
│   └── cedcine.sql            # MySQL schema & initial seed data
├── helpers/
│   ├── auth.php               # Session verification, role guards, CSRF tokens
│   └── partial.php            # render_partial($name, $data) template engine
├── partials/
│   ├── header.php             # HTML head, Bootstrap 5 CSS, Google Fonts, alerts
│   ├── navbar.php             # Brand logo, live search, favorites link, auth menu
│   ├── hero.php               # Featured movie banner & notice
│   ├── filter_bar.php         # Genre pills & sorting dropdown
│   ├── movie_grid.php         # Responsive grid container with counter
│   ├── movie_card.php         # Reusable card with rating, poster & heart toggle
│   ├── movie_detail.php       # Dedicated synopsis, cast, and director view
│   ├── empty_state.php        # Friendly card when no movies match criteria
│   ├── player_modal.php       # Simulated player modal ("Not Playable Yet")
│   ├── admin_sidebar.php      # Admin navigation sidebar
│   └── footer.php             # Footer branding, tech tags, Bootstrap 5 JS bundle
├── index.php                  # Public catalog homepage
├── movie.php                  # Detailed movie view
├── favorites.php              # Authenticated user watchlist / favorites
├── login.php                  # User & administrator login with show/hide password
├── register.php               # User registration with password hashing
├── logout.php                 # Safe session termination & redirection
├── add_movie.php              # Root compatibility redirect
└── README.md                  # Project documentation
```

---

## 🌟 Key Features Walkthrough

### 1. Public Movie Catalog & "Not Playable Yet" Modal
- Movie cards showcase HD posters, star ratings (out of 10), release years, durations, and genres.
- Clicking **"Watch Movie"** opens the **"Not Playable Yet"** modal, presenting simulated video controls and a clear prompt explaining that streaming is unavailable because CedCine is catalog-only.
- Live debounced search and genre filter pills update the grid instantly without page reloads using **AJAX partial updates**.

### 2. User Authentication & Security
- **Registration**: Validates email format, unique username/email, password confirmation, and hashes passwords securely with `password_hash(..., PASSWORD_DEFAULT)`.
- **Login**: Verifies credentials with `password_verify()`, regenerates session IDs (`session_regenerate_id(true)`) to prevent session fixation, and routes administrators straight to the Admin Dashboard and students to the Homepage.
- **CSRF Defense**: Every state-changing form includes a CSRF token verified on the server.
- **XSS Defense**: All outputs are sanitized through `htmlspecialchars()`.

### 3. Favorites / Watchlist System
- Authenticated users can click the heart icon on any movie card or detail page.
- Requests communicate with `api/favorite.php` via JSON, dynamically toggling the heart state and updating the navbar favorites badge count.
- A dedicated page at [`favorites.php`](file:///c:/xampp/htdocs/CedricMovie/favorites.php) allows users to manage their saved movies.

### 4. Admin Dashboard (Role-Based Access)
- Accessible only to users where `role === 'admin'`. Unauthorized visits are redirected.
- **Metric Cards**: Total Movies, Registered Users, Administrators, and Saved Favorites.
- **Movie CRUD**: Create, read, edit, and delete movie entries with automatic fallback poster placeholders and live previews.
- **User Management**: Search registered users, promote/demote user roles, and delete accounts. Includes safeguards preventing an admin from accidentally deleting or demoting their own account, or removing the last admin.

---

## 📋 Database Structure (`cedcine_db`)

```sql
-- Movies Table
CREATE TABLE movies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    genre VARCHAR(100) NOT NULL,
    release_year INT NOT NULL,
    rating DECIMAL(3,1) NOT NULL DEFAULT 0.0,
    director VARCHAR(150) NOT NULL,
    cast TEXT NOT NULL,
    description TEXT NOT NULL,
    poster_url VARCHAR(500) NOT NULL,
    backdrop_url VARCHAR(500) NULL,
    duration VARCHAR(50) DEFAULT '2h 00m',
    featured TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Favorites Table
CREATE TABLE favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    movie_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_movie (user_id, movie_id)
);
```

---

## 🎓 Academic Defense Tips for Students
- **Why PDO?** Prepared statements separate SQL query structure from user inputs, rendering SQL injection attacks ineffective.
- **Why modular partials?** Code reusability: changing the navbar or movie card updates across the entire website from one single file (`partials/`).
- **Why Bootstrap 5 + Custom CSS?** Bootstrap provides battle-tested responsive grid utilities and modal accessibility, while our custom CSS delivers the dark cinema aesthetic.
- **Why JSON?** Lightweight data transmission for the favorites counter and movie search without reloading entire HTML documents.

# CedricMovie - Movie Catalog & Rating Platform (Modular Partials)

A responsive cinema web application built with **PHP** and **SQLite3**, featuring an extensible **Modular Partials Architecture**, dynamic **Partial AJAX Rendering**, movie pictures, ratings, synopses, and a **"Not Playable Yet"** preview modal.

---

## 🧩 Complete Partials Architecture

All UI components are modularized under [`partials/`](file:///c:/xampp/htdocs/CedricMovie/partials/) and loaded using the [`render_partial($name, $data)`](file:///c:/xampp/htdocs/CedricMovie/helpers/partial.php) helper:

| Partial | Path | Purpose |
| :--- | :--- | :--- |
| **Header** | `partials/header.php` | Document `<head>`, Google Fonts (`Plus Jakarta Sans`), CSS links |
| **Navbar** | `partials/navbar.php` | Brand logo, live search bar, Add Movie action |
| **Hero** | `partials/hero.php` | Featured catalog item banner & "Not playable yet" tag |
| **Filter Bar** | `partials/filter_bar.php` | Genre pills & sort dropdown (Rating, Year, Title) |
| **Movie Grid** | `partials/movie_grid.php` | Grid container & movies counter (supports AJAX partial swaps) |
| **Movie Card** | `partials/movie_card.php` | Reusable movie card with poster, rating, badge, and description |
| **Movie Detail** | `partials/movie_detail.php` | Full backdrop, poster, rating, cast, director, and synopsis |
| **Empty State** | `partials/empty_state.php` | Card shown when no movies match search or filter |
| **Player Modal**| `partials/player_modal.php` | Video player simulation modal with disabled playback warning |
| **Add Modal** | `partials/add_modal.php` | Form modal to insert new movies directly into SQLite |
| **Footer** | `partials/footer.php` | Footer branding, tech badges, and JavaScript scripts |

---

## ⚡ Dynamic Partial AJAX Loading

The application supports **Partial AJAX Updates**:
- When clicking a genre pill, changing the sort dropdown, or typing into the search box, the browser requests:
  ```http
  GET /CedricMovie/index.php?partial=movies&genre=Sci-Fi&sort=rating_desc&q=interstellar
  ```
- The backend renders **only** the `partials/movie_grid.php` component without reloading the entire page or re-executing header/navbar/footer.
- Seamless fallback: If JavaScript is disabled or a direct link is shared, full server-side rendering renders all partials normally.

---

## 📁 File Structure

```text
CedricMovie/
├── config/
│   └── db.php                  # SQLite PDO connection, table setup & seeder
├── database/
│   └── movies.db               # SQLite 3 database file
├── helpers/
│   └── partial.php             # render_partial($name, $data, $returnString) helper
├── partials/
│   ├── header.php              # <head>, fonts, CSS
│   ├── navbar.php              # Navigation and search
│   ├── hero.php                # Featured movie banner
│   ├── filter_bar.php          # Genre pills & sorting
│   ├── movie_grid.php          # Movies container & counter
│   ├── movie_card.php          # Reusable movie card component
│   ├── movie_detail.php        # Dedicated detail view layout
│   ├── empty_state.php         # Clean no-results message
│   ├── player_modal.php        # "Not Playable Yet" dialog
│   ├── add_modal.php           # Add movie form modal
│   └── footer.php              # Footer and JS loader
├── assets/
│   ├── css/
│   │   └── style.css           # Dark cinema theme stylesheet
│   └── js/
│       └── app.js              # Partial AJAX fetcher, modals & search
├── index.php                   # Home catalog controller
├── movie.php                   # Movie detail controller
├── add_movie.php               # SQLite insert handler
└── README.md
```

---

## 🚀 How to Run

### Option 1: Via XAMPP (Apache)
1. Start **Apache** from the XAMPP Control Panel.
2. Open your browser:
   ```
   http://localhost/CedricMovie/
   ```

### Option 2: Via PHP Built-in Server
Open your terminal in `c:\xampp\htdocs\CedricMovie` and run:
```powershell
C:\xampp\php\php.exe -S localhost:8000
```
Then visit:
```
http://localhost:8000
```

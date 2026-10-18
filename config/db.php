<?php
// config/db.php - SQLite Database Connection & Initialization

$dbDir = __DIR__ . '/../database';
if (!is_dir($dbDir)) {
    mkdir($dbDir, 0777, true);
}

$dbPath = $dbDir . '/movies.db';

try {
    $pdo = new PDO("sqlite:" . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Create table if not exists
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS movies (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            genre TEXT NOT NULL,
            year INTEGER NOT NULL,
            duration TEXT NOT NULL,
            rating REAL NOT NULL,
            poster TEXT NOT NULL,
            backdrop TEXT NOT NULL,
            description TEXT NOT NULL,
            director TEXT NOT NULL,
            actors TEXT NOT NULL,
            is_playable INTEGER DEFAULT 0,
            featured INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // Seed initial data if empty
    $count = $pdo->query("SELECT COUNT(*) FROM movies")->fetchColumn();
    if ($count == 0) {
        $sampleMovies = [
            [
                'title' => 'Interstellar',
                'genre' => 'Sci-Fi / Adventure',
                'year' => 2014,
                'duration' => '2h 49m',
                'rating' => 8.7,
                'poster' => 'https://images.unsplash.com/photo-1506703719100-a0f3a48c0f86?w=600&auto=format&fit=crop&q=80',
                'backdrop' => 'https://images.unsplash.com/photo-1506703719100-a0f3a48c0f86?w=1600&auto=format&fit=crop&q=80',
                'description' => 'When Earth becomes uninhabitable in the future, a farmer and ex-NASA pilot, Joseph Cooper, is tasked to pilot a spacecraft, along with a team of researchers, to find a new planet for humans.',
                'director' => 'Christopher Nolan',
                'actors' => 'Matthew McConaughey, Anne Hathaway, Jessica Chastain',
                'is_playable' => 0,
                'featured' => 1
            ],
            [
                'title' => 'Inception',
                'genre' => 'Sci-Fi / Action',
                'year' => 2010,
                'duration' => '2h 28m',
                'rating' => 8.8,
                'poster' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=600&auto=format&fit=crop&q=80',
                'backdrop' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=1600&auto=format&fit=crop&q=80',
                'description' => 'A thief who steals corporate secrets through the use of dream-sharing technology is given the inverse task of planting an idea into the mind of a C.E.O., but his tragic past may doom the project.',
                'director' => 'Christopher Nolan',
                'actors' => 'Leonardo DiCaprio, Joseph Gordon-Levitt, Elliot Page',
                'is_playable' => 0,
                'featured' => 0
            ],
            [
                'title' => 'The Dark Knight',
                'genre' => 'Action / Crime',
                'year' => 2008,
                'duration' => '2h 32m',
                'rating' => 9.0,
                'poster' => 'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?w=600&auto=format&fit=crop&q=80',
                'backdrop' => 'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?w=1600&auto=format&fit=crop&q=80',
                'description' => 'When the menace known as the Joker wreaks havoc and chaos on the people of Gotham, Batman must accept one of the greatest psychological and physical tests of his ability to fight injustice.',
                'director' => 'Christopher Nolan',
                'actors' => 'Christian Bale, Heath Ledger, Aaron Eckhart',
                'is_playable' => 0,
                'featured' => 0
            ],
            [
                'title' => 'Blade Runner 2049',
                'genre' => 'Sci-Fi / Mystery',
                'year' => 2017,
                'duration' => '2h 44m',
                'rating' => 8.0,
                'poster' => 'https://images.unsplash.com/photo-1578632767115-351597cf2477?w=600&auto=format&fit=crop&q=80',
                'backdrop' => 'https://images.unsplash.com/photo-1578632767115-351597cf2477?w=1600&auto=format&fit=crop&q=80',
                'description' => 'Young Blade Runner K\'s discovery of a long-buried secret leads him to track down former Blade Runner Rick Deckard, who\'s been missing for thirty years.',
                'director' => 'Denis Villeneuve',
                'actors' => 'Ryan Gosling, Harrison Ford, Ana de Armas',
                'is_playable' => 0,
                'featured' => 0
            ],
            [
                'title' => 'Spirited Away',
                'genre' => 'Animation / Fantasy',
                'year' => 2001,
                'duration' => '2h 5m',
                'rating' => 8.6,
                'poster' => 'https://images.unsplash.com/photo-1534447677768-be436bb09401?w=600&auto=format&fit=crop&q=80',
                'backdrop' => 'https://images.unsplash.com/photo-1534447677768-be436bb09401?w=1600&auto=format&fit=crop&q=80',
                'description' => 'During her family\'s move to the suburbs, a sullen 10-year-old girl wanders into a world ruled by gods, witches, and spirits, and where humans are changed into beasts.',
                'director' => 'Hayao Miyazaki',
                'actors' => 'Rumi Hiiragi, Miyu Irino, Mari Natsuki',
                'is_playable' => 0,
                'featured' => 0
            ],
            [
                'title' => 'Pulp Fiction',
                'genre' => 'Crime / Drama',
                'year' => 1994,
                'duration' => '2h 34m',
                'rating' => 8.9,
                'poster' => 'https://images.unsplash.com/photo-1594909122845-11baa439b7bf?w=600&auto=format&fit=crop&q=80',
                'backdrop' => 'https://images.unsplash.com/photo-1594909122845-11baa439b7bf?w=1600&auto=format&fit=crop&q=80',
                'description' => 'The lives of two mob hitmen, a boxer, a gangster and his wife, and a pair of diner bandits intertwine in four tales of violence and redemption.',
                'director' => 'Quentin Tarantino',
                'actors' => 'John Travolta, Uma Thurman, Samuel L. Jackson',
                'is_playable' => 0,
                'featured' => 0
            ],
            [
                'title' => 'Dune: Part Two',
                'genre' => 'Sci-Fi / Action',
                'year' => 2024,
                'duration' => '2h 46m',
                'rating' => 8.5,
                'poster' => 'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?w=600&auto=format&fit=crop&q=80',
                'backdrop' => 'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?w=1600&auto=format&fit=crop&q=80',
                'description' => 'Paul Atreides unites with Chani and the Fremen while seeking revenge against the conspirators who destroyed his family.',
                'director' => 'Denis Villeneuve',
                'actors' => 'Timothée Chalamet, Zendaya, Rebecca Ferguson',
                'is_playable' => 0,
                'featured' => 0
            ],
            [
                'title' => 'Parasite',
                'genre' => 'Drama / Thriller',
                'year' => 2019,
                'duration' => '2h 12m',
                'rating' => 8.5,
                'poster' => 'https://images.unsplash.com/photo-1485846234645-a62644f84728?w=600&auto=format&fit=crop&q=80',
                'backdrop' => 'https://images.unsplash.com/photo-1485846234645-a62644f84728?w=1600&auto=format&fit=crop&q=80',
                'description' => 'Greed and class discrimination threaten the newly formed symbiotic relationship between the wealthy Park family and the destitute Kim clan.',
                'director' => 'Bong Joon Ho',
                'actors' => 'Song Kang-ho, Lee Sun-kyun, Cho Yeo-jeong',
                'is_playable' => 0,
                'featured' => 0
            ]
        ];

        $stmt = $pdo->prepare("
            INSERT INTO movies (title, genre, year, duration, rating, poster, backdrop, description, director, actors, is_playable, featured)
            VALUES (:title, :genre, :year, :duration, :rating, :poster, :backdrop, :description, :director, :actors, :is_playable, :featured)
        ");

        foreach ($sampleMovies as $movie) {
            $stmt->execute($movie);
        }
    }
} catch (PDOException $e) {
    die("Database Connection Error: " . $e->getMessage());
}


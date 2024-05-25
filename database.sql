CREATE TABLE songs (
    id INT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    artist VARCHAR(255) NOT NULL,
    spotify_id VARCHAR(255) NOT NULL,
    preview VARCHAR(255),
    img VARCHAR(255),
    danceability DECIMAL(5, 3),
    energy DECIMAL(5, 3),
    loudness DECIMAL(5, 3),
    speechiness DECIMAL(5, 3),
    acousticness DECIMAL(5, 3),
    instrumentalness DECIMAL(5, 3),
    liveness DECIMAL(5, 3),
    valence DECIMAL(5, 3),
    acousticness_artist DECIMAL(10, 9),
    danceability_artist DECIMAL(10, 9),
    energy_artist DECIMAL(10, 9),
    instrumentalness_artist DECIMAL(10, 9),
    liveness_artist DECIMAL(10, 9),
    speechiness_artist DECIMAL(10, 9),
    valence_artist DECIMAL(10, 9)
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    usertype VARCHAR(6),
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

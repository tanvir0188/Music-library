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
    valence_artist DECIMAL(10, 9),
    popularity INT
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    usertype VARCHAR(6),
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE preferences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    danceability FLOAT,
    energy FLOAT,
    loudness FLOAT,
    speechiness FLOAT,
    acousticness FLOAT,
    instrumentalness FLOAT,
    liveness FLOAT,
    valence FLOAT,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
);

CREATE TABLE playlists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
);

-- couldn't implement this table due to time limitation
CREATE TABLE playlist_songs (
    playlist_id INT NOT NULL,
    song_id INT NOT NULL,
    PRIMARY KEY (playlist_id, song_id),
    FOREIGN KEY (playlist_id) REFERENCES playlists (id) ON DELETE CASCADE,
    FOREIGN KEY (song_id) REFERENCES songs (id)
);

CREATE TABLE favorite_songs (
    user_id INT NOT NULL,
    song_id INT NOT NULL,
    PRIMARY KEY (user_id, song_id),
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    FOREIGN KEY (song_id) REFERENCES songs (id) ON DELETE CASCADE
);

DELIMITER / /

CREATE TRIGGER increment_popularity AFTER INSERT ON favorite_songs
FOR EACH ROW
BEGIN
    UPDATE songs
    SET popularity = popularity + 1
    WHERE id = NEW.song_id;
END;

/ /

DELIMITER;

DELIMITER / /

CREATE TRIGGER decrement_popularity AFTER DELETE ON favorite_songs
FOR EACH ROW
BEGIN
    UPDATE songs
    SET popularity = popularity - 1
    WHERE id = OLD.song_id;
END;

/ /

DELIMITER;
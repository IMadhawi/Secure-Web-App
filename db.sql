CREATE TABLE users (
    id INT(11) NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) COLLATE utf8mb4_general_ci NOT NULL,
    password VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
    role ENUM('user', 'admin') COLLATE utf8mb4_general_ci DEFAULT 'user',
    bio TEXT COLLATE utf8mb4_general_ci,
    PRIMARY KEY (id),
    UNIQUE KEY (username)
);

CREATE TABLE movies (
    id INT(11) NOT NULL AUTO_INCREMENT,
    title VARCHAR(100) COLLATE utf8mb4_general_ci NOT NULL,
    poster_path VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE reviews (
    id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(11),
    movie_id INT(11),
    review_content TEXT COLLATE utf8mb4_general_ci,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (movie_id) REFERENCES movies(id)
);
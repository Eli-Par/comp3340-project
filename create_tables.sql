CREATE TABLE users (
    userId INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    isAdmin TINYINT(1) NOT NULL DEFAULT 0,
    bio VARCHAR(500) NOT NULL DEFAULT "",
    joinDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);


COMMIT;

CREATE TABLE advice (
    adviceId INT AUTO_INCREMENT PRIMARY KEY,
    authorId INT NOT NULL,
    title varchar(300) NOT NULL,
    content TEXT NOT NULL,
    dateCreated DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (authorId) REFERENCES users(userId)
);

COMMIT;

CREATE TABLE advice_interactions (
    adviceId INT NOT NULL,
    userId INT NOT NULL,
    isLike TINYINT(1) NOT NULL,
    PRIMARY KEY (adviceId, userId),
    FOREIGN KEY (adviceId) REFERENCES advice(adviceId),
    FOREIGN KEY (userId) REFERENCES users(userId)
);

COMMIT;

CREATE TABLE advice_history (
    adviceHistoryId INT AUTO_INCREMENT PRIMARY KEY,
    userId INT,
    adviceId INT,
    time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userId) REFERENCES users(userId),
    FOREIGN KEY (adviceId) REFERENCES advice(adviceId)
);


COMMIT;
CREATE TABLE users (
    userId INT AUTO_INCREMENT PRIMARY KEY,
    username varchar(50) NOT NULL UNIQUE,
    password varchar(100) NOT NULL,
    isAdmin TINYINT(1) NOT NULL DEFAULT 0,
    bio varchar(500) NOT NULL DEFAULT ""
);

COMMIT;

CREATE TABLE advice (
    adviceId INT AUTO_INCREMENT PRIMARY KEY,
    authorId INT NOT NULL,
    title varchar(300) NOT NULL,
    content TEXT NOT NULL,
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
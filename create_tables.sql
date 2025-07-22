CREATE TABLE users (
    userId INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    isAdmin TINYINT(1) NOT NULL DEFAULT 0,
    bio VARCHAR(500) NOT NULL DEFAULT "",
    joinDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    isActive TINYINT(1) NOT NULL DEFAULT 1
);


COMMIT;

CREATE TABLE advice (
    adviceId INT AUTO_INCREMENT PRIMARY KEY,
    authorId INT NOT NULL,
    title VARCHAR(300) NOT NULL,
    summary TEXT,
    content TEXT NOT NULL,
    imageLink VARCHAR(500),
    imageAlt VARCHAR(255) DEFAULT '',
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

CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255),
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

COMMIT;

CREATE TABLE discussion (
    discussionId INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    authorId INT NOT NULL,
    title VARCHAR(300) NOT NULL,
    content TEXT NOT NULL,
    dateCreated DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    dateModified DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (authorId) REFERENCES users(userId)
);

COMMIT;

CREATE TABLE discussion_interactions (
    discussionId INT NOT NULL,
    userId INT NOT NULL,
    date_interacted DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (discussionId, userId),
    FOREIGN KEY (discussionId) REFERENCES discussion(discussionId) ON DELETE CASCADE,
    FOREIGN KEY (userId) REFERENCES users(userId)
);

COMMIT;

CREATE TABLE discussion_comments (
    commentID INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    discussionId INT NOT NULL,
    authorId INT NOT NULL,
    content TEXT NOT NULL,
    dateCreated DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (discussionId) REFERENCES discussion(discussionId) ON DELETE CASCADE,
    FOREIGN KEY (authorId) REFERENCES users(userId)
);

COMMIT;

CREATE TABLE themes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    theme VARCHAR(100) NOT NULL UNIQUE
);

COMMIT;
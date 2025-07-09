CREATE TABLE users (
    userId INT AUTO_INCREMENT PRIMARY KEY,
    username varchar(50) NOT NULL UNIQUE,
    password varchar(100) NOT NULL,
    isAdmin TINYINT(1) NOT NULL DEFAULT 0,
    bio varchar(500) NOT NULL DEFAULT ""
);

COMMIT;
DROP DATABASE IF EXISTS patgram;

CREATE DATABASE patgram;

USE patgram;

CREATE TABLE user (
    userId INT PRIMARY KEY AUTO_INCREMENT,
    userName VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(50) NOT NULL,
    firstName VARCHAR(50) NOT NULL,
    lastName  VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    createDate DATETIME NOT NULL,
    lastLoginDate DATETIME
);

INSERT INTO user(userName, password, firstName, lastName, email, createDate)
VALUES('admin', MD5('123456'), 'admin', 'admin', 'admin@patgram.com', NOW());

CREATE TABLE verb (
    verbId INT PRIMARY KEY AUTO_INCREMENT,
    value VARCHAR(50) NOT NULL
);

CREATE TABLE meaning_group (
    meaningGroupId INT PRIMARY KEY AUTO_INCREMENT,
    name LONGTEXT,
    pattern LONGTEXT,
    meaning LONGTEXT,
    verbId INT,
    FOREIGN KEY (verbId)
        REFERENCES verb(verbId)
        ON DELETE CASCADE
);

CREATE TABLE example_verb (
    exampleVerbId INT PRIMARY KEY AUTO_INCREMENT,
    value VARCHAR(50),
    meaningGroupId INT,
    FOREIGN KEY (meaningGroupId)
        REFERENCES meaning_group(meaningGroupId)
        ON DELETE CASCADE
);

CREATE TABLE example_sentence (
    exampleSentenceId INT PRIMARY KEY AUTO_INCREMENT,
    value LONGTEXT,
    meaningGroupId INT,
    FOREIGN KEY (meaningGroupId)
        REFERENCES meaning_group(meaningGroupId)
        ON DELETE CASCADE
);

CREATE TABLE class (
    classId INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50),
    code VARCHAR(50),
    description VARCHAR(500),
    userId INT,
    createDate DATETIME,
    FOREIGN KEY (userId)
        REFERENCES user(userId)
        ON DELETE CASCADE
);

CREATE TABLE class_verb (
    classId INT,
    verbId INT,
    PRIMARY KEY (classId, verbId),
    FOREIGN KEY (classId)
        REFERENCES class(classId)
        ON DELETE CASCADE,
    FOREIGN KEY (verbId)
        REFERENCES verb(verbId)
        ON DELETE CASCADE
);

CREATE TABLE meaning_group_highlighted_in_class (
    classId INT,
    meaningGroupId INT,
    PRIMARY KEY (classId, meaningGroupId),
    FOREIGN KEY (classId)
        REFERENCES class(classId)
        ON DELETE CASCADE,
    FOREIGN KEY (meaningGroupId)
        REFERENCES meaning_group(meaningGroupId)
        ON DELETE CASCADE
);

CREATE TABLE example_verb_highlighted_in_class (
    classId INT,
    exampleVerbId INT,
    PRIMARY KEY (classId, exampleVerbId),
    FOREIGN KEY (classId)
        REFERENCES class(classId)
        ON DELETE CASCADE,
    FOREIGN KEY (exampleVerbId)
        REFERENCES example_verb(exampleVerbId)
        ON DELETE CASCADE
);

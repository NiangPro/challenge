-- Base de données de test
CREATE DATABASE IF NOT EXISTS challenge_test;
USE challenge_test;

-- Table utilisateurs
CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table cohortes
CREATE TABLE IF NOT EXISTS cohortes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table challenges
CREATE TABLE IF NOT EXISTS challenges (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    date_creation DATE NOT NULL,
    id_cohorte INT,
    statut TINYINT DEFAULT 0,
    tour INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_cohorte) REFERENCES cohortes(id)
);

-- Table matches
CREATE TABLE IF NOT EXISTS matches (
    id INT PRIMARY KEY AUTO_INCREMENT,
    participant1_id INT,
    participant2_id INT,
    challenge_id INT,
    gagnant_id INT DEFAULT NULL,
    statut TINYINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (challenge_id) REFERENCES challenges(id),
    FOREIGN KEY (participant1_id) REFERENCES utilisateurs(id),
    FOREIGN KEY (participant2_id) REFERENCES utilisateurs(id),
    FOREIGN KEY (gagnant_id) REFERENCES utilisateurs(id)
);

-- Table predictions
CREATE TABLE IF NOT EXISTS predictions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    match_id INT,
    participant_id INT,
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (match_id) REFERENCES matches(id),
    FOREIGN KEY (participant_id) REFERENCES utilisateurs(id),
    FOREIGN KEY (user_id) REFERENCES utilisateurs(id)
);

-- Table notifications
CREATE TABLE IF NOT EXISTS notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    message TEXT NOT NULL,
    type VARCHAR(50) DEFAULT 'info',
    link VARCHAR(255),
    lu TINYINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES utilisateurs(id)
);

-- Données de test initiales
INSERT INTO cohortes (nom) VALUES ('Cohorte Test');

-- Utilisateurs de test
INSERT INTO utilisateurs (email, password, nom, prenom) VALUES 
('test1@example.com', '$2y$10$abcdefghijklmnopqrstuv', 'User1', 'Test'),
('test2@example.com', '$2y$10$abcdefghijklmnopqrstuv', 'User2', 'Test'),
('test3@example.com', '$2y$10$abcdefghijklmnopqrstuv', 'User3', 'Test'),
('test4@example.com', '$2y$10$abcdefghijklmnopqrstuv', 'User4', 'Test');

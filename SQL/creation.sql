-- Fichier : creation_db.sql

-- 1. Création de la base de données (si elle n'existe pas)
-- Remplacez 'warhammer_db' par le nom que vous souhaitez donner à votre base.
CREATE DATABASE IF NOT EXISTS warhammer_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Sélectionner la base de données
USE warhammer_db;

-- 2. Création de la table 'roles'
-- Cette table définit les types de rôles (Humain/Non-Humain)
CREATE TABLE roles (
    role_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nom_role VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Insertion des rôles
-- Ces rôles sont essentiels pour le contexte 40K
INSERT INTO roles (nom_role) VALUES
('Humain (Sujet de l\'Imperium)'),
('Non-Humain (Xenos)');


-- 4. Création de la table 'users'
-- Note : L'ID est la clé primaire, le mot de passe est haché (CHAR 255 minimum)
CREATE TABLE users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL, -- Doit être assez long pour les hachages modernes (ex: 255)
    role_id INT UNSIGNED NOT NULL,
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    -- Clé étrangère pour lier l'utilisateur à un rôle
    FOREIGN KEY (role_id) REFERENCES roles(role_id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
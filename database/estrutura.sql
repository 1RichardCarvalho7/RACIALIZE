CREATE DATABASE IF NOT EXISTS racialize_direitos
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE racialize_direitos;

CREATE TABLE IF NOT EXISTS administrador (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nivelAdministrador TINYINT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    senha_hash VARCHAR(255) NOT NULL,
) ENGINE=InnoDB;

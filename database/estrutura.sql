CREATE DATABASE IF NOT EXISTS racialize_direitos
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE racialize_direitos;

-- 1. Administradores
-- nivelAdministrador:
--   1 = Administrador geral (primeira conta; intocável)
--   2 = Gerenciador (gerencia administradores, exceto o nível 1, e conteúdos)
--   3 = Editor (somente cria, edita e exclui conteúdos)
CREATE TABLE IF NOT EXISTS administrador (
    id_administrador INT AUTO_INCREMENT PRIMARY KEY,
    nivelAdministrador TINYINT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    senhaHash VARCHAR(255) NOT NULL,
    UNIQUE KEY uq_administrador_nome (nome)
) ENGINE=InnoDB;

-- 2. Categorias
CREATE TABLE IF NOT EXISTS categoria (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT
) ENGINE=InnoDB;

-- 3. Artigos
CREATE TABLE IF NOT EXISTS artigo (
    id_artigo INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    conteudo TEXT NOT NULL,
    data_publicacao DATE,
    id_administrador INT NOT NULL,
    id_categoria INT NOT NULL,
    FOREIGN KEY (id_administrador) REFERENCES administrador (id_administrador),
    FOREIGN KEY (id_categoria) REFERENCES categoria (id_categoria)
) ENGINE=InnoDB;

-- 4. Vídeos
CREATE TABLE IF NOT EXISTS video (
    id_video INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descricao TEXT,
    url_video VARCHAR(500) NOT NULL,
    id_administrador INT NOT NULL,
    id_categoria INT NOT NULL,
    id_artigo INT NOT NULL,
    FOREIGN KEY (id_artigo) REFERENCES artigo (id_artigo),
    FOREIGN KEY (id_administrador) REFERENCES administrador (id_administrador),
    FOREIGN KEY (id_categoria) REFERENCES categoria (id_categoria)
) ENGINE=InnoDB;

-- 5. Gráficos
CREATE TABLE IF NOT EXISTS grafico (
    id_grafico INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descricao TEXT,
    fonte_dados VARCHAR(300),
    imagem VARCHAR(500),
    id_administrador INT NOT NULL,
    id_categoria INT NOT NULL,
    FOREIGN KEY (id_administrador) REFERENCES administrador (id_administrador),
    FOREIGN KEY (id_categoria) REFERENCES categoria (id_categoria)
) ENGINE=InnoDB;

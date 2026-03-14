<?php

function runMigrations($conn)
{

    $conn->exec("
        CREATE TABLE IF NOT EXISTS usuarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(150) NOT NULL,
            telefone VARCHAR(20),
            email VARCHAR(150) UNIQUE,
            senha VARCHAR(255) NOT NULL,
            tipo_usuario TINYINT NOT NULL DEFAULT 1,
            criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            atualizado_em TIMESTAMP NULL DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

    $conn->exec("
        ALTER TABLE usuarios
        ADD COLUMN IF NOT EXISTS tipo_usuario TINYINT NOT NULL DEFAULT 1 AFTER senha,
        ADD COLUMN IF NOT EXISTS atualizado_em TIMESTAMP NULL DEFAULT NULL AFTER criado_em
    ");

    $conn->exec("
        CREATE TABLE IF NOT EXISTS carousel (
            id INT AUTO_INCREMENT PRIMARY KEY,
            imagem VARCHAR(255) NOT NULL,
            ativo TINYINT(1) DEFAULT 1,
            criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

    $conn->exec("
        CREATE TABLE IF NOT EXISTS pedidos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            usuario_id INT NOT NULL,
            total DECIMAL(10,2) NOT NULL DEFAULT 0,
            status VARCHAR(30) NOT NULL DEFAULT 'pendente',
            criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            atualizado_em TIMESTAMP NULL DEFAULT NULL,

            INDEX idx_usuario (usuario_id),
            CONSTRAINT fk_pedidos_usuario
            FOREIGN KEY (usuario_id)
            REFERENCES usuarios(id)
            ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

    $conn->exec("
        CREATE TABLE IF NOT EXISTS produtos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(255) NOT NULL,
            descricao TEXT NULL,
            preco DECIMAL(10,2) NOT NULL DEFAULT 0,
            preco_pj DECIMAL(10,2) DEFAULT NULL,
            estoque INT NOT NULL DEFAULT 0,
            imagem VARCHAR(255) NULL,
            ativo TINYINT(1) NOT NULL DEFAULT 1,
            criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            atualizado_em TIMESTAMP NULL DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

    $conn->exec("
        ALTER TABLE produtos
        ADD COLUMN IF NOT EXISTS preco_pj DECIMAL(10,2) DEFAULT NULL AFTER preco
    ");

}
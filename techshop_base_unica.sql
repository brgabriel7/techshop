-- Módulo techshop base unica da plataforma TechShop.
-- Projecto: TechShop — Loja Virtual de Acessórios Informáticos
-- Autor: Bernabé Rofino Gabriel
-- Instituição: Universidade Licungo
-- Curso: Mestrado em Tecnologias Educativas e Inovação
-- Módulo: Aplicações Web
-- Docente: Prof. Félix Singo
-- Ano: 2026
-- Direitos: Todos os direitos reservados.

-- TECHSHOP | INSTALACAO UNICA | MySQL / MariaDB | UTF-8
-- Importar este ficheiro UMA VEZ no phpMyAdmin, numa instalacao nova.
-- Usa CREATE TABLE IF NOT EXISTS: nao elimina tabelas ou dados existentes.
-- Para bases antigas sem coluna produtos.imagem, consultar README_INSTALACAO.md.
CREATE DATABASE IF NOT EXISTS `loja` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `loja`;

CREATE TABLE IF NOT EXISTS `utilizadores` (
 `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
 `nome` VARCHAR(100) NOT NULL,
 `senha_hash` VARCHAR(255) NOT NULL,
 `criado_em` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`), UNIQUE KEY `uq_utilizadores_nome` (`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `produtos` (
 `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
 `nome` VARCHAR(200) NOT NULL,
 `categoria` VARCHAR(120) NOT NULL,
 `preco` DECIMAL(12,2) NOT NULL,
 `stock` INT UNSIGNED NOT NULL DEFAULT 0,
 `imagem` VARCHAR(255) DEFAULT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `clientes` (
 `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
 `nome` VARCHAR(160) NOT NULL,
 `email` VARCHAR(190) DEFAULT NULL,
 `telefone` VARCHAR(35) NOT NULL,
 `endereco` VARCHAR(255) DEFAULT NULL,
 `foto` VARCHAR(120) DEFAULT NULL,
 `criado_em` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `encomendas` (
 `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
 `nome_cliente` VARCHAR(160) NOT NULL,
 `telefone` VARCHAR(25) NOT NULL,
 `endereco` VARCHAR(255) NOT NULL DEFAULT '',
 `metodo_pagamento` ENUM('mpesa','emola','whatsapp') NOT NULL,
 `total` DECIMAL(12,2) NOT NULL,
 `estado` ENUM('pendente','pago','cancelado') NOT NULL DEFAULT 'pendente',
 `criado_em` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `encomenda_itens` (
 `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
 `encomenda_id` INT UNSIGNED NOT NULL,
 `produto_id` INT UNSIGNED DEFAULT NULL,
 `quantidade` INT UNSIGNED NOT NULL,
 `preco_unitario` DECIMAL(12,2) NOT NULL,
 PRIMARY KEY (`id`),
 KEY `idx_encomenda` (`encomenda_id`),
 KEY `idx_produto` (`produto_id`),
 CONSTRAINT `fk_itens_encomenda` FOREIGN KEY (`encomenda_id`) REFERENCES `encomendas` (`id`) ON DELETE CASCADE,
 CONSTRAINT `fk_itens_produto` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

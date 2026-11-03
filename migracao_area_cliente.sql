-- Migração da base de dados: migracao area cliente.
-- Projecto: TechShop — Loja Virtual de Acessórios Informáticos
-- Autor: Bernabé Rofino Gabriel
-- Instituição: Universidade Licungo
-- Curso: Mestrado em Tecnologias Educativas e Inovação
-- Módulo: Aplicações Web
-- Docente: Prof. Félix Singo
-- Ano: 2026
-- Direitos: Todos os direitos reservados.

-- Executar uma única vez na base loja existente. Fazer backup antes.
USE `loja`;
CREATE TABLE IF NOT EXISTS `contas_clientes` (
 `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
 `nome` VARCHAR(160) NOT NULL,
 `email` VARCHAR(190) NOT NULL,
 `telefone` VARCHAR(35) NOT NULL DEFAULT '',
 `endereco` VARCHAR(255) NOT NULL DEFAULT '',
 `foto` VARCHAR(120) DEFAULT NULL,
 `senha_hash` VARCHAR(255) NOT NULL,
 `criado_em` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 UNIQUE KEY `uq_contas_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `carrinho_guardado` (
 `cliente_id` INT UNSIGNED NOT NULL,
 `produto_id` INT NOT NULL,
 `quantidade` INT UNSIGNED NOT NULL,
 PRIMARY KEY (`cliente_id`,`produto_id`),
 CONSTRAINT `fk_carrinho_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `contas_clientes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- A tabela encomendas da instalação actual utiliza id INT UNSIGNED.
ALTER TABLE `encomendas` ADD COLUMN `cliente_id` INT UNSIGNED DEFAULT NULL;
CREATE INDEX `idx_encomendas_cliente` ON `encomendas` (`cliente_id`);
ALTER TABLE `encomendas` ADD CONSTRAINT `fk_encomendas_conta` FOREIGN KEY (`cliente_id`) REFERENCES `contas_clientes` (`id`) ON DELETE SET NULL;

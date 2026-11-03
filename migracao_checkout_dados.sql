-- Migração da base de dados: migracao checkout dados.
-- Projecto: TechShop — Loja Virtual de Acessórios Informáticos
-- Autor: Bernabé Rofino Gabriel
-- Instituição: Universidade Licungo
-- Curso: Mestrado em Tecnologias Educativas e Inovação
-- Módulo: Aplicações Web
-- Docente: Prof. Félix Singo
-- Ano: 2026
-- Direitos: Todos os direitos reservados.

-- Para instalações com a área do cliente já criada; executar uma única vez.
USE `loja`;
ALTER TABLE `contas_clientes` ADD COLUMN `endereco` VARCHAR(255) NOT NULL DEFAULT '' AFTER `telefone`;

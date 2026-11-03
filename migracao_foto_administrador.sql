-- Migração da base de dados: migracao foto administrador.
-- Projecto: TechShop — Loja Virtual de Acessórios Informáticos
-- Autor: Bernabé Rofino Gabriel
-- Instituição: Universidade Licungo
-- Curso: Mestrado em Tecnologias Educativas e Inovação
-- Módulo: Aplicações Web
-- Docente: Prof. Félix Singo
-- Ano: 2026
-- Direitos: Todos os direitos reservados.

-- Executar UMA VEZ na base loja. Não apaga registos.
USE `loja`;
ALTER TABLE `utilizadores` ADD COLUMN `foto` VARCHAR(120) DEFAULT NULL;

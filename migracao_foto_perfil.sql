-- Migração da base de dados: migracao foto perfil.
-- Projecto: TechShop — Loja Virtual de Acessórios Informáticos
-- Autor: Bernabé Rofino Gabriel
-- Instituição: Universidade Licungo
-- Curso: Mestrado em Tecnologias Educativas e Inovação
-- Módulo: Aplicações Web
-- Docente: Prof. Félix Singo
-- Ano: 2026
-- Direitos: Todos os direitos reservados.

-- Executar UMA VEZ na base de dados loja, se contas_clientes ainda não tem foto.
USE `loja`;
ALTER TABLE `contas_clientes` ADD COLUMN `foto` VARCHAR(120) DEFAULT NULL;

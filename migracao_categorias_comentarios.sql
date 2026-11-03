-- TechShop | Bernabé Rofino Gabriel | Universidade Licungo
-- Mestrado em Tecnologias Educativas e Inovação | Aplicações Web
-- Docente: Prof. Félix Singo | 2026 | Direitos reservados
-- Seleccionar a base de dados da TechShop no phpMyAdmin antes de executar.
-- Executar uma única vez; não elimina os produtos existentes.

CREATE TABLE IF NOT EXISTS categorias_produtos (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL UNIQUE,
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Importar as categorias que já estão associadas aos produtos existentes.
INSERT IGNORE INTO categorias_produtos (nome)
SELECT DISTINCT TRIM(categoria) FROM produtos
WHERE categoria IS NOT NULL AND TRIM(categoria) <> '';

-- Criar categorias iniciais (pode adicionar ou gerir outras pelo painel).
INSERT IGNORE INTO categorias_produtos (nome) VALUES
('Teclados'), ('Ratos'), ('Cabos e adaptadores'),
('Armazenamento'), ('Áudio e vídeo'), ('Redes e conectividade'),
('Acessórios para portáteis'), ('Impressoras e consumíveis'),
('Monitores'), ('Outros');

-- ATENÇÃO: executar esta instrução apenas uma vez.
ALTER TABLE produtos ADD COLUMN comentarios TEXT NULL;

<?php
/**
 * TechShop — Projecto académico de Bernabé Rofino Gabriel.
 * Universidade Licungo | Mestrado em Tecnologias Educativas e Inovação.
 * Módulo: Aplicações Web | Docente: Prof. Félix Singo | 2026.
 * Todos os direitos reservados.
 */
/** Obtém as categorias disponíveis, ordenadas alfabeticamente. */
function categorias_disponiveis(PDO $pdo): array {
    return $pdo->query('SELECT nome FROM categorias_produtos ORDER BY nome')->fetchAll(PDO::FETCH_COLUMN);
}
/** Confirma que a categoria seleccionada foi previamente cadastrada. */
function categoria_valida(PDO $pdo, string $nome): bool {
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM categorias_produtos WHERE nome = ?');
    $stmt->execute([$nome]);
    return (int)$stmt->fetchColumn() > 0;
}

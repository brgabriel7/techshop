<?php
/**
 * Processamento de eliminação de utilizador; verificar permissões e dados.
 * Projecto: TechShop — Loja Virtual de Acessórios Informáticos
 * Autor: Bernabé Rofino Gabriel
 * Instituição: Universidade Licungo
 * Curso: Mestrado em Tecnologias Educativas e Inovação
 * Módulo: Aplicações Web
 * Docente: Prof. Félix Singo
 * Ano: 2026
 * Direitos: Todos os direitos reservados.
 */

// Exclusão segura: apenas POST, com CSRF e protecção da conta actual.
// Carrega as dependências e funções necessárias para esta página.
require'config.php';
auth();
// Processa os dados enviados pelo formulário através de uma requisição HTTP.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405);
 exit('Método não permitido.');
 }
// Confirma o token de segurança para proteger a operação contra CSRF.
check_token();
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) { http_response_code(400);
 exit('Identificador inválido.');
 }
if ($id === (int)$_SESSION['user_id']) go('lista_utilizadores.php?aviso=protegido');
// A transacção evita apagar o último utilizador em operações simultâneas.
// Inicia uma transacção para manter a consistência dos dados.
$pdo->beginTransaction();
try {
    $ids = $pdo->query('SELECT id FROM utilizadores ORDER BY id FOR UPDATE')->fetchAll(PDO::FETCH_COLUMN);
    if (count($ids) <= 1) { $pdo->rollBack();
 go('lista_utilizadores.php?aviso=protegido');
 }
    // Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
    $stmt = $pdo->prepare('DELETE FROM utilizadores WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $apagado = $stmt->rowCount() > 0;
    // Confirma as alterações na base de dados após a operação bem-sucedida.
    $pdo->commit();
    go('lista_utilizadores.php?aviso=' . ($apagado ? 'apagado' : 'inexistente'));
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    error_log($e->getMessage());
    http_response_code(500);
 exit('Não foi possível apagar o utilizador.');
}

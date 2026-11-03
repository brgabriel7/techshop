<?php
/**
 * Processamento de eliminação de produto; verificar permissões e dados.
 * Projecto: TechShop — Loja Virtual de Acessórios Informáticos
 * Autor: Bernabé Rofino Gabriel
 * Instituição: Universidade Licungo
 * Curso: Mestrado em Tecnologias Educativas e Inovação
 * Módulo: Aplicações Web
 * Docente: Prof. Félix Singo
 * Ano: 2026
 * Direitos: Todos os direitos reservados.
 */

// Apaga um produto. Apenas aceita POST e valida o token CSRF.
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
// Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
$stmt = $pdo->prepare('DELETE FROM produtos WHERE id = :id');
$stmt->execute([':id' => $id]);
go('lista_produtos.php?aviso=' . ($stmt->rowCount() ? 'apagado' : 'inexistente'));

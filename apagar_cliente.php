<?php
/**
 * Processamento de eliminação de cliente; verificar permissões e dados.
 * Projecto: TechShop — Loja Virtual de Acessórios Informáticos
 * Autor: Bernabé Rofino Gabriel
 * Instituição: Universidade Licungo
 * Curso: Mestrado em Tecnologias Educativas e Inovação
 * Módulo: Aplicações Web
 * Docente: Prof. Félix Singo
 * Ano: 2026
 * Direitos: Todos os direitos reservados.
 */

// Carrega as dependências e funções necessárias para esta página.

require'config.php';
 auth();
 // Carrega as dependências e funções necessárias para esta página.
 require'cliente_foto.php';
// Processa os dados enviados pelo formulário através de uma requisição HTTP.
if ($_SERVER['REQUEST_METHOD']!=='POST') { http_response_code(405);
 exit('Método não permitido.');
 }
// Confirma o token de segurança para proteger a operação contra CSRF.
check_token();
 $id=filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT);
if (!$id) { http_response_code(400);
 exit('ID inválido.');
 }
// Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
$stmt=$pdo->prepare('SELECT foto FROM clientes WHERE id=?');
 $stmt->execute([$id]);
 $foto=$stmt->fetchColumn();
// Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
$stmt=$pdo->prepare('DELETE FROM clientes WHERE id=?');
 $stmt->execute([$id]);
if ($stmt->rowCount()) apagar_foto_cliente($foto?:null);
go('lista_clientes.php?ok=apagado');

<?php
/**
 * Autenticação de clientes.
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

require_once __DIR__.'/config.php';
if(cliente_id()) go('minha_conta.php');
$erro='';
// Processa os dados enviados pelo formulário através de uma requisição HTTP.
if($_SERVER['REQUEST_METHOD']==='POST') {
 // Confirma o token de segurança para proteger a operação contra CSRF.
 check_token();
$email=mb_strtolower(trim($_POST['email']??''));
$senha=$_POST['senha']??'';
 // Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
 $stmt=$pdo->prepare('SELECT id,nome,senha_hash FROM contas_clientes WHERE email=? LIMIT 1');
$stmt->execute([$email]);
$c=$stmt->fetch();
 if($c && password_verify($senha,$c['senha_hash'])) {
  session_regenerate_id(true);
$_SESSION['cliente_id']=(int)$c['id'];
$_SESSION['cliente_nome']=$c['nome'];
  carregar_carrinho($pdo);
go('minha_conta.php');
 }$erro='E-mail ou palavra-passe incorrectos.';
}
?><!doctype html><html lang="pt"><head>
<link rel="icon" type="image/x-icon" href="favicon.ico?v=2">
<link rel="icon" type="image/png" sizes="32x32" href="assets/favicon-32.png?v=2">
<link rel="apple-touch-icon" href="assets/techshop-icon-192.png?v=2"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Entrar | TechShop</title><link rel="stylesheet" href="assets/loja.css?v=20261103-menu"></head><body><?php include __DIR__.'/public_header.php';
 ?><main class="wrap account-wrap"><section class="cart-panel account-card"><h1>Área do cliente</h1><p class="muted">Entre para ver as suas compras e o carrinho guardado.</p><?php if($erro):?><p class="account-error"><?=h($erro)?></p><?php endif;?><form method="post"><input type="hidden" name="csrf" value="<?=h(token())?>"><label class="field">E-mail<input type="email" name="email" required autocomplete="email"></label><label class="field">Palavra-passe<input type="password" name="senha" required autocomplete="current-password"></label><button class="primary" type="submit">Entrar</button></form><p>Não tem conta? <a class="account-link" href="cliente_registar.php">Registar-me</a></p></section></main><?php include __DIR__.'/public_footer.php';
 ?>
</body></html>
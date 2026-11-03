<?php
/**
 * Autenticação de administradores.
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
if (!empty($_SESSION['user_id'])) go('painel.php');
$erro='';
// Processa os dados enviados pelo formulário através de uma requisição HTTP.
if ($_SERVER['REQUEST_METHOD']==='POST') {
    // Confirma o token de segurança para proteger a operação contra CSRF.
    check_token();
    $nome=trim($_POST['nome']??'');
 $senha=$_POST['senha']??'';
    // Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
    $stmt=$pdo->prepare('SELECT id,nome,senha_hash FROM utilizadores WHERE nome=:nome LIMIT 1');
    $stmt->execute([':nome'=>$nome]);
 $user=$stmt->fetch();
    if ($user && password_verify($senha,$user['senha_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id']=$user['id'];
 $_SESSION['nome']=$user['nome'];
        go('painel.php');
    }
    $erro='Utilizador ou palavra-passe incorrectos.';
}
?><!doctype html>
<html lang="pt"><head>
<link rel="icon" type="image/x-icon" href="favicon.ico?v=2">
<link rel="icon" type="image/png" sizes="32x32" href="assets/favicon-32.png?v=2">
<link rel="apple-touch-icon" href="assets/techshop-icon-192.png?v=2"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Entrar no painel | TechShop</title>
<link rel="stylesheet" href="assets/style.css"><link rel="stylesheet" href="assets/login_moderno.css">
</head><body class="login-page">
<main class="login-shell">
 <section class="login-intro" aria-label="Apresentação da TechShop">
  <a href="loja.php" class="login-brand"><span class="login-brand-icon">▣</span><span><strong>TechShop</strong><small>Loja de acessórios informáticos</small></span></a>
  <div class="login-intro-content"><span class="login-eyebrow">ÁREA RESERVADA</span><h1>Bem-vindo de volta.</h1><p>Um espaço simples para gerir os seus produtos, acompanhar encomendas e cuidar dos seus clientes.</p><div class="login-intro-line"></div><span class="login-caption">Gestão organizada. Trabalho mais simples.</span></div>
  <div class="login-intro-footer">© <?=date('Y')?> TechShop</div>
 </section>
 <section class="login-main" aria-labelledby="login-heading">
  <div class="login-card">
   <img class="login-logo" src="assets/oshop.png" alt="Logótipo TechShop">
   <div class="login-heading"><span class="login-eyebrow login-eyebrow-light">ACESSO ADMINISTRATIVO</span><h2 id="login-heading">Entrar no painel</h2><p>Introduza os seus dados para continuar.</p></div>
   <?php if($erro): ?><div class="alert error" role="alert"><?=h($erro)?></div><?php endif;
 ?>
   <form method="post" autocomplete="on">
    <input type="hidden" name="csrf" value="<?=h(token())?>">
    <label for="nome">Nome de utilizador</label><input id="nome" name="nome" value="<?=h($_POST['nome']??'')?>" required autocomplete="username" placeholder="O seu nome de utilizador" autofocus>
    <div class="login-password-label"><label for="senha">Palavra-passe</label></div>
    <div class="login-password-wrap"><input id="senha" type="password" name="senha" required autocomplete="current-password" placeholder="Introduza a palavra-passe"><button type="button" class="login-toggle" id="toggle-password" aria-controls="senha" aria-label="Mostrar palavra-passe" aria-pressed="false">Mostrar</button></div>
    <button class="login-submit" type="submit">Entrar no sistema <span aria-hidden="true">→</span></button>
   </form>
   <div class="login-bottom"><a href="loja.php">← Voltar à loja pública</a></div>
   <p class="login-security">🔒 Acesso reservado aos utilizadores autorizados.</p>
  </div>
 </section>
</main>
<script src="assets/login_moderno.js" defer></script>
<?php include __DIR__.'/admin_footer.php';
 ?>
</body></html>

<?php
/**
 * Registo de utilizadores administrativos.
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
// O primeiro utilizador pode ser criado no registo. Depois, só alguém autenticado pode criar outros.
$total=(int)$pdo->query('SELECT COUNT(*) FROM utilizadores')->fetchColumn();
if ($total>0 && empty($_SESSION['user_id'])) { http_response_code(403);
 exit('O registo público está encerrado. Entre no sistema para criar outro utilizador.');
 }
$erro='';
// Processa os dados enviados pelo formulário através de uma requisição HTTP.
if ($_SERVER['REQUEST_METHOD']==='POST') {
    // Confirma o token de segurança para proteger a operação contra CSRF.
    check_token();
 $nome=trim($_POST['nome']??'');
 $senha=$_POST['senha']??'';
 $confirmar=$_POST['confirmar']??'';
    if (strlen($nome)<3 || strlen($senha)<8 || $senha!==$confirmar) $erro='Informe um nome com 3 caracteres, palavra-passe de pelo menos 8 caracteres e confirmação igual.';
    else { try { $stmt=$pdo->prepare('INSERT INTO utilizadores (nome,senha_hash) VALUES (:nome,:hash)');
 $stmt->execute([':nome'=>$nome,':hash'=>password_hash($senha,PASSWORD_DEFAULT)]);
 go(empty($_SESSION['user_id'])?'login.php':'painel.php');
 } catch(PDOException $e){$erro='Este utilizador já existe ou não foi possível guardar.';
} }
}
$autenticado = !empty($_SESSION['user_id']);
?><!doctype html>
<html lang="pt"><head>
<link rel="icon" type="image/x-icon" href="favicon.ico?v=2">
<link rel="icon" type="image/png" sizes="32x32" href="assets/favicon-32.png?v=2">
<link rel="apple-touch-icon" href="assets/techshop-icon-192.png?v=2"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Novo utilizador | TechShop</title><link rel="stylesheet" href="assets/style.css?v=reg20261103"></head>
<body class="admin-registration-page">
<?php if ($autenticado) include __DIR__.'/header.php';
 ?>
<main class="registration-container">
  <div class="registration-heading">
    <div><span class="registration-eyebrow">GESTÃO DE UTILIZADORES</span><h1>Novo utilizador</h1><p>Crie uma conta para um membro da equipa com acesso à administração da loja.</p></div>
    <?php if ($autenticado): ?><a class="registration-back" href="lista_utilizadores.php">← Voltar aos utilizadores</a><?php endif;
 ?>
  </div>
  <section class="registration-card">
    <div class="registration-card-heading"><div class="registration-icon" aria-hidden="true">♙</div><div><h2>Dados de acesso</h2><p>Preencha os campos abaixo para registar o utilizador.</p></div></div>
    <?php if($erro): ?><div class="alert error" role="alert"><?=h($erro)?></div><?php endif;
 ?>
    <form method="post" class="registration-form">
      <input type="hidden" name="csrf" value="<?=h(token())?>">
      <label for="novo-nome">Nome do utilizador</label>
      <input id="novo-nome" name="nome" required minlength="3" maxlength="100" autocomplete="username" placeholder="Ex.: Maria Silva" value="<?=h($_POST['nome']??'')?>">
      <label for="nova-senha">Palavra-passe <span class="registration-optional">(mínimo 8 caracteres)</span></label>
      <div class="registration-password"><input id="nova-senha" name="senha" type="password" required minlength="8" autocomplete="new-password" placeholder="Introduza uma palavra-passe segura"><button type="button" class="registration-reveal" data-password-target="nova-senha" aria-label="Mostrar palavra-passe" aria-pressed="false">Mostrar</button></div>
      <label for="nova-confirmar">Confirmar palavra-passe</label>
      <div class="registration-password"><input id="nova-confirmar" name="confirmar" type="password" required minlength="8" autocomplete="new-password" placeholder="Repita a palavra-passe"><button type="button" class="registration-reveal" data-password-target="nova-confirmar" aria-label="Mostrar confirmação" aria-pressed="false">Mostrar</button></div>
      <p class="registration-note">A palavra-passe será guardada de forma protegida. Partilhe os dados de acesso apenas com o utilizador autorizado.</p>
      <div class="registration-actions"><button type="submit" class="registration-submit">Guardar utilizador</button><a class="registration-cancel" href="<?=$autenticado?'lista_utilizadores.php':'login.php'?>">Cancelar</a></div>
    </form>
  </section>
</main>
<script src="assets/registar_utilizador.js" defer></script>
<?php include __DIR__.'/admin_footer.php';
 ?>
</body></html>

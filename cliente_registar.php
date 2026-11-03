<?php
/**
 * Registo de clientes.
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
// Carrega as dependências e funções necessárias para esta página.
require_once __DIR__.'/foto_perfil.php';
if(cliente_id()) go('minha_conta.php');
$erro='';
// Processa os dados enviados pelo formulário através de uma requisição HTTP.
if($_SERVER['REQUEST_METHOD']==='POST') {
 // Confirma o token de segurança para proteger a operação contra CSRF.
 check_token();
 $nome=trim($_POST['nome']??'');
 $email=mb_strtolower(trim($_POST['email']??''));
 $telefone=trim($_POST['telefone']??'');
 $endereco=trim($_POST['endereco']??'');
 $senha=$_POST['senha']??'';
 if(!$nome || mb_strlen($nome)>160 || !filter_var($email,FILTER_VALIDATE_EMAIL) || mb_strlen($email)>190 || mb_strlen($telefone)>35 || mb_strlen($endereco)>255 || strlen($senha)<8) $erro='Preencha os dados correctamente. A palavra-passe deve ter pelo menos 8 caracteres.';
 else {try {
  $foto=guardar_foto_perfil($_FILES['foto'] ?? []);
  // Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
  $stmt=$pdo->prepare('INSERT INTO contas_clientes (nome,email,telefone,endereco,senha_hash) VALUES (?,?,?,?,?)');
  $stmt->execute([$nome,$email,$telefone,$endereco,password_hash($senha,PASSWORD_DEFAULT)]);
  if($foto){$pdo->prepare('UPDATE contas_clientes SET foto=? WHERE id=?')->execute([$foto,(int)$pdo->lastInsertId()]);
}
  session_regenerate_id(true);
$_SESSION['cliente_id']=(int)$pdo->lastInsertId();
$_SESSION['cliente_nome']=$nome;
  carregar_carrinho($pdo);
go('minha_conta.php');
 }catch(Throwable $e){$erro=$e instanceof PDOException ? ($e->getCode()==='23000'?'Este e-mail já está registado.':'Não foi possível criar a conta.') : $e->getMessage();
}}
}
?>
<!doctype html><html lang="pt"><head>
<link rel="icon" type="image/x-icon" href="favicon.ico?v=2">
<link rel="icon" type="image/png" sizes="32x32" href="assets/favicon-32.png?v=2">
<link rel="apple-touch-icon" href="assets/techshop-icon-192.png?v=2"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Criar conta | TechShop</title><link rel="stylesheet" href="assets/loja.css?v=20261103-menu"></head><body>
<?php include __DIR__.'/public_header.php';
 ?>
<main class="wrap account-wrap"><section class="cart-panel account-card"><h1>Criar conta de cliente</h1><p class="muted">Guarde o seu carrinho e acompanhe as suas encomendas.</p>
<?php if($erro):?><p class="account-error"><?=h($erro)?></p><?php endif;?>
<form method="post" enctype="multipart/form-data"><input type="hidden" name="csrf" value="<?=h(token())?>"><label class="field">Nome completo<input name="nome" maxlength="160" required value="<?=h($_POST['nome']??'')?>"></label><label class="field">E-mail<input type="email" name="email" maxlength="190" required value="<?=h($_POST['email']??'')?>"></label><label class="field">Telefone<input name="telefone" maxlength="35" value="<?=h($_POST['telefone']??'')?>"></label><label class="field">Endereço de entrega<textarea name="endereco" maxlength="255"><?=h($_POST['endereco']??'')?></textarea></label><label class="field">Fotografia de perfil (opcional)<input type="file" name="foto" accept="image/jpeg,image/png,image/webp"><small>JPG, PNG ou WebP, até 2 MB.</small></label><label class="field">Palavra-passe (mínimo 8 caracteres)<input type="password" name="senha" minlength="8" required autocomplete="new-password"></label><button class="primary" type="submit">Criar conta</button></form></section></main><?php include __DIR__.'/public_footer.php';
 ?>
</body></html>

<?php
/**
 * Módulo cliente perfil da plataforma TechShop.
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
cliente_auth();
// Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
$stmt=$pdo->prepare('SELECT id,nome,email,telefone,endereco,foto FROM contas_clientes WHERE id=?');
$stmt->execute([cliente_id()]);
$c=$stmt->fetch();
if(!$c)go('cliente_login.php');
$erro='';
$ok='';
// Processa os dados enviados pelo formulário através de uma requisição HTTP.
if($_SERVER['REQUEST_METHOD']==='POST'){
 // Confirma o token de segurança para proteger a operação contra CSRF.
 check_token();
 $nome=trim($_POST['nome']??'');
$telefone=trim($_POST['telefone']??'');
$endereco=trim($_POST['endereco']??'');
 if(!$nome || mb_strlen($nome)>160 || mb_strlen($telefone)>35 || mb_strlen($endereco)>255){$erro='Verifique os dados introduzidos.';
}
 else { $novaFoto=null;
  try{
   $novaFoto=guardar_foto_perfil($_FILES['foto']??[]);
   $foto=$novaFoto??$c['foto'];
   // Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
   $st=$pdo->prepare('UPDATE contas_clientes SET nome=?,telefone=?,endereco=?,foto=? WHERE id=?');
   $st->execute([$nome,$telefone,$endereco,$foto,cliente_id()]);
   $_SESSION['cliente_nome']=$nome;
   if($novaFoto && url_foto_perfil($c['foto'])){@unlink(__DIR__.'/'.url_foto_perfil($c['foto']));
}
   $c['nome']=$nome;
$c['telefone']=$telefone;
$c['endereco']=$endereco;
$c['foto']=$foto;
$ok='Perfil actualizado com sucesso.';
  }catch(Throwable $e){if($novaFoto)@unlink(__DIR__.'/uploads/perfis/'.$novaFoto);
$erro=$e instanceof PDOException?'Não foi possível actualizar o perfil.':$e->getMessage();
}
 }
}
?><!doctype html><html lang="pt"><head>
<link rel="icon" type="image/x-icon" href="favicon.ico?v=2">
<link rel="icon" type="image/png" sizes="32x32" href="assets/favicon-32.png?v=2">
<link rel="apple-touch-icon" href="assets/techshop-icon-192.png?v=2"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Editar perfil | TechShop</title><link rel="stylesheet" href="assets/loja.css?v=20261103-menu"></head><body>
<?php include __DIR__.'/public_header.php';
 ?>
<main class="wrap account-wrap"><section class="cart-panel account-card"><h1>Editar o meu perfil</h1><p class="muted">Pode acrescentar ou substituir a fotografia quando desejar.</p>
<div class="profile-avatar profile-edit-avatar"><?php if(url_foto_perfil($c['foto'])):?><img src="<?=h(url_foto_perfil($c['foto']))?>" alt="Fotografia actual"><?php else:?><span><?=h(mb_strtoupper(mb_substr($c['nome'],0,1)))?></span><?php endif;?></div>
<?php if($erro):?><p class="account-error"><?=h($erro)?></p><?php endif;?><?php if($ok):?><p class="account-success"><?=h($ok)?></p><?php endif;?>
<form method="post" enctype="multipart/form-data"><input type="hidden" name="csrf" value="<?=h(token())?>">
<label class="field">Nome completo<input name="nome" maxlength="160" required value="<?=h($c['nome'])?>"></label>
<label class="field">E-mail<input value="<?=h($c['email'])?>" disabled></label>
<label class="field">Telefone<input name="telefone" maxlength="35" value="<?=h($c['telefone'])?>"></label>
<label class="field">Endereço de entrega<textarea name="endereco" maxlength="255"><?=h($c['endereco'])?></textarea></label>
<label class="field">Fotografia de perfil (opcional)<input type="file" name="foto" accept="image/jpeg,image/png,image/webp"><small>JPG, PNG ou WebP; máximo 2 MB. Deixe vazio para manter a fotografia actual.</small></label>
<button class="primary" type="submit">Guardar alterações</button></form></section></main><?php include __DIR__.'/public_footer.php';
 ?>
</body></html>

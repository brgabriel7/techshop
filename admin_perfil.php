<?php
/**
 * Módulo admin perfil da plataforma TechShop.
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
require_once __DIR__.'/admin_foto.php';
auth();
$id=(int)$_SESSION['user_id'];
// Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
$stmt=$pdo->prepare('SELECT id,nome,foto FROM utilizadores WHERE id=? LIMIT 1');
$stmt->execute([$id]);
$admin=$stmt->fetch();
if (!$admin) { go('logout.php');
 }
$erro='';
 $sucesso='';
// Processa os dados enviados pelo formulário através de uma requisição HTTP.
if ($_SERVER['REQUEST_METHOD']==='POST') {
    // Confirma o token de segurança para proteger a operação contra CSRF.
    check_token();
    $nome=trim($_POST['nome']??'');
    $senha=(string)($_POST['senha']??'');
    $confirmar=(string)($_POST['confirmar']??'');
    if (mb_strlen($nome)<3 || mb_strlen($nome)>100) $erro='O nome deve ter entre 3 e 100 caracteres.';
    elseif ($senha!=='' && (strlen($senha)<8 || $senha!==$confirmar)) $erro='A nova palavra-passe deve ter pelo menos 8 caracteres e corresponder à confirmação.';
    else {
        $novaFoto=null;
        try {
            $novaFoto=admin_guardar_foto($_FILES['foto']??[]);
            $foto=$novaFoto??$admin['foto'];
            if ($senha!=='') {
                // Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
                $up=$pdo->prepare('UPDATE utilizadores SET nome=?,foto=?,senha_hash=? WHERE id=?');
                $up->execute([$nome,$foto,password_hash($senha,PASSWORD_DEFAULT),$id]);
            } else {
                // Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
                $up=$pdo->prepare('UPDATE utilizadores SET nome=?,foto=? WHERE id=?');
                $up->execute([$nome,$foto,$id]);
            }
            if ($novaFoto && admin_foto_url($admin['foto'])) @unlink(__DIR__.'/'.admin_foto_url($admin['foto']));
            $admin['nome']=$nome;
 $admin['foto']=$foto;
 $_SESSION['nome']=$nome;
            $sucesso='Perfil actualizado com sucesso.';
        } catch (Throwable $e) {
            if ($novaFoto) @unlink(__DIR__.'/uploads/administradores/'.$novaFoto);
            error_log($e->getMessage());
            $erro=$e instanceof PDOException ? 'Não foi possível actualizar o perfil. Verifique se a migração SQL foi importada.' : $e->getMessage();
        }
    }
}
$fotoUrl=admin_foto_url($admin['foto']);
?><!doctype html><html lang="pt"><head>
<link rel="icon" type="image/x-icon" href="favicon.ico?v=2">
<link rel="icon" type="image/png" sizes="32x32" href="assets/favicon-32.png?v=2">
<link rel="apple-touch-icon" href="assets/techshop-icon-192.png?v=2"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Meu perfil | TechShop</title><link rel="stylesheet" href="assets/style.css"></head><body>
<?php include 'header.php';
 ?>
<main class="wrap admin-profile-layout">
  <div class="admin-profile-heading">
    <div><span class="admin-kicker">ÁREA ADMINISTRATIVA</span><h1>O meu perfil</h1>
    <p class="muted">Gerencie os seus dados pessoais e a segurança da conta.</p></div>
    <a class="btn gray" href="painel.php">← Voltar ao painel</a>
  </div>
  <?php if($erro): ?><p class="alert error"><?=h($erro)?></p><?php endif;
 ?>
  <?php if($sucesso): ?><p class="alert"><?=h($sucesso)?></p><?php endif;
 ?>
  <div class="admin-profile-grid">
    <aside class="panel admin-profile-summary">
      <div class="admin-avatar admin-avatar-large">
        <?php if($fotoUrl): ?><img src="<?=h($fotoUrl)?>" alt="Fotografia do administrador">
        <?php else: ?><span><?=h(mb_strtoupper(mb_substr($admin['nome'],0,1)))?></span><?php endif;
 ?>
      </div>
      <h2><?=h($admin['nome'])?></h2>
      <span class="admin-role">Administrador</span>
      <p class="muted">A sua fotografia aparece no cabeçalho de todas as páginas administrativas.</p>
    </aside>
    <section class="panel admin-profile-editor">
      <h2>Informações do perfil</h2>
      <p class="muted">Altere os dados necessários e guarde as modificações.</p>
      <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf" value="<?=h(token())?>">
        <label for="nome">Nome do administrador</label>
        <input id="nome" name="nome" required minlength="3" maxlength="100" value="<?=h($admin['nome'])?>">
        <label for="foto">Fotografia de perfil <span class="admin-optional">(opcional)</span></label>
        <input id="foto" name="foto" type="file" accept="image/jpeg,image/png,image/webp">
        <p class="hint">JPG, PNG ou WebP · máximo 2 MB. Deixe vazio para conservar a fotografia actual.</p>
        <div class="admin-form-divider"></div>
        <h3>Segurança da conta</h3>
        <p class="muted">Preencha apenas se pretender alterar a palavra-passe.</p>
        <label for="senha">Nova palavra-passe</label>
        <input id="senha" name="senha" type="password" minlength="8" autocomplete="new-password" placeholder="Mínimo 8 caracteres">
        <label for="confirmar">Confirmar nova palavra-passe</label>
        <input id="confirmar" name="confirmar" type="password" autocomplete="new-password" placeholder="Repita a nova palavra-passe">
        <div class="admin-form-actions"><button type="submit">Guardar alterações</button><a class="btn gray" href="painel.php">Cancelar</a></div>
      </form>
    </section>
  </div>
</main><?php include __DIR__.'/admin_footer.php';
 ?>
</body></html>

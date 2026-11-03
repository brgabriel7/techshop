<?php
/**
 * Consulta e actualização de cliente.
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
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
if (!$id) { http_response_code(400);
 exit('ID inválido.');
 }
// Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
$stmt=$pdo->prepare('SELECT * FROM clientes WHERE id=?');
 $stmt->execute([$id]);
 $c=$stmt->fetch();
if (!$c) { http_response_code(404);
 exit('Cliente não encontrado.');
 }
$erro='';
// Processa os dados enviados pelo formulário através de uma requisição HTTP.
if ($_SERVER['REQUEST_METHOD']==='POST') {
    // Confirma o token de segurança para proteger a operação contra CSRF.
    check_token();
    $c['nome']=trim($_POST['nome']??'');
 $c['telefone']=trim($_POST['telefone']??'');
    $c['email']=trim($_POST['email']??'');
 $c['endereco']=trim($_POST['endereco']??'');
    if ($c['nome']==='' || $c['telefone']==='') $erro='Nome e telefone são obrigatórios.';
    elseif ($c['email']!=='' && !filter_var($c['email'],FILTER_VALIDATE_EMAIL)) $erro='E-mail inválido.';
    else {
        $anterior=$c['foto'];
 $nova=$anterior;
        try {
            $nova=guardar_foto_cliente($_FILES['foto']??[],$anterior);
            // Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
            $stmt=$pdo->prepare('UPDATE clientes SET nome=?, telefone=?, email=?, endereco=?, foto=? WHERE id=?');
            $stmt->execute([$c['nome'],$c['telefone'],$c['email']?:null,$c['endereco']?:null,$nova,$id]);
            if ($nova!==$anterior) apagar_foto_cliente($anterior);
            go('lista_clientes.php?ok=editado');
        } catch (Throwable $e) { if($nova!==$anterior) apagar_foto_cliente($nova);
 error_log($e->getMessage());
 $erro='Não foi possível actualizar o cliente.';
 }
    }
}
?><!doctype html><html lang="pt"><head>
<link rel="icon" type="image/x-icon" href="favicon.ico?v=2">
<link rel="icon" type="image/png" sizes="32x32" href="assets/favicon-32.png?v=2">
<link rel="apple-touch-icon" href="assets/techshop-icon-192.png?v=2"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Editar cliente | TechShop</title><link rel="stylesheet" href="assets/style.css"></head><body><?php include 'header.php';
 ?><main class="wrap"><div class="panel client-form"><div class="rowhead"><h1>Editar cliente</h1><a href="lista_clientes.php">← Voltar</a></div><?=avatar_cliente($c)?><?php if($erro): ?><div class="alert error"><?=h($erro)?></div><?php endif;
 ?><form method="post" enctype="multipart/form-data"><input type="hidden" name="csrf" value="<?=h(token())?>"><div class="form-columns"><div><label>Nome completo *</label><input name="nome" required maxlength="160" value="<?=h($c['nome'])?>"></div><div><label>Telefone *</label><input name="telefone" required maxlength="35" value="<?=h($c['telefone'])?>"></div><div><label>E-mail</label><input name="email" type="email" maxlength="190" value="<?=h($c['email'])?>"></div><div><label>Endereço</label><input name="endereco" maxlength="255" value="<?=h($c['endereco'])?>"></div></div><label>Substituir fotografia</label><input type="file" name="foto" accept="image/jpeg,image/png,image/webp"><p class="hint">Deixe vazio para manter a fotografia actual. Máximo 2 MB.</p><button>Guardar alterações</button></form></div></main><?php include __DIR__.'/admin_footer.php';
 ?>
</body></html>
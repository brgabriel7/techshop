<?php
/**
 * Módulo cadastrar cliente da plataforma TechShop.
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
$erro='';
 $nome='';
 $email='';
 $telefone='';
 $endereco='';
// Processa os dados enviados pelo formulário através de uma requisição HTTP.
if ($_SERVER['REQUEST_METHOD']==='POST') {
    // Confirma o token de segurança para proteger a operação contra CSRF.
    check_token();
    $nome=trim($_POST['nome']??'');
 $email=trim($_POST['email']??'');
    $telefone=trim($_POST['telefone']??'');
 $endereco=trim($_POST['endereco']??'');
    if ($nome==='' || $telefone==='') $erro='Preencha o nome e o telefone.';
    elseif ($email!=='' && !filter_var($email,FILTER_VALIDATE_EMAIL)) $erro='Introduza um e-mail válido.';
    else {
        $foto=null;
        try {
            $foto=guardar_foto_cliente($_FILES['foto']??[]);
            // Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
            $stmt=$pdo->prepare('INSERT INTO clientes (nome,email,telefone,endereco,foto) VALUES (:nome,:email,:telefone,:endereco,:foto)');
            $stmt->execute(['nome'=>$nome,'email'=>$email?:null,'telefone'=>$telefone,'endereco'=>$endereco?:null,'foto'=>$foto]);
            go('lista_clientes.php?ok=criado');
        } catch (Throwable $e) { if ($foto) apagar_foto_cliente($foto);
 error_log($e->getMessage());
 $erro='Não foi possível registar o cliente. Verifique a base de dados e a fotografia.';
 }
    }
}
?><!doctype html><html lang="pt"><head>
<link rel="icon" type="image/x-icon" href="favicon.ico?v=2">
<link rel="icon" type="image/png" sizes="32x32" href="assets/favicon-32.png?v=2">
<link rel="apple-touch-icon" href="assets/techshop-icon-192.png?v=2"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Novo cliente | TechShop</title><link rel="stylesheet" href="assets/style.css"></head><body><?php include 'header.php';
 ?><main class="wrap"><div class="panel client-form"><div class="rowhead"><h1>Registar novo cliente</h1><a href="lista_clientes.php">← Voltar</a></div><p class="muted">Preencha os dados do cliente e adicione uma fotografia, se desejar.</p><?php if($erro): ?><div class="alert error"><?=h($erro)?></div><?php endif;
 ?><form method="post" enctype="multipart/form-data"><input type="hidden" name="csrf" value="<?=h(token())?>"><div class="form-columns"><div><label for="nome">Nome completo *</label><input id="nome" name="nome" required maxlength="160" value="<?=h($nome)?>"></div><div><label for="telefone">Telefone *</label><input id="telefone" name="telefone" required maxlength="35" value="<?=h($telefone)?>"></div><div><label for="email">E-mail</label><input id="email" type="email" name="email" maxlength="190" value="<?=h($email)?>"></div><div><label for="endereco">Endereço</label><input id="endereco" name="endereco" maxlength="255" value="<?=h($endereco)?>"></div></div><label for="foto">Fotografia do cliente</label><input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp"><p class="hint">Opcional. JPG, PNG ou WebP, até 2 MB.</p><button type="submit">Guardar cliente</button></form></div></main><?php include __DIR__.'/admin_footer.php';
 ?>
</body></html>
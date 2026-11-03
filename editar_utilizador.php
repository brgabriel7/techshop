<?php
/**
 * Consulta e actualização de utilizador.
 * Projecto: TechShop — Loja Virtual de Acessórios Informáticos
 * Autor: Bernabé Rofino Gabriel
 * Instituição: Universidade Licungo
 * Curso: Mestrado em Tecnologias Educativas e Inovação
 * Módulo: Aplicações Web
 * Docente: Prof. Félix Singo
 * Ano: 2026
 * Direitos: Todos os direitos reservados.
 */

// Edição do nome e, opcionalmente, da palavra-passe do utilizador.
// Carrega as dependências e funções necessárias para esta página.
require'config.php';
auth();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) { http_response_code(400);
 exit('Identificador inválido.');
 }
// Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
$stmt = $pdo->prepare('SELECT id, nome FROM utilizadores WHERE id = :id');
$stmt->execute([':id' => $id]);
$utilizador = $stmt->fetch();
if (!$utilizador) go('lista_utilizadores.php?aviso=inexistente');
$erro = '';
// Processa os dados enviados pelo formulário através de uma requisição HTTP.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Confirma o token de segurança para proteger a operação contra CSRF.
    check_token();
    $nome = trim($_POST['nome'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmar = $_POST['confirmar'] ?? '';
    if (mb_strlen($nome) < 3 || mb_strlen($nome) > 100 || ($senha !== '' && (strlen($senha) < 8 || $senha !== $confirmar))) {
        $erro = 'Informe um nome de 3 a 100 caracteres. Se mudar a palavra-passe, use pelo menos 8 caracteres e confirme-a.';
    } else {
        try {
            if ($senha !== '') {
                // Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
                $stmt = $pdo->prepare('UPDATE utilizadores SET nome = :nome, senha_hash = :hash WHERE id = :id');
                $stmt->execute([':nome' => $nome, ':hash' => password_hash($senha, PASSWORD_DEFAULT), ':id' => $id]);
            } else {
                // Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
                $stmt = $pdo->prepare('UPDATE utilizadores SET nome = :nome WHERE id = :id');
                $stmt->execute([':nome' => $nome, ':id' => $id]);
            }
            if ((int)$_SESSION['user_id'] === $id) $_SESSION['nome'] = $nome;
            go('lista_utilizadores.php?aviso=editado');
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $erro = 'O nome já está em uso ou não foi possível actualizar.';
        }
    }
    $utilizador['nome'] = $nome;
}
?>
<!doctype html><html lang="pt"><head>
<link rel="icon" type="image/x-icon" href="favicon.ico?v=2">
<link rel="icon" type="image/png" sizes="32x32" href="assets/favicon-32.png?v=2">
<link rel="apple-touch-icon" href="assets/techshop-icon-192.png?v=2"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Editar Utilizador | e-Loja</title><link rel="stylesheet" href="assets/style.css"></head><body>
<?php include 'header.php';
 ?>
<main class="wrap"><section class="panel formpanel"><h2>Editar Utilizador #<?= (int)$id ?></h2>
<?php if ($erro): ?><p class="alert error"><?= h($erro) ?></p><?php endif;
 ?>
<form method="post"><input type="hidden" name="csrf" value="<?= h(token()) ?>">
<label for="nome">Nome do utilizador</label><input id="nome" name="nome" required minlength="3" maxlength="100" value="<?= h($utilizador['nome']) ?>">
<label for="senha">Nova palavra-passe (opcional)</label><input id="senha" name="senha" type="password" minlength="8" autocomplete="new-password" placeholder="Deixe vazio para manter a actual">
<label for="confirmar">Confirmar nova palavra-passe</label><input id="confirmar" name="confirmar" type="password" autocomplete="new-password">
<div class="actions"><button type="submit">Guardar alterações</button><a class="btn gray" href="lista_utilizadores.php">Cancelar</a></div>
</form></section></main><?php include __DIR__.'/admin_footer.php';
 ?>
</body></html>

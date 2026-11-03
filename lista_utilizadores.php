<?php
/**
 * Listagem e consulta de utilizadores.
 * Projecto: TechShop — Loja Virtual de Acessórios Informáticos
 * Autor: Bernabé Rofino Gabriel
 * Instituição: Universidade Licungo
 * Curso: Mestrado em Tecnologias Educativas e Inovação
 * Módulo: Aplicações Web
 * Docente: Prof. Félix Singo
 * Ano: 2026
 * Direitos: Todos os direitos reservados.
 */

// Consulta dos utilizadores cadastrados.
// Carrega as dependências e funções necessárias para esta página.
require'config.php';
auth();
$utilizadores = $pdo->query('SELECT id, nome, criado_em FROM utilizadores ORDER BY id DESC')->fetchAll();
$aviso = $_GET['aviso'] ?? '';
?>
<!doctype html><html lang="pt"><head>
<link rel="icon" type="image/x-icon" href="favicon.ico?v=2">
<link rel="icon" type="image/png" sizes="32x32" href="assets/favicon-32.png?v=2">
<link rel="apple-touch-icon" href="assets/techshop-icon-192.png?v=2"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Utilizadores | e-Loja</title><link rel="stylesheet" href="assets/style.css"></head><body>
<?php include 'header.php';
 ?>
<main class="wrap"><section class="panel"><div class="rowhead"><h2>👥 Lista de Utilizadores</h2><a class="btn" href="registar.php">Novo utilizador</a></div>
<?php if ($aviso === 'editado'): ?><p class="alert">Utilizador actualizado com sucesso.</p><?php endif;
 ?>
<?php if ($aviso === 'apagado'): ?><p class="alert">Utilizador apagado com sucesso.</p><?php endif;
 ?>
<?php if ($aviso === 'protegido'): ?><p class="alert error">Não pode apagar a sua conta nem o último utilizador.</p><?php endif;
 ?>
<?php if ($aviso === 'inexistente'): ?><p class="alert error">Utilizador não encontrado.</p><?php endif;
 ?>
<div class="tablewrap"><table><thead><tr><th>ID</th><th>Utilizador</th><th>Data de registo</th><th>Acções</th></tr></thead><tbody>
<?php foreach ($utilizadores as $u): ?><tr>
<td>#<?= (int)$u['id'] ?></td><td><?= h($u['nome']) ?><?= (int)$u['id'] === (int)$_SESSION['user_id'] ? ' (Você)' : '' ?></td><td><?= h($u['criado_em']) ?></td>
<td><div class="table-actions"><a class="btn small" href="editar_utilizador.php?id=<?= (int)$u['id'] ?>">Editar</a>
<?php if ((int)$u['id'] !== (int)$_SESSION['user_id'] && count($utilizadores) > 1): ?>
<form method="post" action="apagar_utilizador.php" onsubmit="return confirm('Tem a certeza de que deseja apagar este utilizador?');">
<input type="hidden" name="csrf" value="<?= h(token()) ?>"><input type="hidden" name="id" value="<?= (int)$u['id'] ?>"><button type="submit" class="btn red small">Apagar</button></form>
<?php endif;
 ?></div></td></tr><?php endforeach;
 ?>
<?php if (!$utilizadores): ?><tr><td colspan="4">Nenhum utilizador registado.</td></tr><?php endif;
 ?>
</tbody></table></div></section></main><?php include __DIR__.'/admin_footer.php';
 ?>
</body></html>

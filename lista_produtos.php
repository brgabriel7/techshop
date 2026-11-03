<?php
/**
 * Listagem e consulta de produtos.
 * Projecto: TechShop — Loja Virtual de Acessórios Informáticos
 * Autor: Bernabé Rofino Gabriel
 * Instituição: Universidade Licungo
 * Curso: Mestrado em Tecnologias Educativas e Inovação
 * Módulo: Aplicações Web
 * Docente: Prof. Félix Singo
 * Ano: 2026
 * Direitos: Todos os direitos reservados.
 */

// Lista dos produtos, com botões para editar e apagar.
// Carrega as dependências e funções necessárias para esta página.
require'config.php';
auth();
$produtos = $pdo->query('SELECT id, nome, categoria, preco, stock FROM produtos ORDER BY id DESC')->fetchAll();
$aviso = $_GET['aviso'] ?? '';
?>
<!doctype html>
<html lang="pt"><head>
<link rel="icon" type="image/x-icon" href="favicon.ico?v=2">
<link rel="icon" type="image/png" sizes="32x32" href="assets/favicon-32.png?v=2">
<link rel="apple-touch-icon" href="assets/techshop-icon-192.png?v=2"><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Inventário | e-Loja</title><link rel="stylesheet" href="assets/style.css"></head>
<body><?php include 'header.php';
 ?>
<main class="wrap"><div class="panel">
<div class="rowhead"><h2>Inventário de Produtos - e-Loja</h2><a class="btn" href="inserir_produtos.php">Adicionar produto</a></div>
<?php if ($aviso === 'editado'): ?><p class="alert">Produto actualizado com sucesso.</p><?php endif;
 ?>
<?php if ($aviso === 'apagado'): ?><p class="alert">Produto apagado com sucesso.</p><?php endif;
 ?>
<?php if ($aviso === 'inexistente'): ?><p class="alert error">Produto não encontrado.</p><?php endif;
 ?>
<div class="tablewrap"><table><thead><tr><th>ID</th><th>Nome do Produto</th><th>Categoria</th><th>Preço</th><th>Stock</th><th>Acções</th></tr></thead><tbody>
<?php foreach ($produtos as $produto): ?>
<tr>
<td>#<?= h($produto['id']) ?></td>
<td><?= h($produto['nome']) ?></td>
<td><?= h($produto['categoria']) ?></td>
<td class="good">MT <?= number_format((float)$produto['preco'], 2, ',', '.') ?></td>
<td class="<?= (int)$produto['stock'] < 5 ? 'low' : '' ?>"><?= h($produto['stock']) ?> un.</td>
<td><div class="table-actions">
<a class="btn small" href="editar_produto.php?id=<?= (int)$produto['id'] ?>">Editar</a>
<form action="apagar_produto.php" method="post" onsubmit="return confirm('Tem a certeza de que deseja apagar este produto?');">
<input type="hidden" name="csrf" value="<?= h(token()) ?>">
<input type="hidden" name="id" value="<?= (int)$produto['id'] ?>">
<button type="submit" class="btn red small">Apagar</button>
</form></div></td>
</tr>
<?php endforeach;
 ?>
<?php if (!$produtos): ?><tr><td colspan="6" style="text-align:center">Nenhum produto registado.</td></tr><?php endif;
 ?>
</tbody></table></div></div></main><?php include __DIR__.'/admin_footer.php';
 ?>
</body></html>

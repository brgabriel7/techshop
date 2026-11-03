<?php
/**
 * Consulta e actualização de produto.
 * Projecto: TechShop — Loja Virtual de Acessórios Informáticos
 * Autor: Bernabé Rofino Gabriel
 * Instituição: Universidade Licungo
 * Curso: Mestrado em Tecnologias Educativas e Inovação
 * Módulo: Aplicações Web
 * Docente: Prof. Félix Singo
 * Ano: 2026
 * Direitos: Todos os direitos reservados.
 */

// Permite actualizar os dados de um produto já existente.
// Carrega as dependências e funções necessárias para esta página.
require'config.php';
auth();
// Carrega as dependências e funções necessárias para esta página.
require_once __DIR__ . '/produto_imagem.php';
require_once __DIR__ . '/categorias_helper.php';
$categorias = categorias_disponiveis($pdo);
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) { http_response_code(400);
 exit('Identificador inválido.');
 }

// Procurar produto usando consulta preparada (evita SQL injection).
// Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
$stmt = $pdo->prepare('SELECT id, nome, categoria, preco, stock, imagem, comentarios FROM produtos WHERE id = :id');
$stmt->execute([':id' => $id]);
$produto = $stmt->fetch();
if (!$produto) { go('lista_produtos.php?aviso=inexistente');
 }
$erro = '';

// Processa os dados enviados pelo formulário através de uma requisição HTTP.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Confirma o token de segurança para proteger a operação contra CSRF.
    check_token();
    $nome = trim($_POST['nome'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $preco = $_POST['preco'] ?? '';
    $stock = $_POST['stock'] ?? '';
    $comentarios = trim((string)($_POST['comentarios'] ?? ''));
    if ($nome === '' || !categoria_valida($pdo, $categoria) || mb_strlen($comentarios) > 5000 || mb_strlen($nome) > 200 || mb_strlen($categoria) > 120 || !is_numeric($preco) || !is_finite((float)$preco) || (float)$preco <= 0 || (float)$preco > 9999999999.99 || filter_var($stock, FILTER_VALIDATE_INT) === false || (int)$stock < 0) {
        $erro = 'Preencha correctamente todos os campos.';
    } else {
        // Actualizar apenas o produto identificado no URL.
        try { $novaImagem = guardar_imagem_produto($_FILES['imagem'] ?? []);
 } catch (RuntimeException $e) { $erro = $e->getMessage();
 }
        if ($erro === '') {
        $sql = 'UPDATE produtos SET nome = :nome, categoria = :categoria, preco = :preco, stock = :stock, imagem = :imagem, comentarios = :comentarios WHERE id = :id';
        // Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':nome' => $nome, ':categoria' => $categoria, ':preco' => $preco, ':stock' => (int)$stock, ':imagem' => $novaImagem ?? $produto['imagem'], ':comentarios' => $comentarios, ':id' => $id]);
        go('lista_produtos.php?aviso=editado');
        }
    }
    $produto = array_merge($produto, ['nome' => $nome, 'categoria' => $categoria, 'preco' => $preco, 'stock' => $stock, 'comentarios' => $comentarios]);
}
?>
<!doctype html><html lang="pt"><head>
<link rel="icon" type="image/x-icon" href="favicon.ico?v=2">
<link rel="icon" type="image/png" sizes="32x32" href="assets/favicon-32.png?v=2">
<link rel="apple-touch-icon" href="assets/techshop-icon-192.png?v=2"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Editar Produto | e-Loja</title><link rel="stylesheet" href="assets/style.css"></head><body>
<?php include 'header.php';
 ?>
<main class="wrap"><div class="panel formpanel"><h2>Editar Produto #<?= (int)$id ?></h2>
<?php if ($erro): ?><p class="alert error"><?= h($erro) ?></p><?php endif;
 ?>
<form method="post" enctype="multipart/form-data"><input type="hidden" name="csrf" value="<?= h(token()) ?>">
<label for="nome">Nome do Produto:</label><input id="nome" name="nome" type="text" maxlength="200" required value="<?= h($produto['nome']) ?>">
<label for="categoria">Categoria:</label>
<select id="categoria" name="categoria" required>
<option value="">Seleccione uma categoria</option>
<?php foreach ($categorias as $cat): ?>
<option value="<?= h($cat) ?>" <?= $produto['categoria'] === $cat ? 'selected' : '' ?>><?= h($cat) ?></option>
<?php endforeach; ?>
</select>
<p><a href="categorias_produtos.php">Gerir categorias</a></p>
<label for="preco">Preço (MT):</label><input id="preco" name="preco" type="number" min="0.01" step="0.01" required value="<?= h($produto['preco']) ?>">
<label for="stock">Quantidade em Stock:</label><input id="stock" name="stock" type="number" min="0" step="1" required value="<?= h($produto['stock']) ?>">
<label for="comentarios">Comentários / Descrição do produto:</label>
<textarea id="comentarios" name="comentarios" rows="5" maxlength="5000"><?= h($produto['comentarios'] ?? '') ?></textarea>
<label for="imagem">Alterar fotografia (opcional, JPG/PNG/WebP até 3 MB):</label><input id="imagem" name="imagem" type="file" accept="image/jpeg,image/png,image/webp"><div class="actions"><button type="submit">Guardar alterações</button><a class="btn gray" href="lista_produtos.php">Cancelar</a></div>
</form></div></main><?php include __DIR__.'/admin_footer.php';
 ?>
</body></html>

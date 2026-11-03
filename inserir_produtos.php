<?php
/**
 * TechShop — Projecto académico de Bernabé Rofino Gabriel.
 * Universidade Licungo | Mestrado em Tecnologias Educativas e Inovação.
 * Módulo: Aplicações Web | Docente: Prof. Félix Singo | 2026.
 * Todos os direitos reservados.
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/produto_imagem.php';
require_once __DIR__ . '/categorias_helper.php';
auth();

// Consultar categorias previamente registadas.
$categorias = categorias_disponiveis($pdo);
$mensagem = '';
$erro = '';
$nome = $categoria = $comentarios = $preco = $stock = '';

// Processar e validar a submissão do formulário.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_token();
    $nome = trim((string)($_POST['nome'] ?? ''));
    $categoria = trim((string)($_POST['categoria'] ?? ''));
    $comentarios = trim((string)($_POST['comentarios'] ?? ''));
    $preco = (string)($_POST['preco'] ?? '');
    $stock = (string)($_POST['stock'] ?? '');

    if ($nome === '' || mb_strlen($nome) > 200 || !categoria_valida($pdo, $categoria)
        || !is_numeric($preco) || (float)$preco <= 0 || (float)$preco > 9999999999.99
        || filter_var($stock, FILTER_VALIDATE_INT) === false || (int)$stock < 0
        || mb_strlen($comentarios) > 5000) {
        $erro = 'Verifique os dados e seleccione uma categoria cadastrada.';
    } else {
        try {
            // Validar e guardar a imagem, quando enviada.
            $imagem = guardar_imagem_produto($_FILES['imagem'] ?? []);
            // Guardar os dados usando parâmetros SQL seguros.
            $stmt = $pdo->prepare('INSERT INTO produtos
                (nome, categoria, preco, stock, imagem, comentarios)
                VALUES (:nome, :categoria, :preco, :stock, :imagem, :comentarios)');
            $stmt->execute([
                ':nome' => $nome, ':categoria' => $categoria,
                ':preco' => $preco, ':stock' => (int)$stock,
                ':imagem' => $imagem, ':comentarios' => $comentarios,
            ]);
            $mensagem = 'Produto registado com sucesso! ID: ' . $pdo->lastInsertId();
            $nome = $categoria = $comentarios = $preco = $stock = '';
        } catch (Throwable $e) {
            error_log($e->getMessage());
            $erro = 'Não foi possível guardar o produto. Verifique a migração da base de dados.';
        }
    }
}
?>
<!doctype html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registar produto | TechShop</title>
    <link rel="icon" href="favicon.ico">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include __DIR__ . '/header.php'; ?>
<main class="wrap">
    <div class="panel formpanel">
        <h2 style="text-align:center">Registar Novo Produto</h2>
        <img class="logo" src="assets/oshop.png" alt="TechShop">
        <?php if ($mensagem): ?><div class="alert"><?= h($mensagem) ?></div><?php endif; ?>
        <?php if ($erro): ?><div class="alert error"><?= h($erro) ?></div><?php endif; ?>

        <!-- Formulário para registar os dados e a fotografia do produto. -->
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf" value="<?= h(token()) ?>">
            <label for="nome">Nome do Produto:</label>
            <input id="nome" name="nome" maxlength="200" value="<?= h($nome) ?>" required>

            <label for="categoria">Categoria:</label>
            <select id="categoria" name="categoria" required>
                <option value="">Seleccione uma categoria</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= h($cat) ?>" <?= $categoria === $cat ? 'selected' : '' ?>><?= h($cat) ?></option>
                <?php endforeach; ?>
            </select>
            <p><a href="categorias_produtos.php">Gerir categorias</a></p>

            <label for="preco">Preço (MT):</label>
            <input id="preco" name="preco" type="number" min="0.01" step="0.01" value="<?= h($preco) ?>" required>
            <label for="stock">Quantidade em Stock:</label>
            <input id="stock" name="stock" type="number" min="0" step="1" value="<?= h($stock) ?>" required>
            <label for="comentarios">Comentários / Descrição do produto:</label>
            <textarea id="comentarios" name="comentarios" maxlength="5000" rows="5" placeholder="Características técnicas, observações e outras informações..."><?= h($comentarios) ?></textarea>
            <label for="imagem">Fotografia do produto (JPG, PNG ou WebP, até 3 MB):</label>
            <input id="imagem" type="file" name="imagem" accept="image/jpeg,image/png,image/webp">
            <button class="full" type="submit">Guardar Produto</button>
        </form>
        <div class="links"><a href="lista_produtos.php">Listar produtos</a></div>
    </div>
</main>
<?php include __DIR__ . '/admin_footer.php'; ?>
</body>
</html>

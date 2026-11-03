<?php
/**
 * TechShop — Projecto académico de Bernabé Rofino Gabriel.
 * Universidade Licungo | Mestrado em Tecnologias Educativas e Inovação.
 * Módulo: Aplicações Web | Docente: Prof. Félix Singo | 2026.
 * Todos os direitos reservados.
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/categorias_helper.php';
auth();
$erro = $mensagem = '';
// Apenas categorias novas e válidas podem ser registadas.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_token();
    $nome = trim((string)($_POST['nome'] ?? ''));
    if ($nome === '' || mb_strlen($nome) > 120) {
        $erro = 'Indique uma categoria com até 120 caracteres.';
    } elseif (categoria_valida($pdo, $nome)) {
        $erro = 'Esta categoria já está registada.';
    } else {
        try {
            $stmt = $pdo->prepare('INSERT INTO categorias_produtos (nome) VALUES (?)');
            $stmt->execute([$nome]);
            $mensagem = 'Categoria registada com sucesso.';
        } catch (Throwable $e) {
            error_log($e->getMessage());
            $erro = 'Não foi possível guardar a categoria.';
        }
    }
}
$categorias = categorias_disponiveis($pdo);
?>
<!doctype html>
<html lang="pt">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Gerir categorias | TechShop</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include __DIR__ . '/header.php'; ?>
<main class="wrap"><div class="panel formpanel">
<h2>Gestão de Categorias</h2>
<p>Registe as categorias que poderão ser seleccionadas nos formulários de produtos.</p>
<?php if ($mensagem): ?><p class="alert"><?= h($mensagem) ?></p><?php endif; ?>
<?php if ($erro): ?><p class="alert error"><?= h($erro) ?></p><?php endif; ?>
<form method="post">
<input type="hidden" name="csrf" value="<?= h(token()) ?>">
<label for="nome">Nova categoria:</label>
<input id="nome" name="nome" maxlength="120" required placeholder="Ex.: Equipamentos de rede">
<button type="submit">Guardar categoria</button>
</form>
<h3>Categorias cadastradas</h3>
<ul><?php foreach ($categorias as $cat): ?><li><?= h($cat) ?></li><?php endforeach; ?></ul>
<p><a href="inserir_produtos.php">Voltar ao registo de produtos</a></p>
</div></main>
<?php include __DIR__ . '/admin_footer.php'; ?>
</body></html>

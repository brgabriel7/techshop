<?php
/**
 * Gestão dos produtos e quantidades no carrinho.
 * Projecto: TechShop — Loja Virtual de Acessórios Informáticos
 * Autor: Bernabé Rofino Gabriel
 * Instituição: Universidade Licungo
 * Curso: Mestrado em Tecnologias Educativas e Inovação
 * Módulo: Aplicações Web
 * Docente: Prof. Félix Singo
 * Ano: 2026
 * Direitos: Todos os direitos reservados.
 */

/** Carrinho guardado na sessão. Os preços e stock são sempre consultados no MySQL. */
// Carrega as dependências e funções necessárias para esta página.
require_once __DIR__.'/config.php';
$_SESSION['carrinho'] ??= [];
// Processa os dados enviados pelo formulário através de uma requisição HTTP.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Confirma o token de segurança para proteger a operação contra CSRF.
    check_token();
    $acao = $_POST['acao'] ?? '';
    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
    if ($acao === 'adicionar' && $id && $id > 0) {
        // Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
        $stmt=$pdo->prepare('SELECT stock FROM produtos WHERE id=?');
$stmt->execute([$id]);
        $stock=$stmt->fetchColumn();
        if ($stock !== false && (int)$stock > 0) {
            $_SESSION['carrinho'][$id] = min((int)$stock, ($_SESSION['carrinho'][$id] ?? 0)+1);
        }
    } elseif ($acao === 'actualizar' && $id && $id > 0) {
        // Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
        $stmt=$pdo->prepare('SELECT stock FROM produtos WHERE id=?');
$stmt->execute([$id]);
        $stock=$stmt->fetchColumn();
        $q=filter_var($_POST['quantidade'] ?? null,FILTER_VALIDATE_INT);
        if ($stock === false || $q === false || $q < 1) unset($_SESSION['carrinho'][$id]);
        else $_SESSION['carrinho'][$id]=min($q,(int)$stock);
    } elseif ($acao === 'remover' && $id) unset($_SESSION['carrinho'][$id]);
    guardar_carrinho($pdo);
    go('carrinho.php');
}
$itens=[];
$total=0;
if ($_SESSION['carrinho']) {
    $ids=array_map('intval',array_keys($_SESSION['carrinho']));
    // Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
    $stmt=$pdo->prepare('SELECT id,nome,preco,stock,imagem FROM produtos WHERE id IN ('.implode(',',array_fill(0,count($ids),'?')).')');
    $stmt->execute($ids);
    // Percorre os registos e aplica o processamento necessário a cada item.
    foreach ($stmt as $p) {
        $id=(int)$p['id'];
$q=min((int)$_SESSION['carrinho'][$id],(int)$p['stock']);
        if ($q<=0) {unset($_SESSION['carrinho'][$id]);
continue;
}
        $_SESSION['carrinho'][$id]=$q;
$p['quantidade']=$q;
$p['subtotal']=$q*(float)$p['preco'];
$total+=$p['subtotal'];
$itens[]=$p;
    }
}
if (cliente_id()) guardar_carrinho($pdo);
function imagem_carrinho($v){$nome=basename((string)$v);
return $nome && is_file(__DIR__.'/uploads/produtos/'.$nome)?'uploads/produtos/'.rawurlencode($nome):'assets/img/produto.svg';
}
?>
<!doctype html><html lang="pt"><head>
<link rel="icon" type="image/x-icon" href="favicon.ico?v=2">
<link rel="icon" type="image/png" sizes="32x32" href="assets/favicon-32.png?v=2">
<link rel="apple-touch-icon" href="assets/techshop-icon-192.png?v=2"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Carrinho | TechShop</title><link rel="stylesheet" href="assets/loja.css?v=20261103-menu"></head><body>
<?php include __DIR__.'/public_header.php';
 ?>
<main class="wrap"><h1>O meu carrinho</h1><div class="cart-layout"><section class="cart-panel">
<?php if (!$itens): ?><p>O seu carrinho está vazio.</p><a class="primary" href="loja.php">Ver produtos</a><?php endif;
 ?>
<?php foreach($itens as $p): ?><div class="cart-row"><img src="<?=h(imagem_carrinho($p['imagem']))?>" alt=""><div class="item-name"><strong><?=h($p['nome'])?></strong><p class="muted"><?=number_format((float)$p['preco'],2,',','.')?> MT · <?= (int)$p['stock'] ?> disponíveis</p></div><form action="carrinho.php" method="post"><input type="hidden" name="csrf" value="<?=h(token())?>"><input type="hidden" name="acao" value="actualizar"><input type="hidden" name="id" value="<?=(int)$p['id']?>"><input aria-label="Quantidade" type="number" name="quantidade" min="1" max="<?=(int)$p['stock']?>" value="<?=(int)$p['quantidade']?>"><button type="submit">Actualizar</button></form><strong><?=number_format($p['subtotal'],2,',','.')?> MT</strong><form action="carrinho.php" method="post"><input type="hidden" name="csrf" value="<?=h(token())?>"><input type="hidden" name="acao" value="remover"><input type="hidden" name="id" value="<?=(int)$p['id']?>"><button type="submit">Remover</button></form></div><?php endforeach;
 ?>
</section><aside class="cart-panel"><h2>Resumo</h2><p><?=array_sum($_SESSION['carrinho'])?> unidade(s)</p><p class="cart-total"><?=number_format($total,2,',','.')?> MT</p><p class="muted">Entrega a combinar. O pagamento não é efectuado automaticamente.</p><?php if ($itens): ?><a class="primary" href="checkout.php">Finalizar encomenda →</a><?php endif;
 ?></aside></div></main><?php include __DIR__.'/public_footer.php';
 ?>
</body></html>

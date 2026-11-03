<?php
/**
 * Módulo admin encomenda detalhes da plataforma TechShop.
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
auth();
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
if (!$id) {http_response_code(400);
exit('Número de encomenda inválido.');
}
// Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
$st=$pdo->prepare('SELECT id,nome_cliente,telefone,endereco,metodo_pagamento,total,estado,criado_em FROM encomendas WHERE id=?');
$st->execute([$id]);
$pedido=$st->fetch();
if (!$pedido) {http_response_code(404);
exit('Encomenda não encontrada.');
}
// Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
$it=$pdo->prepare('SELECT i.produto_id,i.quantidade,i.preco_unitario,p.nome FROM encomenda_itens i LEFT JOIN produtos p ON p.id=i.produto_id WHERE i.encomenda_id=? ORDER BY i.id');
$it->execute([$id]);
$itens=$it->fetchAll();
?><!doctype html><html lang="pt"><head>
<link rel="icon" type="image/x-icon" href="favicon.ico?v=2">
<link rel="icon" type="image/png" sizes="32x32" href="assets/favicon-32.png?v=2">
<link rel="apple-touch-icon" href="assets/techshop-icon-192.png?v=2"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Encomenda #<?=(int)$id?> | TechShop</title><link rel="stylesheet" href="assets/style.css?v=orders2"><link rel="stylesheet" href="assets/admin_encomendas.css?v=2"></head><body><?php include __DIR__.'/header.php';
 ?>
<main class="orders-wrap"><div class="orders-heading"><div><span class="orders-kicker">DETALHES DA COMPRA</span><h1>Encomenda #<?=(int)$id?></h1><p>Registada em <?=h(date('d/m/Y, H:i',strtotime($pedido['criado_em'])))?></p></div><a class="orders-back" href="admin_encomendas.php">← Todas as encomendas</a></div>
<div class="orders-detail-grid"><section class="orders-panel orders-info"><h2>Dados do cliente</h2><dl><dt>Nome</dt><dd><?=h($pedido['nome_cliente'])?></dd><dt>Telefone</dt><dd><?=h($pedido['telefone'])?></dd><dt>Endereço de entrega</dt><dd><?=h($pedido['endereco']?:'Não informado')?></dd></dl></section><section class="orders-panel orders-info"><h2>Pagamento</h2><dl><dt>Método</dt><dd><?=h(strtoupper($pedido['metodo_pagamento']))?></dd><dt>Estado</dt><dd><span class="orders-badge <?=h($pedido['estado'])?>"><?=h(ucfirst($pedido['estado']))?></span></dd><dt>Valor total</dt><dd class="orders-price"><?=number_format((float)$pedido['total'],2,',','.')?> MT</dd></dl><?php if($pedido['estado']==='pendente'):?><p class="orders-footnote">Para confirmar o pagamento ou cancelar, volte à lista de encomendas.</p><?php endif;?></section></div>
<section class="orders-panel"><h2>Produtos da encomenda</h2><div class="orders-table-scroll"><table class="orders-table"><thead><tr><th>Produto</th><th>Quantidade</th><th>Preço unitário</th><th>Subtotal</th></tr></thead><tbody><?php foreach($itens as $item):?><tr><td><?=h($item['nome']??'Produto removido do catálogo')?></td><td><?=(int)$item['quantidade']?></td><td><?=number_format((float)$item['preco_unitario'],2,',','.')?> MT</td><td class="orders-price"><?=number_format((float)$item['preco_unitario']*(int)$item['quantidade'],2,',','.')?> MT</td></tr><?php endforeach;?><?php if(!$itens):?><tr><td colspan="4" class="orders-empty">Sem itens associados a esta encomenda.</td></tr><?php endif;?></tbody></table></div></section>
</main><?php include __DIR__.'/admin_footer.php';
 ?>
</body></html>

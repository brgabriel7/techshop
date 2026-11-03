<?php
/**
 * Módulo minha conta da plataforma TechShop.
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
// Carrega as dependências e funções necessárias para esta página.
require_once __DIR__.'/foto_perfil.php';
cliente_auth();
// Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
$stmt=$pdo->prepare('SELECT id,nome,email,telefone,foto FROM contas_clientes WHERE id=?');
$stmt->execute([cliente_id()]);
$cliente=$stmt->fetch();
if(!$cliente){unset($_SESSION['cliente_id']);
go('cliente_login.php');
}
// Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
$stmt=$pdo->prepare('SELECT id,total,estado,metodo_pagamento,criado_em FROM encomendas WHERE cliente_id=? ORDER BY id DESC');
$stmt->execute([cliente_id()]);
$encomendas=$stmt->fetchAll();
$itens=[];
if($encomendas){$ids=array_column($encomendas,'id');
$sql="SELECT ei.encomenda_id,ei.quantidade,ei.preco_unitario,COALESCE(p.nome,'Produto removido') AS nome FROM encomenda_itens ei LEFT JOIN produtos p ON p.id=ei.produto_id WHERE ei.encomenda_id IN (".implode(',',array_fill(0,count($ids),'?')).")";
// Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
$st=$pdo->prepare($sql);
$st->execute($ids);
// Percorre os registos e aplica o processamento necessário a cada item.
foreach($st as $i)$itens[(int)$i['encomenda_id']][]=$i;
}
?><!doctype html><html lang="pt"><head>
<link rel="icon" type="image/x-icon" href="favicon.ico?v=2">
<link rel="icon" type="image/png" sizes="32x32" href="assets/favicon-32.png?v=2">
<link rel="apple-touch-icon" href="assets/techshop-icon-192.png?v=2"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Minha conta | TechShop</title><link rel="stylesheet" href="assets/loja.css?v=20261103-menu"></head><body><?php include __DIR__.'/public_header.php';
 ?>
<main class="wrap account-main"><div class="account-heading"><div class="account-identity"><div class="profile-avatar"><?php if(url_foto_perfil($cliente['foto'])):?><img src="<?=h(url_foto_perfil($cliente['foto']))?>" alt="Fotografia de perfil"><?php else:?><span><?=h(mb_strtoupper(mb_substr($cliente['nome'],0,1)))?></span><?php endif;?></div><div><span class="eyebrow">ÁREA DO CLIENTE</span><h1>Olá, <?=h($cliente['nome'])?>!</h1><p class="muted"><?=h($cliente['email'])?> · <?=h($cliente['telefone'])?></p><a class="account-link" href="cliente_perfil.php">Editar perfil e fotografia</a></div></div><a class="primary" href="loja.php">Continuar a comprar</a></div>
<div class="account-summary"><section class="cart-panel"><strong><?=count($encomendas)?></strong><p>Encomendas registadas</p></section><section class="cart-panel"><strong><?=array_sum($_SESSION['carrinho']??[])?></strong><p>Produtos guardados no carrinho</p><a class="account-link" href="carrinho.php">Ver carrinho</a></section></div>
<h2>Histórico de compras</h2><?php if(!$encomendas):?><section class="cart-panel"><p>Ainda não tem encomendas associadas a esta conta.</p><a class="account-link" href="loja.php">Explorar produtos</a></section><?php endif;?>
<?php foreach($encomendas as $e):?><section class="cart-panel order-card"><div class="order-head"><div><h3>Encomenda #<?=(int)$e['id']?></h3><p class="muted"><?=h($e['criado_em'])?> · <?=h(strtoupper($e['metodo_pagamento']))?></p></div><div><span class="order-status"><?=h(ucfirst($e['estado']))?></span><strong><?=number_format((float)$e['total'],2,',','.')?> MT</strong></div></div><div class="order-items"><?php foreach($itens[(int)$e['id']]??[] as $i):?><div><?=h($i['nome'])?> <span><?= (int)$i['quantidade']?> × <?=number_format((float)$i['preco_unitario'],2,',','.')?> MT</span></div><?php endforeach;?></div></section><?php endforeach;?></main><?php include __DIR__.'/public_footer.php';
 ?>
</body></html>
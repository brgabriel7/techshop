<?php
/**
 * Vitrine pública e listagem de produtos.
 * Projecto: TechShop — Loja Virtual de Acessórios Informáticos
 * Autor: Bernabé Rofino Gabriel
 * Instituição: Universidade Licungo
 * Curso: Mestrado em Tecnologias Educativas e Inovação
 * Módulo: Aplicações Web
 * Docente: Prof. Félix Singo
 * Ano: 2026
 * Direitos: Todos os direitos reservados.
 */

/** Vitrine pública: não requer login e consulta os produtos reais da base de dados. */
// Carrega as dependências e funções necessárias para esta página.
require_once __DIR__ . '/config.php';
$busca = trim((string)($_GET['q'] ?? ''));
$categoria = trim((string)($_GET['categoria'] ?? ''));
$categorias = $pdo->query("SELECT DISTINCT categoria FROM produtos WHERE categoria <> '' ORDER BY categoria")->fetchAll(PDO::FETCH_COLUMN);
$sql = 'SELECT id, nome, categoria, preco, stock, imagem FROM produtos WHERE 1=1';
$params = [];
if ($busca !== '') { $sql .= ' AND (nome LIKE :busca OR categoria LIKE :busca_categoria)';
 $params[':busca'] = '%'.$busca.'%';
 $params[':busca_categoria'] = '%'.$busca.'%';
 }
if ($categoria !== '') { $sql .= ' AND categoria = :categoria';
 $params[':categoria'] = $categoria;
 }
$sql .= ' ORDER BY id DESC';
// Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
$stmt = $pdo->prepare($sql);
 $stmt->execute($params);
 $produtos = $stmt->fetchAll();
// Destaques dinâmicos: utiliza os produtos existentes, sem nova tabela.
$destaques = $pdo->query('SELECT id, nome, categoria, preco, stock, imagem FROM produtos WHERE stock > 0 ORDER BY id DESC LIMIT 8')->fetchAll();
$slides = array_slice($destaques, 0, 5);
// Número da loja já fornecido; pode alterar aqui quando necessário.
$whatsapp = '258872872515';
function foto_produto($imagem) {
    // Apenas nomes de ficheiros locais são aceites (evita caminhos externos).
    $nome = basename((string)$imagem);
    if ($nome !== '' && is_file(__DIR__.'/uploads/produtos/'.$nome)) return 'uploads/produtos/'.rawurlencode($nome);
    return 'assets/img/produto.svg';
}
?>
<!doctype html><html lang="pt"><head>
<link rel="icon" type="image/x-icon" href="favicon.ico?v=2">
<link rel="icon" type="image/png" sizes="32x32" href="assets/favicon-32.png?v=2">
<link rel="apple-touch-icon" href="assets/techshop-icon-192.png?v=2"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Loja Virtual | TechShop</title><link rel="stylesheet" href="assets/loja.css?v=20261104-mobile"></head><body>
<?php include __DIR__.'/public_header.php';
 ?>
<main class="wrap">
<?php if ($slides): ?>
<section class="hero hero-slider" data-hero-slider aria-label="Destaques da TechShop">
  <div class="hero-slides">
    <?php foreach ($slides as $i => $slide): ?>
    <article class="hero-slide <?= $i === 0 ? 'is-active' : '' ?>" aria-hidden="<?= $i === 0 ? 'false' : 'true' ?>">
      <div class="hero-text"><span class="eyebrow">PRODUTOS EM DESTAQUE</span>
        <h1><?=h($slide['nome'])?></h1>
        <p><?=h($slide['categoria'])?> · Disponível em stock</p>
        <strong class="hero-price"><?=number_format((float)$slide['preco'],2,',','.')?> MT</strong>
        <a class="primary" href="#produtos">Ver produtos →</a>
      </div>
      <div class="hero-slide-photo"><img src="<?=h(foto_produto($slide['imagem']))?>" alt="<?=h($slide['nome'])?>"></div>
    </article>
    <?php endforeach;
 ?>
  </div>
  <?php if (count($slides) > 1): ?>
  <div class="hero-controls"><button type="button" data-hero-prev aria-label="Slide anterior">‹</button>
    <div class="hero-dots" aria-label="Seleccionar slide"><?php foreach ($slides as $i => $slide): ?><button type="button" data-hero-dot="<?=$i?>" class="<?=$i===0?'is-active':''?>" aria-label="Mostrar slide <?=$i+1?>" aria-current="<?=$i===0?'true':'false'?>"></button><?php endforeach;
 ?></div>
    <button type="button" data-hero-next aria-label="Próximo slide">›</button>
    <button type="button" data-hero-pause aria-label="Pausar slides">Pausar Ⅱ</button>
  </div>
  <?php endif;
 ?>
</section>
<?php else: ?>
<section class="hero"><div class="hero-text"><span class="eyebrow">A SUA LOJA DE TECNOLOGIA</span><h1>Tecnologia para o seu dia a dia.</h1><p>Encontre acessórios informáticos com preços claros e disponibilidade actualizada.</p><a class="primary" href="#produtos">Explorar produtos →</a></div><div class="hero-graphic" aria-hidden="true"><div class="screen"><div class="screen-inner">TECH<span>SHOP</span></div></div><div class="keyboard"></div></div></section>
<?php endif;
 ?>
<?php if ($destaques): ?>
<section class="showcase" aria-label="Produtos em destaque" data-showcase>
  <div class="showcase-head"><div><span class="eyebrow">NOVIDADES DA LOJA</span><h2>Produtos em destaque</h2><p>Conheça os nossos acessórios. Escolha um produto e adicione ao carrinho.</p></div><div class="showcase-actions"><button type="button" class="showcase-arrow" data-prev aria-label="Produto anterior">‹</button><button type="button" class="showcase-arrow" data-next aria-label="Próximo produto">›</button></div></div>
  <div class="showcase-window" tabindex="0" aria-label="Lista horizontal de produtos em destaque">
    <div class="showcase-track">
    <?php foreach ($destaques as $destaque): ?>
      <article class="showcase-card"><div class="showcase-photo"><img src="<?=h(foto_produto($destaque['imagem']))?>" alt="<?=h($destaque['nome'])?>" loading="lazy"></div><div class="showcase-info"><span class="category"><?=h($destaque['categoria'])?></span><h3><?=h($destaque['nome'])?></h3><strong><?=number_format((float)$destaque['preco'],2,',','.')?> MT</strong><span class="showcase-stock">Disponível · <?= (int)$destaque['stock'] ?> em stock</span><form action="carrinho.php" method="post"><input type="hidden" name="csrf" value="<?=h(token())?>"><input type="hidden" name="acao" value="adicionar"><input type="hidden" name="id" value="<?=(int)$destaque['id']?>"><button class="showcase-buy" type="submit">Adicionar ao carrinho</button></form></div></article>
    <?php endforeach;
 ?>
    </div>
  </div>
  <div class="showcase-foot"><span>Deslize para descobrir mais produtos</span><button type="button" data-toggle aria-label="Pausar animação" aria-pressed="false">Pausar animação <span aria-hidden="true">Ⅱ</span></button></div>
</section>
<?php endif;
 ?>

<section id="produtos" class="catalog"><div class="heading"><div><span class="eyebrow">CATÁLOGO</span><h2>Os nossos produtos</h2><p><?=count($produtos)?> produto(s) encontrado(s)</p></div></div>
<form class="filters" method="get" action="loja.php"><label class="search"><span class="sr">Pesquisar</span><input type="search" name="q" placeholder="Pesquisar produto ou categoria..." value="<?=h($busca)?>"></label><label class="sr" for="categoria">Categoria</label><select name="categoria" id="categoria"><option value="">Todas as categorias</option><?php foreach($categorias as $cat): ?><option value="<?=h($cat)?>" <?=$categoria===$cat?'selected':''?>><?=h($cat)?></option><?php endforeach;
 ?></select><button type="submit" class="primary">Pesquisar</button><a class="clear" href="loja.php">Limpar</a></form>
<?php if (!$produtos): ?><div class="empty">Não foram encontrados produtos. <a href="loja.php">Ver todos</a></div><?php else: ?><div class="grid">
<?php foreach ($produtos as $produto): $stock=(int)$produto['stock'];
 $msg='Olá! Gostaria de informações sobre o produto '.$produto['nome'].' (referência #'.$produto['id'].').';
 $url='https://wa.me/'.$whatsapp.'?text='.rawurlencode($msg);
 ?>
<article class="product"><div class="photo"><img src="<?=h(foto_produto($produto['imagem']))?>" alt="<?=h($produto['nome'])?>" loading="lazy"><span class="stock <?=$stock===0?'out':($stock<=5?'low':'')?>"><?=$stock===0?'Esgotado':$stock.' em stock'?></span></div><div class="details"><span class="category"><?=h($produto['categoria'])?></span><h3><?=h($produto['nome'])?></h3><strong class="price"><?=number_format((float)$produto['preco'],2,',','.')?> MT</strong><?php if ($stock>0): ?><form method="post" action="carrinho.php"><input type="hidden" name="csrf" value="<?=h(token())?>"><input type="hidden" name="acao" value="adicionar"><input type="hidden" name="id" value="<?= (int)$produto['id'] ?>"><button class="contact cart-add" type="submit">🛒 Adicionar ao carrinho</button></form><a class="contact" href="<?=h($url)?>" target="_blank" rel="noopener noreferrer">Consultar no WhatsApp ↗</a><?php else: ?><span class="unavailable">Temporariamente indisponível</span><?php endif;
 ?></div></article>
<?php endforeach;
 ?></div><?php endif;
 ?>

<section class="features" aria-label="Vantagens"><span>✓ Stock actualizado</span><span>✓ Preços em meticais</span><span>✓ Atendimento por WhatsApp</span></section>

</section>

 
</main><?php include __DIR__ . '/voltar_topo.php';
 ?><script src="assets/banner.js" defer></script><script src="assets/slides.js" defer></script><?php include __DIR__.'/public_footer.php';
 ?>
</body></html>

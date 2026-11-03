<?php
/**
 * Painel de controlo administrativo.
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
// Carrega as dependências e funções necessárias para esta página.
require_once __DIR__.'/admin_foto.php';
function painel_num(PDO $pdo, string $sql): int { try { return (int)$pdo->query($sql)->fetchColumn();
 } catch(PDOException $e) { error_log($e->getMessage());
 return 0;
 } }
$total=painel_num($pdo,'SELECT COUNT(*) FROM produtos');
$unidades=painel_num($pdo,'SELECT COALESCE(SUM(stock),0) FROM produtos');
$baixos=painel_num($pdo,'SELECT COUNT(*) FROM produtos WHERE stock < 5');
// Contas da loja e registos manuais são fontes distintas; não somar clientes repetidos.
$contas=painel_num($pdo,'SELECT COUNT(*) FROM contas_clientes');
$manuais=painel_num($pdo,'SELECT COUNT(*) FROM clientes');
$encomendas=painel_num($pdo,'SELECT COUNT(*) FROM encomendas');
$pendentes=painel_num($pdo,"SELECT COUNT(*) FROM encomendas WHERE estado='pendente'");
$nomeAdmin=(string)($_SESSION['nome'] ?? 'Administrador');
$fotoAdmin=null;
try { $st=$pdo->prepare('SELECT nome,foto FROM utilizadores WHERE id=? LIMIT 1');
 $st->execute([(int)$_SESSION['user_id']]);
 if($d=$st->fetch()){$nomeAdmin=(string)$d['nome'];
$fotoAdmin=admin_foto_url($d['foto'] ?? null);
} }catch(PDOException $e){error_log($e->getMessage());
}
$hora=(int)date('G');
$saudacao=$hora<12?'Bom dia':($hora<19?'Boa tarde':'Boa noite');
?><!doctype html><html lang="pt"><head>
<link rel="icon" type="image/x-icon" href="favicon.ico?v=2">
<link rel="icon" type="image/png" sizes="32x32" href="assets/favicon-32.png?v=2">
<link rel="apple-touch-icon" href="assets/techshop-icon-192.png?v=2"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Painel de gestão | TechShop</title><link rel="stylesheet" href="assets/style.css?v=painel-20261103"><link rel="stylesheet" href="assets/painel_moderno.css?v=1"></head><body>
<?php include __DIR__.'/header.php';
 ?>
<main class="wrap painel-v3">
 <section class="pv-welcome">
  <div class="pv-intro"><div class="pv-avatar"><?php if($fotoAdmin):?><img src="<?=h($fotoAdmin)?>" alt="Fotografia de <?=h($nomeAdmin)?>"><?php else:?><span><?=h(mb_strtoupper(mb_substr($nomeAdmin,0,1)))?></span><?php endif;?></div><div><div class="pv-eyebrow">PAINEL ADMINISTRATIVO</div><h1><?=h($saudacao)?>, <?=h($nomeAdmin)?>!</h1><p>Bem-vindo de volta. Aqui está o resumo da sua loja.</p></div></div>
  <a class="pv-profile-link" href="admin_perfil.php">Editar perfil <span aria-hidden="true">↗</span></a>
 </section>
 <div class="pv-section-title"><div><h2>Visão geral</h2><p>Acompanhe os principais números do seu negócio.</p></div><span class="pv-today"><?=h(date('d/m/Y'))?></span></div>
 <section class="pv-stats" aria-label="Indicadores da loja">
  <a class="pv-stat pv-blue" href="lista_produtos.php"><span class="pv-stat-top"><span>Produtos registados</span><span class="pv-stat-icon"><svg class="ts-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m12 2 9 5-9 5-9-5 9-5z"/><path d="M3 7v10l9 5 9-5V7M12 12v10"/></svg></span></span><strong><?=number_format($total,0,',','.')?></strong><small>Ver inventário ↗</small></a>
  <a class="pv-stat pv-green" href="lista_produtos.php"><span class="pv-stat-top"><span>Unidades em stock</span><span class="pv-stat-icon"><svg class="ts-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg></span></span><strong><?=number_format($unidades,0,',','.')?></strong><small>Disponíveis na loja ↗</small></a>
  <a class="pv-stat pv-orange" href="lista_produtos.php"><span class="pv-stat-top"><span>Stock baixo</span><span class="pv-stat-icon"><svg class="ts-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m12 3 10 18H2L12 3z"/><path d="M12 9v4M12 17h.01"/></svg></span></span><strong><?=number_format($baixos,0,',','.')?></strong><small>Produtos com menos de 5 unidades ↗</small></a>
  <a class="pv-stat pv-purple" href="lista_clientes.php"><span class="pv-stat-top"><span>Contas de clientes</span><span class="pv-stat-icon"><svg class="ts-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg></span></span><strong><?=number_format($contas,0,',','.')?></strong><small><?=number_format($manuais,0,',','.')?> registos administrativos · Ver clientes ↗</small></a>
  <a class="pv-stat pv-cyan" href="admin_encomendas.php"><span class="pv-stat-top"><span>Encomendas</span><span class="pv-stat-icon"><svg class="ts-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="5" y="3" width="14" height="18" rx="2"/><path d="M9 3.5h6M9 9h6M9 13h6M9 17h4"/></svg></span></span><strong><?=number_format($encomendas,0,',','.')?></strong><small>Consultar encomendas ↗</small></a>
  <a class="pv-stat pv-red" href="admin_encomendas.php"><span class="pv-stat-top"><span>Aguardam confirmação</span><span class="pv-stat-icon"><svg class="ts-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></span></span><strong><?=number_format($pendentes,0,',','.')?></strong><small>Pagamentos pendentes ↗</small></a>
 </section>
 <section class="pv-bottom"><div class="pv-panel"><div class="pv-panel-heading"><div><h2>Acesso rápido</h2><p>As tarefas mais frequentes, num só lugar.</p></div></div><div class="pv-actions"><a href="inserir_produtos.php"><span class="pv-action-icon"><svg class="ts-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg></span><span><strong>Adicionar produto</strong><small>Registar novo artigo</small></span><span class="pv-arrow">›</span></a><a href="lista_produtos.php"><span class="pv-action-icon"><svg class="ts-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg></span><span><strong>Gerir produtos</strong><small>Inventário e preços</small></span><span class="pv-arrow">›</span></a><a href="lista_clientes.php"><span class="pv-action-icon"><svg class="ts-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg></span><span><strong>Gerir clientes</strong><small>Contas e registos</small></span><span class="pv-arrow">›</span></a><a href="admin_encomendas.php"><span class="pv-action-icon"><svg class="ts-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="5" y="3" width="14" height="18" rx="2"/><path d="M9 3.5h6M9 9h6M9 13h6M9 17h4"/></svg></span><span><strong>Gerir encomendas</strong><small>Compras e pagamentos</small></span><span class="pv-arrow">›</span></a></div></div>
 <aside class="pv-panel pv-side"><h2>A sua loja</h2><p>Consulte a experiência de compra como os seus clientes.</p><div class="pv-side-art" aria-hidden="true"><svg class="ts-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="7" width="16" height="14" rx="2"/><path d="M8 9V6a4 4 0 0 1 8 0v3"/></svg></div><a class="pv-store-button" href="loja.php" target="_blank" rel="noopener">Abrir loja pública ↗</a><a class="pv-secondary-link" href="lista_utilizadores.php">Gerir utilizadores →</a></aside></section>
</main>
<?php include __DIR__.'/admin_footer.php';
 ?>
</body></html>

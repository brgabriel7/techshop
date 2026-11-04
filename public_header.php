<?php
/**
 * Cabeçalho e navegação da área pública.
 * Projecto: TechShop — Loja Virtual de Acessórios Informáticos
 * Autor: Bernabé Rofino Gabriel
 * Instituição: Universidade Licungo
 * Curso: Mestrado em Tecnologias Educativas e Inovação
 * Módulo: Aplicações Web
 * Docente: Prof. Félix Singo
 * Ano: 2026
 * Direitos: Todos os direitos reservados.
 */

// Menu público partilhado. Mostra o perfil apenas ao cliente autenticado.
// Carrega as dependências e funções necessárias para esta página.
require_once __DIR__.'/config.php';
$clienteMenu = null;
if (cliente_id()) {
    try {
        // Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
        $qMenu = $pdo->prepare('SELECT nome, foto FROM contas_clientes WHERE id=? LIMIT 1');
        $qMenu->execute([cliente_id()]);
        $clienteMenu = $qMenu->fetch() ?: null;
    } catch (PDOException $e) {
        // Mantém o menu funcional mesmo se a migração da fotografia ainda faltar.
        error_log($e->getMessage());
        try {
            // Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
            $qMenu = $pdo->prepare('SELECT nome FROM contas_clientes WHERE id=? LIMIT 1');
            $qMenu->execute([cliente_id()]);
            $clienteMenu = $qMenu->fetch() ?: null;
        } catch (PDOException $ignored) { error_log($ignored->getMessage());
 }
    }
}
$nomeMenu = (string)($clienteMenu['nome'] ?? ($_SESSION['cliente_nome'] ?? 'Cliente'));
$fotoMenu = null;
$arquivoMenu = (string)($clienteMenu['foto'] ?? '');
if ($arquivoMenu && preg_match('/^[a-f0-9]{32}\\.(?:jpg|png|webp)$/D', $arquivoMenu)
    && is_file(__DIR__.'/uploads/perfis/'.$arquivoMenu)) {
    $fotoMenu = 'uploads/perfis/'.rawurlencode($arquivoMenu);
}
$quantidadeMenu = array_sum(array_map('intval', $_SESSION['carrinho'] ?? []));
// Categorias existentes na base de dados: o menu actualiza-se automaticamente.
$categoriasMenu = [];
try {
    $categoriasMenu = $pdo->query(
        "SELECT DISTINCT categoria FROM produtos
         WHERE categoria IS NOT NULL AND TRIM(categoria) <> ''
         ORDER BY categoria"
    )->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    error_log('TechShop - categorias do menu: '.$e->getMessage());
}

?>
<header class="top public-top">
 <div class="wrap public-nav">
  <a class="brand" href="loja.php" aria-label="TechShop, página inicial"><span class="brand-mark"><img class="ts-brand-logo" src="assets/techshop-logo.png" alt="" width="48" height="48"></span><span><strong>TechShop</strong><small>Acessórios informáticos</small></span></a>
  <!-- Navegação responsiva: botão acessível para ecrãs pequenos. -->
  <button class="public-mobile-toggle" type="button" aria-controls="techshop-mobile-navigation" aria-expanded="false" aria-label="Abrir menu">
    <span></span><span></span><span></span>
  </button>
  <nav id="techshop-mobile-navigation" class="public-nav-links" aria-label="Menu da loja">
   <a href="loja.php">Início</a>
   <div class="public-products-menu">
    <a class="public-products-link" href="loja.php#produtos">Produtos</a>
    <button class="public-products-toggle" type="button" aria-expanded="false"
            aria-controls="public-products-dropdown" aria-label="Mostrar categorias de produtos">
      <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
    </button>
    <div id="public-products-dropdown" class="public-products-dropdown">
      <span class="public-products-heading">EXPLORAR CATEGORIAS</span>
      <a href="loja.php#produtos" class="public-category-all">Todos os produtos</a>
      <?php foreach ($categoriasMenu as $categoriaMenu): ?>
        <a href="loja.php?categoria=<?=rawurlencode((string)$categoriaMenu)?>#produtos"
           <?= isset($_GET['categoria']) && (string)$_GET['categoria'] === (string)$categoriaMenu ? 'aria-current="page"' : '' ?>>
          <span class="public-category-bullet" aria-hidden="true"></span><?=h($categoriaMenu)?>
        </a>
      <?php endforeach;
 ?>
      <?php if (!$categoriasMenu): ?><span class="public-categories-empty">Ainda não existem categorias.</span><?php endif;
 ?>
    </div>
   </div>
   <a href="carrinho.php">🛒 Carrinho (<?= (int)$quantidadeMenu ?>)</a>
   <?php if ($clienteMenu): ?>
    <a class="public-profile" href="minha_conta.php" title="Abrir a minha conta">
     <span class="public-profile-avatar">
      <?php if ($fotoMenu): ?><img src="<?=h($fotoMenu)?>" alt="Fotografia de <?=h($nomeMenu)?>">
      <?php else: ?><span><?=h(mb_strtoupper(mb_substr($nomeMenu,0,1)))?></span><?php endif;
 ?>
     </span>
     <span class="public-profile-label"><strong><?=h($nomeMenu)?></strong><small>Minha conta</small></span>
    </a>
    <a href="cliente_sair.php">Sair</a>
   <?php else: ?>
    <a class="public-login" href="cliente_login.php">Entrar / Criar conta</a>
   <?php endif;
 ?>
   <a class="public-admin-link" href="login.php">Administração ↗</a>
  </nav>
 </div>
</header>

<script>
(function(){
  // Controla a abertura e o fecho do menu móvel.
  const mobileToggle = document.querySelector('.public-mobile-toggle');
  const mobileNav = document.getElementById('techshop-mobile-navigation');
  const closeMobile = () => {
    mobileNav?.classList.remove('is-mobile-open');
    mobileToggle?.setAttribute('aria-expanded', 'false');
  };
  mobileToggle?.addEventListener('click', () => {
    const opened = mobileNav.classList.toggle('is-mobile-open');
    mobileToggle.setAttribute('aria-expanded', String(opened));
  });
  mobileNav?.querySelectorAll('a').forEach(a => a.addEventListener('click', closeMobile));
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeMobile(); });
  const menu = document.querySelector('.public-products-menu');
  if (!menu) return;
  const toggle = menu.querySelector('.public-products-toggle');
  const setOpen = (open) => {
    menu.classList.toggle('is-open', open);
    toggle.setAttribute('aria-expanded', String(open));
  };
  toggle.addEventListener('click', () => setOpen(!menu.classList.contains('is-open')));
  document.addEventListener('click', (e) => { if (!menu.contains(e.target)) setOpen(false); });
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') { setOpen(false); toggle.focus(); } });
})();
</script>

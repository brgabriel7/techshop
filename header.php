<?php
/**
 * Cabeçalho e navegação do painel administrativo.
 * Projecto: TechShop — Loja Virtual de Acessórios Informáticos
 * Autor: Bernabé Rofino Gabriel
 * Instituição: Universidade Licungo
 * Curso: Mestrado em Tecnologias Educativas e Inovação
 * Módulo: Aplicações Web
 * Docente: Prof. Félix Singo
 * Ano: 2026
 * Direitos: Todos os direitos reservados.
 */

// Cabeçalho administrativo partilhado por todas as páginas privadas.
// Carrega as dependências e funções necessárias para esta página.
require_once __DIR__.'/config.php';
// Carrega as dependências e funções necessárias para esta página.
require_once __DIR__.'/admin_foto.php';
$nomeCabecalho = (string)($_SESSION['nome'] ?? 'Administrador');
$fotoCabecalho = null;
if (!empty($_SESSION['user_id'])) {
    try {
        // Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
        $stmtCabecalho = $pdo->prepare('SELECT nome, foto FROM utilizadores WHERE id=? LIMIT 1');
        $stmtCabecalho->execute([(int)$_SESSION['user_id']]);
        $dadosCabecalho = $stmtCabecalho->fetch();
        if ($dadosCabecalho) {
            $nomeCabecalho = $dadosCabecalho['nome'];
            $fotoCabecalho = admin_foto_url($dadosCabecalho['foto']);
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
    }
}
?>
<?php
$paginaMenu = basename($_SERVER['SCRIPT_NAME'] ?? '');
function menu_actual(string $pagina, array $paginas): string { return in_array($pagina,$paginas,true) ? ' is-current' : '';
 }
?>
<header class="top admin-top admin-top-v2">
  <div class="admin-bar-main">
    <a class="brand admin-brand-v2" href="painel.php" aria-label="TechShop, painel administrativo">
      <span class="admin-logo-icon" aria-hidden="true"><img class="ts-admin-logo" src="assets/techshop-logo.png" alt="" width="38" height="38"></span>
      <span class="admin-brand-words"><strong>TechShop</strong><small>Painel administrativo</small></span>
    </a>
    <button class="admin-mobile-toggle" type="button" aria-controls="admin-main-nav" aria-expanded="false" aria-label="Abrir menu">☰ <span>Menu</span></button>
    <nav id="admin-main-nav" class="admin-navigation admin-menu-v2" aria-label="Navegação administrativa">
      <a class="admin-nav-link<?=menu_actual($paginaMenu,['painel.php'])?>" href="painel.php"><span class="admin-nav-icon" aria-hidden="true"><svg class="ts-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1z"/></svg></span> Início</a>
      <details class="admin-menu-group<?=menu_actual($paginaMenu,['lista_produtos.php','inserir_produtos.php','editar_produto.php','categorias_produtos.php'])?>">
        <summary><span class="admin-nav-icon" aria-hidden="true"><svg class="ts-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m12 2 9 5-9 5-9-5 9-5z"/><path d="M3 7v10l9 5 9-5V7M12 12v10"/></svg></span> Produtos <span class="admin-chevron" aria-hidden="true"><svg class="admin-chevron-svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg></span></summary>
        <div class="admin-menu-panel"><span class="admin-menu-caption">GESTÃO DE PRODUTOS</span><a href="lista_produtos.php">Listar produtos <small>Inventário e stock</small></a><a href="inserir_produtos.php">Adicionar produto <small>Novo artigo na loja</small></a><a href="categorias_produtos.php">Gerir categorias <small>Categorias pré-cadastradas</small></a></div>
      </details>
      <details class="admin-menu-group<?=menu_actual($paginaMenu,['lista_clientes.php','cadastrar_cliente.php','editar_cliente.php'])?>">
        <summary><span class="admin-nav-icon" aria-hidden="true"><svg class="ts-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg></span> Clientes <span class="admin-chevron" aria-hidden="true"><svg class="admin-chevron-svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg></span></summary>
        <div class="admin-menu-panel"><span class="admin-menu-caption">GESTÃO DE CLIENTES</span><a href="lista_clientes.php">Listar clientes <small>Cadastros e compras</small></a><a href="cadastrar_cliente.php">Novo cliente <small>Registo administrativo</small></a></div>
      </details>
      <a class="admin-nav-link<?=menu_actual($paginaMenu,['admin_encomendas.php'])?>" href="admin_encomendas.php"><span class="admin-nav-icon" aria-hidden="true"><svg class="ts-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="5" y="3" width="14" height="18" rx="2"/><path d="M9 3.5h6M9 9h6M9 13h6M9 17h4"/></svg></span> Encomendas</a>
      <details class="admin-menu-group<?=menu_actual($paginaMenu,['lista_utilizadores.php','registar.php','editar_utilizador.php'])?>">
        <summary><span class="admin-nav-icon" aria-hidden="true"><svg class="ts-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.88l.06.06-2.8 2.8-.06-.06a1.7 1.7 0 0 0-1.88-.34 1.7 1.7 0 0 0-1 1.55V21h-4v-.11a1.7 1.7 0 0 0-1-1.55 1.7 1.7 0 0 0-1.88.34l-.06.06-2.8-2.8.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-1.55-1H3v-4h.05A1.7 1.7 0 0 0 4.6 9a1.7 1.7 0 0 0-.34-1.88L4.2 7.06l2.8-2.8.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-1.55V3h4v.05a1.7 1.7 0 0 0 1 1.55 1.7 1.7 0 0 0 1.88-.34l.06-.06 2.8 2.8-.06.06A1.7 1.7 0 0 0 19.4 9a1.7 1.7 0 0 0 1.55 1H21v4h-.05A1.7 1.7 0 0 0 19.4 15z"/></svg></span> Utilizadores <span class="admin-chevron" aria-hidden="true"><svg class="admin-chevron-svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg></span></summary>
        <div class="admin-menu-panel"><span class="admin-menu-caption">GESTÃO DE ACESSOS</span><a href="lista_utilizadores.php">Utilizadores <small>Gerir acessos</small></a><a href="registar.php">Registar utilizador <small>Criar acesso</small></a></div>
      </details>
      <a class="admin-nav-link admin-store-link" href="loja.php" target="_blank" rel="noopener"><span class="admin-nav-icon" aria-hidden="true"><svg class="ts-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M13 5h6v6M19 5l-9 9"/><path d="M19 13v5a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h5"/></svg></span> Loja pública</a>
    </nav>
    <div class="admin-header-account admin-account-v2">
      <details class="admin-menu-group admin-user-group">
        <summary class="admin-user-trigger" aria-label="Opções da conta de <?=h($nomeCabecalho)?>">
          <span class="admin-header-avatar">
            <?php if ($fotoCabecalho): ?><img src="<?=h($fotoCabecalho)?>" alt="Fotografia de <?=h($nomeCabecalho)?>">
            <?php else: ?><span><?=h(mb_strtoupper(mb_substr($nomeCabecalho,0,1)))?></span><?php endif;
 ?>
          </span>
          <span class="admin-header-identity"><strong><?=h($nomeCabecalho)?></strong><small>Administrador</small></span>
          <span class="admin-chevron" aria-hidden="true"><svg class="admin-chevron-svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg></span>
        </summary>
        <div class="admin-menu-panel admin-user-panel">
          <span class="admin-menu-caption">A MINHA CONTA</span>
          <a href="admin_perfil.php">O meu perfil <small>Fotografia e dados pessoais</small></a>
          <a href="logout.php" class="admin-logout-item">Terminar sessão <small>Sair em segurança</small></a>
        </div>
      </details>
    </div>
  </div>
</header>
<script src="assets/admin_menu.js?v=4" defer></script>

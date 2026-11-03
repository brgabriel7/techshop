/*
 * Módulo admin menu da plataforma TechShop.
 * Projecto: TechShop — Loja Virtual de Acessórios Informáticos
 * Autor: Bernabé Rofino Gabriel
 * Instituição: Universidade Licungo
 * Curso: Mestrado em Tecnologias Educativas e Inovação
 * Módulo: Aplicações Web
 * Docente: Prof. Félix Singo
 * Ano: 2026
 * Direitos: Todos os direitos reservados.
 */

// Navegação progressiva: o menu continua funcional sem JavaScript.
(() => {
  const root = document.querySelector('.admin-top-v2');
  if (!root) return;
  const groups = [...root.querySelectorAll('details.admin-menu-group')];
  const toggle = root.querySelector('.admin-mobile-toggle');
  const nav = root.querySelector('#admin-main-nav');
  groups.forEach(group => group.addEventListener('toggle', () => {
    if (group.open) groups.forEach(other => { if (other !== group) other.open = false; });
  }));
  // Regista o evento para executar a lógica no momento adequado.
  document.addEventListener('pointerdown', event => {
    if (!root.contains(event.target)) groups.forEach(group => { group.open = false; });
    else groups.forEach(group => { if (!group.contains(event.target)) group.open = false; });
  });
  // Regista o evento para executar a lógica no momento adequado.
  document.addEventListener('keydown', event => {
    if (event.key === 'Escape') {
      groups.forEach(group => { group.open = false; });
      if (nav && nav.classList.contains('is-open')) {
        nav.classList.remove('is-open');
        toggle?.setAttribute('aria-expanded', 'false');
        toggle?.focus();
      }
    }
  });
  toggle?.addEventListener('click', () => {
    const open = nav.classList.toggle('is-open');
    toggle.setAttribute('aria-expanded', String(open));
    toggle.setAttribute('aria-label', open ? 'Fechar menu' : 'Abrir menu');
  });
})();

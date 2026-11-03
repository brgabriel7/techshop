/*
 * Módulo banner da plataforma TechShop.
 * Projecto: TechShop — Loja Virtual de Acessórios Informáticos
 * Autor: Bernabé Rofino Gabriel
 * Instituição: Universidade Licungo
 * Curso: Mestrado em Tecnologias Educativas e Inovação
 * Módulo: Aplicações Web
 * Docente: Prof. Félix Singo
 * Ano: 2026
 * Direitos: Todos os direitos reservados.
 */

// Banner animado, sem bibliotecas externas. Respeita a preferência por movimento reduzido.
document.querySelectorAll('[data-showcase]').forEach((banner) => {
  const viewport = banner.querySelector('.showcase-window');
  const track = banner.querySelector('.showcase-track');
  const cards = [...track.querySelectorAll('.showcase-card')];
  const toggle = banner.querySelector('[data-toggle]');
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)');
  let paused = reduced.matches || cards.length < 2;
  let interval;
  const step = () => cards[0]?.getBoundingClientRect().width + 14 || 220;
  const next = () => {
    const end = viewport.scrollWidth - viewport.clientWidth;
    if (end <= 2) return;
    viewport.scrollTo({left: viewport.scrollLeft >= end - 5 ? 0 : Math.min(end, viewport.scrollLeft + step()), behavior: reduced.matches ? 'auto' : 'smooth'});
  };
  const prev = () => viewport.scrollBy({left: -step(), behavior: reduced.matches ? 'auto' : 'smooth'});
  const stop = () => { clearInterval(interval); interval = undefined; };
  const start = () => { stop(); if (!paused && !document.hidden) interval = setInterval(next, 3800); };
  const refresh = () => { toggle.innerHTML = paused ? 'Retomar animação <span aria-hidden="true">▶</span>' : 'Pausar animação <span aria-hidden="true">Ⅱ</span>'; toggle.setAttribute('aria-pressed', String(paused)); };
  banner.querySelector('[data-next]').addEventListener('click', () => { next(); start(); });
  banner.querySelector('[data-prev]').addEventListener('click', () => { prev(); start(); });
  toggle.addEventListener('click', () => { paused = !paused; refresh(); start(); });
  banner.addEventListener('mouseenter', stop);
  banner.addEventListener('mouseleave', start);
  banner.addEventListener('focusin', stop);
  banner.addEventListener('focusout', (event) => { if (!banner.contains(event.relatedTarget)) start(); });
  // Regista o evento para executar a lógica no momento adequado.
  document.addEventListener('visibilitychange', start);
  reduced.addEventListener('change', (event) => { paused = event.matches; refresh(); start(); });
  refresh(); start();
});

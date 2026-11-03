/* Progresso da rolagem e botão voltar ao topo — TechShop. */
(function () {
  'use strict';
  const button = document.getElementById('ts-back-to-top');
  if (!button) return;
  const ring = button.querySelector('.ts-scroll-progress');
  const label = button.querySelector('.ts-scroll-percent');
  const circumference = 2 * Math.PI * 20;
  let queued = false;
  function update() {
    const doc = document.documentElement;
    const maxScroll = Math.max(0, doc.scrollHeight - doc.clientHeight);
    const scroll = Math.max(0, window.scrollY || doc.scrollTop || 0);
    const percent = maxScroll > 0 ? Math.min(100, Math.max(0, Math.round(scroll / maxScroll * 100))) : 0;
    ring.style.strokeDasharray = String(circumference);
    ring.style.strokeDashoffset = String(circumference * (1 - percent / 100));
    label.textContent = percent + '%';
    button.classList.toggle('is-visible', scroll > 160 && maxScroll > 0);
    button.setAttribute('aria-label', 'Voltar ao topo da página. Rolagem: ' + percent + '%');
    queued = false;
  }
  function scheduleUpdate() {
    if (!queued) { queued = true; window.requestAnimationFrame(update); }
  }
  button.addEventListener('click', function () {
    window.scrollTo({ top: 0, behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'instant' : 'smooth' });
  });
  window.addEventListener('scroll', scheduleUpdate, { passive: true });
  window.addEventListener('resize', scheduleUpdate);
  window.addEventListener('load', scheduleUpdate);
  update();
})();

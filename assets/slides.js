/*
 * Módulo slides da plataforma TechShop.
 * Projecto: TechShop — Loja Virtual de Acessórios Informáticos
 * Autor: Bernabé Rofino Gabriel
 * Instituição: Universidade Licungo
 * Curso: Mestrado em Tecnologias Educativas e Inovação
 * Módulo: Aplicações Web
 * Docente: Prof. Félix Singo
 * Ano: 2026
 * Direitos: Todos os direitos reservados.
 */

// Slides da página pública. Sem dependências externas.
document.querySelectorAll('[data-hero-slider]').forEach(slider => {
 const slides=[...slider.querySelectorAll('.hero-slide')], dots=[...slider.querySelectorAll('[data-hero-dot]')];
 if(slides.length<2) return;
 const pauseButton=slider.querySelector('[data-hero-pause]');
 const reduced=window.matchMedia('(prefers-reduced-motion: reduce)');
 let current=0, paused=reduced.matches, timer;
 function show(n){current=(n+slides.length)%slides.length;slides.forEach((slide,i)=>{const active=i===current;slide.classList.toggle('is-active',active);slide.setAttribute('aria-hidden',String(!active));slide.querySelectorAll('a,button').forEach(el=>{el.tabIndex=active?0:-1;});});dots.forEach((dot,i)=>{dot.classList.toggle('is-active',i===current);dot.setAttribute('aria-current',String(i===current));});}
 function stop(){clearInterval(timer);timer=null;}
 function start(){stop();if(!paused&&!document.hidden)timer=setInterval(()=>show(current+1),5000);}
 slider.querySelector('[data-hero-prev]').addEventListener('click',()=>{show(current-1);start();});
 slider.querySelector('[data-hero-next]').addEventListener('click',()=>{show(current+1);start();});
 dots.forEach((dot,i)=>dot.addEventListener('click',()=>{show(i);start();}));
 pauseButton.addEventListener('click',()=>{paused=!paused;pauseButton.textContent=paused?'Retomar ▶':'Pausar Ⅱ';pauseButton.setAttribute('aria-label',paused?'Retomar slides':'Pausar slides');start();});
 slider.addEventListener('mouseenter',stop);slider.addEventListener('mouseleave',start);
 slider.addEventListener('focusin',stop);slider.addEventListener('focusout',e=>{if(!slider.contains(e.relatedTarget))start();});
 // Regista o evento para executar a lógica no momento adequado.
 document.addEventListener('visibilitychange',start);
 reduced.addEventListener('change',e=>{paused=e.matches;pauseButton.textContent=paused?'Retomar ▶':'Pausar Ⅱ';start();});
 show(0);start();
});

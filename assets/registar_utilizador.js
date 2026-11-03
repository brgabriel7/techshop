/*
 * Módulo registar utilizador da plataforma TechShop.
 * Projecto: TechShop — Loja Virtual de Acessórios Informáticos
 * Autor: Bernabé Rofino Gabriel
 * Instituição: Universidade Licungo
 * Curso: Mestrado em Tecnologias Educativas e Inovação
 * Módulo: Aplicações Web
 * Docente: Prof. Félix Singo
 * Ano: 2026
 * Direitos: Todos os direitos reservados.
 */

document.querySelectorAll('[data-password-target]').forEach(function(button) {
  button.addEventListener('click', function() {
    var field = document.getElementById(button.dataset.passwordTarget);
    if (!field) return;
    var show = field.type === 'password';
    field.type = show ? 'text' : 'password';
    button.textContent = show ? 'Ocultar' : 'Mostrar';
    button.setAttribute('aria-pressed', String(show));
    button.setAttribute('aria-label', show ? 'Ocultar palavra-passe' : 'Mostrar palavra-passe');
  });
});

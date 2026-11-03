/*
 * Módulo login moderno da plataforma TechShop.
 * Projecto: TechShop — Loja Virtual de Acessórios Informáticos
 * Autor: Bernabé Rofino Gabriel
 * Instituição: Universidade Licungo
 * Curso: Mestrado em Tecnologias Educativas e Inovação
 * Módulo: Aplicações Web
 * Docente: Prof. Félix Singo
 * Ano: 2026
 * Direitos: Todos os direitos reservados.
 */

// Regista o evento para executar a lógica no momento adequado.

document.addEventListener('DOMContentLoaded',()=>{const input=document.getElementById('senha');const btn=document.getElementById('toggle-password');if(!input||!btn)return;btn.addEventListener('click',()=>{const visible=input.type==='text';input.type=visible?'password':'text';btn.textContent=visible?'Mostrar':'Ocultar';btn.setAttribute('aria-label',visible?'Mostrar palavra-passe':'Ocultar palavra-passe');btn.setAttribute('aria-pressed',String(!visible));});});

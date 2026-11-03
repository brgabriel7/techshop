<?php
/**
 * Módulo cliente sair da plataforma TechShop.
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
// Não encerra a sessão administrativa; apenas a identidade do cliente.
unset($_SESSION['cliente_id'],$_SESSION['cliente_nome'],$_SESSION['carrinho']);
session_regenerate_id(true);
go('loja.php');

<?php
/**
 * Módulo logout da plataforma TechShop.
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
 require'config.php';
 $_SESSION=[];
 session_destroy();
 // Define o cabeçalho HTTP da resposta antes de enviar conteúdo.
 header('Location: login.php');
 exit;
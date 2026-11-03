<?php
/**
 * Ponto de entrada e redireccionamento inicial.
 * Projecto: TechShop — Loja Virtual de Acessórios Informáticos
 * Autor: Bernabé Rofino Gabriel
 * Instituição: Universidade Licungo
 * Curso: Mestrado em Tecnologias Educativas e Inovação
 * Módulo: Aplicações Web
 * Docente: Prof. Félix Singo
 * Ano: 2026
 * Direitos: Todos os direitos reservados.
 */

// Página inicial pública da TechShop.
// Define o cabeçalho HTTP da resposta antes de enviar conteúdo.
header('Location: loja.php', true, 302);
exit;

<?php
/**
 * Rodapé e identificação académica da área pública.
 * Projecto: TechShop — Loja Virtual de Acessórios Informáticos
 * Autor: Bernabé Rofino Gabriel
 * Instituição: Universidade Licungo
 * Curso: Mestrado em Tecnologias Educativas e Inovação
 * Módulo: Aplicações Web
 * Docente: Prof. Félix Singo
 * Ano: 2026
 * Direitos: Todos os direitos reservados.
 */
 // Rodapé da área pública TechShop. ?>
<footer class="public-footer" role="contentinfo">
  <div class="public-footer-inner">
    <div class="public-footer-brand"><strong>TechShop</strong><span>Gestão simples, eficiente e segura.</span></div>
    <div class="public-footer-meta"><span>&copy; <?= date('Y') ?> Bernabé Rofino Gabriel. Todos os direitos reservados.</span><a href="loja.php">Início da loja</a><a href="loja.php#produtos">Produtos</a></div>
  </div>
  <div class="techshop-academic-footer">
    <span>Projecto académico desenvolvido por <strong>Bernabé Rofino Gabriel</strong></span>
    <span>Universidade Licungo · Mestrado em Tecnologias Educativas e Inovação</span>
    <span>Módulo: <a href="https://unilicungo.ac.mz/courses/mestrado-em-tecnologia-educacional-e-inovacao/lessons/ite12b-aplicacoes-web/" target="_blank" rel="noopener noreferrer">Aplicações Web</a> · Docente: Prof. Félix Singo</span>
  </div>
</footer>

<style>
.techshop-academic-footer {
  width: 100%; max-width: 1200px; margin: 10px auto 0;
  padding: 14px 18px 4px; border-top: 1px solid rgba(128,146,164,.24);
  display: flex; flex-wrap: wrap; justify-content: center;
  align-items: center; gap: 6px 22px; text-align: center;
  font-size: 12px; line-height: 1.65; color: inherit; opacity: .84;
}
.techshop-academic-footer a { color: inherit; text-decoration: underline; text-underline-offset: 2px; }
@media (max-width: 680px) {.techshop-academic-footer {flex-direction:column;gap:3px;padding:12px 14px 4px;}}
</style>

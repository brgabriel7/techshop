<?php
/**
 * Módulo encomenda sucesso da plataforma TechShop.
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
$pedido=$_SESSION['encomenda_confirmada']??null;
if (!$pedido) go('loja.php');
unset($_SESSION['encomenda_confirmada']);
$msg='Olá, fiz a encomenda #'.$pedido['id'].' na TechShop, total '.number_format($pedido['total'],2,',','.').' MT. Gostaria de confirmar o pagamento e a entrega.';
$link='https://wa.me/258872872515?text='.rawurlencode($msg);
?><!doctype html><html lang="pt"><head>
<link rel="icon" type="image/x-icon" href="favicon.ico?v=2">
<link rel="icon" type="image/png" sizes="32x32" href="assets/favicon-32.png?v=2">
<link rel="apple-touch-icon" href="assets/techshop-icon-192.png?v=2"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Encomenda registada | TechShop</title><link rel="stylesheet" href="assets/loja.css?v=20261103-menu"></head><body><main class="wrap" style="max-width:700px;padding-top:80px"><section class="cart-panel"><h1>✓ Encomenda registada!</h1><p>Número: <strong>#<?=(int)$pedido['id']?></strong></p><p>Total: <strong><?=number_format($pedido['total'],2,',','.')?> MT</strong></p><p class="success">Estado: pendente de confirmação manual.</p><p>Se escolheu M-Pesa, pague para <strong>848677983</strong>. Para e-Mola, <strong>872872515</strong>. Confirme sempre o destinatário antes de transferir.</p><p>Envie a referência ou comprovativo ao vendedor pelo WhatsApp. O envio do comprovativo não confirma automaticamente o pagamento.</p><a class="primary" href="<?=h($link)?>" target="_blank" rel="noopener noreferrer">Enviar encomenda pelo WhatsApp ↗</a> <a class="cart-link" href="loja.php">Voltar à loja</a></section></main><?php include __DIR__.'/public_footer.php';
 ?>
</body></html>
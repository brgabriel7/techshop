<?php
/**
 * TECHSHOP — CONFIRMAÇÃO DE ENCOMENDA
 * Desenvolvedor: Bernabé Rofino Gabriel
 * Instituição: Universidade Licungo
 * Curso: Mestrado em Tecnologias Educativas e Inovação
 * Módulo: Aplicações Web | Docente: Prof. Félix Singo
 * Ano: 2026 | Todos os direitos reservados.
 *
 * Esta página apresenta a encomenda registada e instruções de pagamento.
 * Não valida nem confirma automaticamente qualquer pagamento.
 */

// 1. Inicializa a sessão e disponibiliza as funções da aplicação.
require_once __DIR__ . '/config.php';

// 2. Obtém os dados da encomenda guardados pelo checkout.
$pedido = $_SESSION['encomenda_confirmada'] ?? null;

// 3. Evita mostrar uma confirmação sem encomenda na sessão.
if (!$pedido) {
    go('loja.php');
}

// 4. Remove o aviso da sessão para evitar a repetição após actualizar a página.
unset($_SESSION['encomenda_confirmada']);

// 5. Formata os dados para apresentação. Não altera os valores da encomenda.
$numeroEncomenda = (int) ($pedido['id'] ?? 0);
$totalEncomenda = number_format((float) ($pedido['total'] ?? 0), 2, ',', '.');

// 6. Prepara a mensagem do WhatsApp com o número e o total da encomenda.
$mensagemWhatsapp = sprintf(
    'Olá, fiz a encomenda #%d na TechShop, total %s MT. Gostaria de confirmar o pagamento e a entrega.',
    $numeroEncomenda,
    $totalEncomenda
);
$linkWhatsapp = 'https://wa.me/258872872515?text=' . rawurlencode($mensagemWhatsapp);
?>
<!doctype html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0874c5">
    <title>Encomenda registada | TechShop</title>

    <!-- Identidade visual e estilos partilhados com a loja. -->
    <link rel="icon" type="image/x-icon" href="favicon.ico?v=2">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon-32.png?v=2">
    <link rel="apple-touch-icon" href="assets/techshop-icon-192.png?v=2">
    <link rel="stylesheet" href="assets/loja.css?v=20261104-mobile">

    <!-- Estilos exclusivos da confirmação; não afectam outras páginas. -->
    <style>
        .ts-confirmation-page {
            width: 100%;
            max-width: 850px;
            padding-top: clamp(24px, 6vw, 68px);
            padding-bottom: clamp(38px, 6vw, 80px);
        }
        .ts-confirmation-card {
            overflow: hidden;
            background: #fff;
            border: 1px solid #e1eaf2;
            border-radius: 20px;
            box-shadow: 0 16px 48px rgba(25, 65, 100, .07);
        }
        .ts-confirmation-header {
            padding: 32px 34px 24px;
            background: linear-gradient(125deg, #f0f9ff, #fff 72%);
        }
        .ts-confirmation-icon {
            display: grid;
            place-items: center;
            width: 56px;
            height: 56px;
            margin-bottom: 18px;
            border-radius: 50%;
            background: #dff6ea;
            color: #17754b;
            font-size: 28px;
            font-weight: 800;
        }
        .ts-confirmation-header h1 {
            margin: 0 0 9px;
            color: #122d46;
            font-size: clamp(25px, 4vw, 34px);
            line-height: 1.2;
        }
        .ts-confirmation-header p { margin: 0; color: #647d90; line-height: 1.65; }
        .ts-confirmation-body { padding: 0 34px 34px; }
        .ts-order-summary {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin: 20px 0;
        }
        .ts-summary-item {
            padding: 17px 19px;
            background: #f7fafd;
            border: 1px solid #e7eef5;
            border-radius: 13px;
        }
        .ts-summary-item span { display: block; margin-bottom: 7px; color: #698093; font-size: 13px; }
        .ts-summary-item strong { color: #11344f; font-size: clamp(20px, 3vw, 26px); }
        .ts-summary-item:last-child strong { color: #0874c5; }
        .ts-payment-status {
            display: flex;
            gap: 11px;
            align-items: flex-start;
            padding: 15px 17px;
            background: #fff8e9;
            color: #825500;
            border: 1px solid #f3dfae;
            border-radius: 12px;
            line-height: 1.5;
            font-size: 14px;
        }
        .ts-payment-status strong { display: block; }
        .ts-section-title { margin: 28px 0 8px; font-size: 19px; color: #15334b; }
        .ts-help-text { margin: 0 0 16px; color: #657d8f; font-size: 14px; line-height: 1.6; }
        .ts-payment-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 13px; }
        .ts-payment-method { padding: 19px; border: 1px solid #e0eaf3; border-radius: 14px; background: #fff; }
        .ts-payment-method h3 { margin: 0 0 9px; font-size: 16px; color: #173a56; }
        .ts-payment-method .ts-payment-number { display: block; font-size: 23px; font-weight: 800; color: #0874c5; letter-spacing: .4px; overflow-wrap: anywhere; }
        .ts-payment-method p { margin: 8px 0 0; font-size: 12px; color: #6b8193; line-height: 1.5; }
        .ts-payment-note { margin: 16px 0 0; font-size: 13px; line-height: 1.65; color: #526e82; }
        .ts-confirmation-actions { display: flex; flex-wrap: wrap; gap: 12px; align-items: center; margin-top: 27px; }
        .ts-confirmation-actions .primary { text-align: center; }
        .ts-back-link { display: inline-flex; align-items: center; justify-content: center; min-height: 44px; padding: 10px 14px; font-size: 14px; font-weight: 700; color: #0874c5; }
        @media (max-width: 600px) {
            .ts-confirmation-page { padding-top: 18px; padding-bottom: 38px; }
            .ts-confirmation-card { border-radius: 15px; }
            .ts-confirmation-header { padding: 25px 20px 18px; }
            .ts-confirmation-body { padding: 0 20px 24px; }
            .ts-order-summary, .ts-payment-grid { grid-template-columns: 1fr; }
            .ts-confirmation-actions { flex-direction: column; align-items: stretch; }
            .ts-confirmation-actions .primary, .ts-back-link { display: flex; width: 100%; min-height: 46px; align-items: center; justify-content: center; }
        }
    </style>
</head>
<body>
    <!-- Cabeçalho público: logótipo, navegação, carrinho e conta do cliente. -->
    <?php include __DIR__ . '/public_header.php'; ?>

    <main class="wrap ts-confirmation-page">
        <section class="ts-confirmation-card" aria-labelledby="ts-confirmation-title">
            <!-- 7. Comunica o registo da encomenda sem sugerir pagamento confirmado. -->
            <header class="ts-confirmation-header">
                <div class="ts-confirmation-icon" aria-hidden="true">✓</div>
                <h1 id="ts-confirmation-title">Encomenda registada!</h1>
                <p>Recebemos a sua encomenda. Consulte os dados abaixo e siga as instruções para confirmar o pagamento.</p>
            </header>

            <div class="ts-confirmation-body">
                <!-- 8. Apresenta o número e o valor total registados no checkout. -->
                <div class="ts-order-summary" aria-label="Resumo da encomenda">
                    <div class="ts-summary-item">
                        <span>Número da encomenda</span>
                        <strong>#<?= $numeroEncomenda ?></strong>
                    </div>
                    <div class="ts-summary-item">
                        <span>Valor total</span>
                        <strong><?= $totalEncomenda ?> MT</strong>
                    </div>
                </div>

                <!-- 9. O estado continua pendente de validação manual. -->
                <div class="ts-payment-status" role="status">
                    <span aria-hidden="true">◷</span>
                    <div><strong>Pagamento pendente de confirmação</strong>A encomenda foi registada, mas o pagamento ainda não foi confirmado pela TechShop.</div>
                </div>

                <!-- 10. Exibe os números já utilizados pela aplicação. -->
                <h2 class="ts-section-title">Como efectuar o pagamento</h2>
                <p class="ts-help-text">Utilize o método de pagamento escolhido no checkout e confirme sempre o nome do destinatário antes de transferir.</p>
                <div class="ts-payment-grid">
                    <div class="ts-payment-method">
                        <h3>M-Pesa</h3>
                        <span class="ts-payment-number">848677983</span>
                        <p>Pagamento por transferência M-Pesa.</p>
                    </div>
                    <div class="ts-payment-method">
                        <h3>e-Mola</h3>
                        <span class="ts-payment-number">872872515</span>
                        <p>Pagamento por transferência e-Mola.</p>
                    </div>
                </div>

                <p class="ts-payment-note">Depois do pagamento, envie a referência ou o comprovativo ao vendedor pelo WhatsApp. O envio do comprovativo <strong>não confirma automaticamente</strong> o pagamento.</p>

                <!-- 11. Abre o WhatsApp com a mensagem preparada e permite voltar à loja. -->
                <div class="ts-confirmation-actions">
                    <a class="primary" href="<?= h($linkWhatsapp) ?>" target="_blank" rel="noopener noreferrer">Enviar encomenda pelo WhatsApp ↗</a>
                    <a class="ts-back-link" href="loja.php">← Voltar à loja</a>
                </div>
            </div>
        </section>
    </main>

    <!-- 12. Reutiliza o rodapé institucional e académico da TechShop. -->
    <?php include __DIR__ . '/public_footer.php'; ?>
</body>
</html>

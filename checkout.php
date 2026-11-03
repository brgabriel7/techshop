<?php
/**
 * Validação e registo de encomendas.
 * Projecto: TechShop — Loja Virtual de Acessórios Informáticos
 * Autor: Bernabé Rofino Gabriel
 * Instituição: Universidade Licungo
 * Curso: Mestrado em Tecnologias Educativas e Inovação
 * Módulo: Aplicações Web
 * Docente: Prof. Félix Singo
 * Ano: 2026
 * Direitos: Todos os direitos reservados.
 */

/** Regista a encomenda e reserva o stock numa transacção MySQL. */
// Carrega as dependências e funções necessárias para esta página.
require_once __DIR__.'/config.php';
$_SESSION['carrinho'] ??= [];
if (!$_SESSION['carrinho']) go('carrinho.php');
$erro='';
// Recupera os dados do cliente autenticado directamente da base de dados.
$dadosCliente = ['nome'=>'', 'telefone'=>'', 'endereco'=>''];
if (cliente_id()) {
    // Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
    $consultaCliente = $pdo->prepare('SELECT nome, telefone, endereco FROM contas_clientes WHERE id=? LIMIT 1');
    $consultaCliente->execute([cliente_id()]);
    $dadosCliente = $consultaCliente->fetch() ?: $dadosCliente;
}
$nomeInicial = $_POST['nome'] ?? $dadosCliente['nome'];
$telefoneInicial = $_POST['telefone'] ?? $dadosCliente['telefone'];
$enderecoInicial = $_POST['endereco'] ?? $dadosCliente['endereco'];
// Processa os dados enviados pelo formulário através de uma requisição HTTP.
if ($_SERVER['REQUEST_METHOD']==='POST') {
    // Confirma o token de segurança para proteger a operação contra CSRF.
    check_token();
    $nome=trim($nomeInicial);
$telefone=trim($telefoneInicial);
$endereco=trim($enderecoInicial);
    $metodo=$_POST['metodo']??'';
    if ($nome==='' || mb_strlen($nome)>160 || !preg_match('/^[0-9+ ()-]{8,25}$/',$telefone) || mb_strlen($endereco)>255 || !in_array($metodo,['mpesa','emola','whatsapp'],true)) {
        $erro='Preencha correctamente o nome, telefone e método de pagamento.';
    } else {
        try {
            // Inicia uma transacção para manter encomenda, itens e stock consistentes.
    // Inicia uma transacção para manter a consistência dos dados.
    $pdo->beginTransaction();
$total=0;
$itens=[];
            // Bloqueio de linhas evita vender mais unidades do que as disponíveis.
            // Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
            $sel=$pdo->prepare('SELECT id,nome,preco,stock FROM produtos WHERE id=? FOR UPDATE');
            // Percorre os registos e aplica o processamento necessário a cada item.
            foreach($_SESSION['carrinho'] as $id=>$q) {
                $id=(int)$id;
$q=(int)$q;
if($id<1||$q<1) throw new RuntimeException('Carrinho inválido.');
                $sel->execute([$id]);
$p=$sel->fetch();
                if (!$p || $q>(int)$p['stock']) throw new RuntimeException('Stock insuficiente. Actualize o carrinho.');
                $total+=(float)$p['preco']*$q;
$itens[]=['id'=>$id,'q'=>$q,'preco'=>$p['preco']];
            }
            if(!$itens) throw new RuntimeException('Carrinho vazio.');
            // Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
            $stmt=$pdo->prepare("INSERT INTO encomendas (nome_cliente,telefone,endereco,metodo_pagamento,total,estado,cliente_id) VALUES (?,?,?,?,?,'pendente',?)");
            $stmt->execute([$nome,$telefone,$endereco,$metodo,$total,cliente_id()?:null]);
$encomendaId=(int)$pdo->lastInsertId();
            // Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
            $itemStmt=$pdo->prepare('INSERT INTO encomenda_itens (encomenda_id,produto_id,quantidade,preco_unitario) VALUES (?,?,?,?)');
            // Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
            $stockStmt=$pdo->prepare('UPDATE produtos SET stock=stock-? WHERE id=?');
            // Percorre os registos e aplica o processamento necessário a cada item.
            foreach($itens as $item){$itemStmt->execute([$encomendaId,$item['id'],$item['q'],$item['preco']]);
$stockStmt->execute([$item['q'],$item['id']]);
}
            // Confirma as alterações na base de dados após a operação bem-sucedida.
            $pdo->commit();
unset($_SESSION['carrinho']);
guardar_carrinho($pdo);
$_SESSION['encomenda_confirmada']=['id'=>$encomendaId,'total'=>$total,'metodo'=>$metodo];
go('encomenda_sucesso.php');
        } catch(Throwable $e) {if($pdo->inTransaction())$pdo->rollBack();
error_log($e->getMessage());
$erro=$e instanceof RuntimeException?$e->getMessage():'Não foi possível guardar a encomenda.';
}
    }
}
?>
<!doctype html><html lang="pt"><head>
<link rel="icon" type="image/x-icon" href="favicon.ico?v=2">
<link rel="icon" type="image/png" sizes="32x32" href="assets/favicon-32.png?v=2">
<link rel="apple-touch-icon" href="assets/techshop-icon-192.png?v=2"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Finalizar encomenda | TechShop</title><link rel="stylesheet" href="assets/loja.css?v=20261103-menu"></head><body><?php include __DIR__.'/public_header.php';
 ?><main class="wrap"><div class="cart-layout"><section class="cart-panel"><h1>Finalizar encomenda</h1><p class="muted"><?=cliente_id() ? "Dados preenchidos a partir do cadastro. Confirme-os antes de comprar." : "Preencha os seus dados para registar a encomenda."?></p><?php if(!cliente_id()):?><p class="muted"><a href="cliente_login.php">Entrar na minha conta para preencher automaticamente</a></p><?php endif;?><?php if($erro):?><div class="alert"><?=h($erro)?></div><?php endif;
 ?><form method="post"><input type="hidden" name="csrf" value="<?=h(token())?>"><label class="field">Nome completo<input name="nome" maxlength="160" required value="<?=h($nomeInicial)?>"></label><label class="field">Telefone<input name="telefone" maxlength="25" required value="<?=h($telefoneInicial)?>"></label><label class="field">Endereço de entrega<textarea name="endereco" maxlength="255"><?=h($enderecoInicial)?></textarea></label><label class="field">Método de pagamento<select name="metodo" required><option value="mpesa">M-Pesa (manual)</option><option value="emola">e-Mola (manual)</option><option value="whatsapp">Combinar pelo WhatsApp</option></select></label><button class="primary" type="submit">Confirmar encomenda</button></form></section><aside class="cart-panel"><h2>Pagamento</h2><p><strong>M-Pesa:</strong> 848677983</p><p><strong>e-Mola:</strong> 872872515</p><p class="muted">Não efectue o pagamento antes de confirmar os dados e o valor. O pagamento será verificado manualmente pelo vendedor.</p></aside></div></main><?php include __DIR__.'/public_footer.php';
 ?>
</body></html>

<?php
/**
 * Módulo admin encomendas da plataforma TechShop.
 * Projecto: TechShop — Loja Virtual de Acessórios Informáticos
 * Autor: Bernabé Rofino Gabriel
 * Instituição: Universidade Licungo
 * Curso: Mestrado em Tecnologias Educativas e Inovação
 * Módulo: Aplicações Web
 * Docente: Prof. Félix Singo
 * Ano: 2026
 * Direitos: Todos os direitos reservados.
 */

/** Gestão administrativa das encomendas. Mantém o esquema de dados existente. */
// Carrega as dependências e funções necessárias para esta página.
require_once __DIR__.'/config.php';
auth();
$erro='';
$sucesso='';
// Processa os dados enviados pelo formulário através de uma requisição HTTP.
if ($_SERVER['REQUEST_METHOD']==='POST') {
    // Confirma o token de segurança para proteger a operação contra CSRF.
    check_token();
    $id=filter_var($_POST['encomenda_id']??null,FILTER_VALIDATE_INT);
    $acao=$_POST['acao']??'';
    if (!$id || !in_array($acao,['pagar','cancelar'],true)) {
        $erro='Operação inválida.';
    } else {
        try {
            // Inicia uma transacção para manter a consistência dos dados.
            $pdo->beginTransaction();
            // Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
            $st=$pdo->prepare('SELECT id,estado FROM encomendas WHERE id=? FOR UPDATE');
            $st->execute([$id]);
$pedido=$st->fetch();
            if (!$pedido) throw new RuntimeException('Encomenda não encontrada.');
            if ($pedido['estado']!=='pendente') throw new RuntimeException('Só é possível alterar encomendas pendentes.');
            if ($acao==='cancelar') {
                // O checkout original desconta stock. Repor apenas uma vez, durante o cancelamento.
                // Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
                $itens=$pdo->prepare('SELECT produto_id,quantidade FROM encomenda_itens WHERE encomenda_id=?');
                $itens->execute([$id]);
                // Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
                $rep=$pdo->prepare('UPDATE produtos SET stock=stock+? WHERE id=?');
                // Percorre os registos e aplica o processamento necessário a cada item.
                foreach($itens as $item) if ($item['produto_id']!==null) $rep->execute([(int)$item['quantidade'],(int)$item['produto_id']]);
                $novo='cancelado';
            } else $novo='pago';
            // Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
            $up=$pdo->prepare('UPDATE encomendas SET estado=? WHERE id=?');
            $up->execute([$novo,$id]);
            // Confirma as alterações na base de dados após a operação bem-sucedida.
            $pdo->commit();
            $sucesso=$acao==='pagar'?'Pagamento confirmado.':'Encomenda cancelada e stock reposto.';
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $erro=$e instanceof RuntimeException?$e->getMessage():'Não foi possível actualizar a encomenda.';
            error_log($e->getMessage());
        }
    }
}
$filtro=$_GET['estado']??'todos';
if (!in_array($filtro,['todos','pendente','pago','cancelado'],true)) $filtro='todos';
$pesquisa=trim($_GET['q']??'');
$condicoes=[];
$args=[];
if ($filtro!=='todos') {$condicoes[]='e.estado=?';
$args[]=$filtro;
}
if ($pesquisa!=='') {
    $condicoes[]='(e.nome_cliente LIKE ? OR e.telefone LIKE ? OR CAST(e.id AS CHAR) = ?)';
    $args[]='%'.$pesquisa.'%';
$args[]='%'.$pesquisa.'%';
$args[]=ltrim($pesquisa,'#');
}
$sql='SELECT e.id,e.nome_cliente,e.telefone,e.metodo_pagamento,e.total,e.estado,e.criado_em FROM encomendas e';
if ($condicoes) $sql.=' WHERE '.implode(' AND ',$condicoes);
$sql.=' ORDER BY e.id DESC LIMIT 200';
// Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
$st=$pdo->prepare($sql);
$st->execute($args);
$pedidos=$st->fetchAll();
$totais=['pendente'=>0,'pago'=>0,'cancelado'=>0,'todos'=>0];
// Percorre os registos e aplica o processamento necessário a cada item.
foreach($pdo->query('SELECT estado,COUNT(*) quantidade FROM encomendas GROUP BY estado') as $r) {
    $totais[$r['estado']]=(int)$r['quantidade'];
$totais['todos']+=(int)$r['quantidade'];
}
?><!doctype html><html lang="pt"><head>
<link rel="icon" type="image/x-icon" href="favicon.ico?v=2">
<link rel="icon" type="image/png" sizes="32x32" href="assets/favicon-32.png?v=2">
<link rel="apple-touch-icon" href="assets/techshop-icon-192.png?v=2"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Encomendas | TechShop</title><link rel="stylesheet" href="assets/style.css?v=orders2"><link rel="stylesheet" href="assets/admin_encomendas.css?v=2"></head><body>
<?php include __DIR__.'/header.php';
 ?>
<main class="orders-wrap">
 <div class="orders-heading"><div><span class="orders-kicker">GESTÃO DE VENDAS</span><h1>Encomendas recebidas</h1><p>Acompanhe os pedidos, confirme pagamentos e consulte os produtos comprados.</p></div><a class="orders-back" href="painel.php">← Voltar ao painel</a></div>
 <?php if($erro):?><div class="orders-alert orders-error" role="alert"><?=h($erro)?></div><?php endif;?>
 <?php if($sucesso):?><div class="orders-alert orders-success" role="status"><?=h($sucesso)?></div><?php endif;?>
 <div class="orders-stats">
 <?php foreach(['todos'=>'Total de encomendas','pendente'=>'Pendentes','pago'=>'Pagas','cancelado'=>'Canceladas'] as $k=>$titulo):?><a class="orders-stat <?= $filtro===$k?'selected':'' ?>" href="?estado=<?=h($k)?>"><span><?=h($titulo)?></span><strong><?=(int)$totais[$k]?></strong></a><?php endforeach;?></div>
 <section class="orders-panel">
 <form method="get" class="orders-filter"><label for="q">Pesquisar encomendas</label><div class="orders-filter-row"><input id="q" name="q" value="<?=h($pesquisa)?>" placeholder="Nome, telefone ou número da encomenda"><select name="estado" aria-label="Filtrar por estado"><option value="todos" <?=$filtro==='todos'?'selected':''?>>Todos os estados</option><option value="pendente" <?=$filtro==='pendente'?'selected':''?>>Pendentes</option><option value="pago" <?=$filtro==='pago'?'selected':''?>>Pagas</option><option value="cancelado" <?=$filtro==='cancelado'?'selected':''?>>Canceladas</option></select><button type="submit">Pesquisar</button></div></form>
 <div class="orders-table-scroll"><table class="orders-table"><thead><tr><th>N.º</th><th>Cliente</th><th>Data</th><th>Método</th><th>Total</th><th>Estado</th><th>Acções</th></tr></thead><tbody>
 <?php foreach($pedidos as $p):?><tr><td><strong>#<?=(int)$p['id']?></strong></td><td><strong><?=h($p['nome_cliente'])?></strong><small><?=h($p['telefone'])?></small></td><td><?=h(date('d/m/Y H:i',strtotime($p['criado_em'])))?></td><td><?=h(strtoupper($p['metodo_pagamento']))?></td><td class="orders-price"><?=number_format((float)$p['total'],2,',','.')?> MT</td><td><span class="orders-badge <?=h($p['estado'])?>"><?=h(ucfirst($p['estado']))?></span></td><td><div class="orders-actions"><a class="orders-detail" href="admin_encomenda_detalhes.php?id=<?=(int)$p['id']?>">Ver detalhes</a><?php if($p['estado']==='pendente'):?><form method="post" onsubmit="return confirm('Confirmar que o pagamento foi efectivamente recebido?');"><input type="hidden" name="csrf" value="<?=h(token())?>"><input type="hidden" name="encomenda_id" value="<?=(int)$p['id']?>"><input type="hidden" name="acao" value="pagar"><button class="orders-confirm" type="submit">Confirmar pagamento</button></form><form method="post" onsubmit="return confirm('Cancelar a encomenda e devolver os produtos ao stock?');"><input type="hidden" name="csrf" value="<?=h(token())?>"><input type="hidden" name="encomenda_id" value="<?=(int)$p['id']?>"><input type="hidden" name="acao" value="cancelar"><button class="orders-cancel" type="submit">Cancelar</button></form><?php endif;?></div></td></tr><?php endforeach;?>
 <?php if(!$pedidos):?><tr><td colspan="7" class="orders-empty">Não foram encontradas encomendas para os filtros seleccionados.</td></tr><?php endif;?></tbody></table></div><p class="orders-footnote">Apresentadas até 200 encomendas por pesquisa. Os pagamentos são confirmados manualmente.</p></section>
</main><?php include __DIR__.'/admin_footer.php';
 ?>
</body></html>

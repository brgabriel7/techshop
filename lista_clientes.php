<?php
/**
 * Listagem e consulta de clientes.
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
auth();
$q=trim($_GET['q']??'');
$tipo=$_GET['tipo']??'todos';
if (!in_array($tipo,['todos','online','manual'],true)) $tipo='todos';
$registos=[];
// Apenas compras comprovadamente ligadas à conta pelo ID.
if ($tipo!=='manual') {
 $sql="SELECT c.id,c.nome,c.email,c.telefone,c.foto,c.criado_em,COUNT(e.id) AS compras FROM contas_clientes c LEFT JOIN encomendas e ON e.cliente_id=c.id WHERE (c.nome LIKE ? OR c.email LIKE ? OR c.telefone LIKE ?) GROUP BY c.id,c.nome,c.email,c.telefone,c.foto,c.criado_em ORDER BY c.criado_em DESC";
 // Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
 $st=$pdo->prepare($sql);
$like='%'.$q.'%';
$st->execute([$like,$like,$like]);
 // Percorre os registos e aplica o processamento necessário a cada item.
 foreach($st as $r){$r['origem']='online';
$registos[]=$r;
}
}
// Os clientes cadastrados manualmente não possuem conta de acesso nem associação fiável às compras.
if ($tipo!=='online') {
 $sql="SELECT id,nome,email,telefone,foto,criado_em FROM clientes WHERE (nome LIKE ? OR COALESCE(email,'') LIKE ? OR telefone LIKE ?) ORDER BY criado_em DESC";
 // Prepara a consulta SQL com parâmetros para evitar injecção de SQL.
 $st=$pdo->prepare($sql);
$like='%'.$q.'%';
$st->execute([$like,$like,$like]);
 // Percorre os registos e aplica o processamento necessário a cada item.
 foreach($st as $r){$r['origem']='manual';
$r['compras']=null;
$registos[]=$r;
}
}
usort($registos,static function($a,$b){return strcmp((string)$b['criado_em'],(string)$a['criado_em']);});
function foto_registo(array $r): string {
 $nome=(string)($r['foto']??'');
 $pasta=$r['origem']==='online'?'uploads/perfis/':'uploads/clientes/';
 if (preg_match('/^[a-f0-9]{32}\.(jpg|png|webp)$/D',$nome) && is_file(__DIR__.'/'.$pasta.$nome))
  return '<img class="unified-avatar" src="'.h($pasta.$nome).'" alt="Fotografia de '.h($r['nome']).'">';
 return '<span class="unified-avatar unified-avatar-fallback">'.h(mb_strtoupper(mb_substr($r['nome'],0,1))).'</span>';
}
?><!doctype html><html lang="pt"><head>
<link rel="icon" type="image/x-icon" href="favicon.ico?v=2">
<link rel="icon" type="image/png" sizes="32x32" href="assets/favicon-32.png?v=2">
<link rel="apple-touch-icon" href="assets/techshop-icon-192.png?v=2"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Clientes | TechShop</title><link rel="stylesheet" href="assets/style.css?v=clients3"><link rel="stylesheet" href="assets/clientes_unificados.css?v=1"></head><body>
<?php include __DIR__.'/header.php';
 ?>
<main class="wrap unified-clients">
 <div class="rowhead"><div><h1>Gestão de clientes</h1><p class="muted">Clientes registados, origem do cadastro e histórico de compras associadas.</p></div><a class="btn" href="cadastrar_cliente.php">+ Cadastro manual</a></div>
 <form class="unified-filters" method="get"><input type="search" name="q" value="<?=h($q)?>" placeholder="Pesquisar nome, telefone ou e-mail"><select name="tipo"><option value="todos" <?=$tipo==='todos'?'selected':''?>>Todos os cadastros</option><option value="online" <?=$tipo==='online'?'selected':''?>>Conta online</option><option value="manual" <?=$tipo==='manual'?'selected':''?>>Cadastro administrativo</option></select><button type="submit">Pesquisar</button></form>
 <section class="unified-table-wrap"><table class="unified-table"><thead><tr><th>Cliente</th><th>Contacto</th><th>Forma de cadastro</th><th>Data e hora</th><th>Compras</th></tr></thead><tbody>
 <?php foreach($registos as $r):?><tr><td><div class="unified-client"><?=foto_registo($r)?><div><strong><?=h($r['nome'])?></strong><small><?=h($r['email']?:'Sem e-mail')?></small></div></div></td><td><?=h($r['telefone']?:'—')?></td><td><span class="unified-tag <?=$r['origem']==='online'?'online':'manual'?>"><?=$r['origem']==='online'?'Conta online':'Cadastro administrativo'?></span></td><td><?=h(date('d/m/Y H:i',strtotime($r['criado_em'])))?></td><td><?php if($r['compras']===null):?><span class="muted" title="Não existe vínculo de conta para contabilizar compras com segurança">Não associado</span><?php else:?><strong><?= (int)$r['compras'] ?></strong> <span class="muted">encomenda(s)</span><?php endif;?></td></tr><?php endforeach;?>
 <?php if(!$registos):?><tr><td colspan="5" class="unified-empty">Nenhum cliente encontrado.</td></tr><?php endif;?></tbody></table></section>
 <p class="unified-note">As compras de contas online são contadas pelo vínculo <code>encomendas.cliente_id</code>. Registos administrativos sem conta não são associados automaticamente por nome ou telefone.</p>
</main><?php include __DIR__.'/admin_footer.php';
 ?>
</body></html>

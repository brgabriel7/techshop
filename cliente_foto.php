<?php
/**
 * Módulo cliente foto da plataforma TechShop.
 * Projecto: TechShop — Loja Virtual de Acessórios Informáticos
 * Autor: Bernabé Rofino Gabriel
 * Instituição: Universidade Licungo
 * Curso: Mestrado em Tecnologias Educativas e Inovação
 * Módulo: Aplicações Web
 * Docente: Prof. Félix Singo
 * Ano: 2026
 * Direitos: Todos os direitos reservados.
 */

/** Utilitários para fotografias dos clientes (JPG, PNG ou WebP). */
function guardar_foto_cliente(array $ficheiro, ?string $anterior = null): ?string {
    if (($ficheiro['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) return $anterior;
    if ($ficheiro['error'] !== UPLOAD_ERR_OK) throw new RuntimeException('Falha ao carregar a fotografia.');
    if ($ficheiro['size'] > 2 * 1024 * 1024) throw new RuntimeException('A fotografia deve ter no máximo 2 MB.');
    $tipo = (new finfo(FILEINFO_MIME_TYPE))->file($ficheiro['tmp_name']);
    $extensoes = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
    if (!isset($extensoes[$tipo]) || !@getimagesize($ficheiro['tmp_name'])) throw new RuntimeException('Use uma imagem JPG, PNG ou WebP válida.');
    $pasta = __DIR__ . '/uploads/clientes';
    if (!is_dir($pasta) && !mkdir($pasta, 0755, true)) throw new RuntimeException('Não foi possível criar a pasta de fotografias.');
    $nome = bin2hex(random_bytes(16)).'.'.$extensoes[$tipo];
    if (!move_uploaded_file($ficheiro['tmp_name'], $pasta.'/'.$nome)) throw new RuntimeException('Não foi possível guardar a fotografia.');
    return $nome;
}
function apagar_foto_cliente(?string $nome): void {
    if ($nome && preg_match('/^[a-f0-9]{32}\.(jpg|png|webp)$/', $nome)) @unlink(__DIR__.'/uploads/clientes/'.$nome);
}
function avatar_cliente(array $cliente): string {
    if (!empty($cliente['foto']) && preg_match('/^[a-f0-9]{32}\.(jpg|png|webp)$/', $cliente['foto'])) {
        return '<img class="avatar" alt="Fotografia de '.h($cliente['nome']).'" src="uploads/clientes/'.h($cliente['foto']).'">';
    }
    return '<div class="avatar avatar-fallback" aria-label="Sem fotografia">'.h(mb_strtoupper(mb_substr($cliente['nome'],0,1))).'</div>';
}

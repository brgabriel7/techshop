<?php
/**
 * Módulo admin foto da plataforma TechShop.
 * Projecto: TechShop — Loja Virtual de Acessórios Informáticos
 * Autor: Bernabé Rofino Gabriel
 * Instituição: Universidade Licungo
 * Curso: Mestrado em Tecnologias Educativas e Inovação
 * Módulo: Aplicações Web
 * Docente: Prof. Félix Singo
 * Ano: 2026
 * Direitos: Todos os direitos reservados.
 */

// Fotografias exclusivas da área administrativa.
function admin_foto_url(?string $nome): ?string {
    return $nome && preg_match('/^[a-f0-9]{32}\.(jpg|png|webp)$/D',$nome)
        ? 'uploads/administradores/'.$nome : null;
}
function admin_guardar_foto(array $ficheiro): ?string {
    if (($ficheiro['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) return null;
    if (($ficheiro['error'] ?? null) !== UPLOAD_ERR_OK || !is_uploaded_file($ficheiro['tmp_name'] ?? ''))
        throw new RuntimeException('Não foi possível receber a fotografia.');
    if (($ficheiro['size'] ?? 0) < 1 || $ficheiro['size'] > 2097152)
        throw new RuntimeException('A fotografia deve ter até 2 MB.');
    $tipo=(new finfo(FILEINFO_MIME_TYPE))->file($ficheiro['tmp_name']);
    $ext=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'][$tipo] ?? null;
    if (!$ext || @getimagesize($ficheiro['tmp_name']) === false)
        throw new RuntimeException('Escolha uma fotografia JPG, PNG ou WebP válida.');
    $pasta=__DIR__.'/uploads/administradores';
    if (!is_dir($pasta) && !mkdir($pasta,0755,true) && !is_dir($pasta))
        throw new RuntimeException('Não foi possível criar a pasta de fotografias.');
    $nome=bin2hex(random_bytes(16)).'.'.$ext;
    if (!move_uploaded_file($ficheiro['tmp_name'],$pasta.'/'.$nome))
        throw new RuntimeException('Não foi possível guardar a fotografia.');
    return $nome;
}

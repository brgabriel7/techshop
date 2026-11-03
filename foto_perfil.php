<?php
/**
 * Módulo foto perfil da plataforma TechShop.
 * Projecto: TechShop — Loja Virtual de Acessórios Informáticos
 * Autor: Bernabé Rofino Gabriel
 * Instituição: Universidade Licungo
 * Curso: Mestrado em Tecnologias Educativas e Inovação
 * Módulo: Aplicações Web
 * Docente: Prof. Félix Singo
 * Ano: 2026
 * Direitos: Todos os direitos reservados.
 */

// Validação de fotografias de perfil: imagem opcional, até 2 MB.
function guardar_foto_perfil(array $file): ?string {
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) return null;
    if (($file['error'] ?? 0) !== UPLOAD_ERR_OK) throw new RuntimeException('Não foi possível carregar a fotografia.');
    if (!is_uploaded_file($file['tmp_name']) || $file['size'] > 2*1024*1024 || $file['size'] <= 0) throw new RuntimeException('A fotografia deve ter até 2 MB.');
    $finfo=new finfo(FILEINFO_MIME_TYPE);
    $mime=$finfo->file($file['tmp_name']);
    $ext=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'][$mime] ?? null;
    if (!$ext || @getimagesize($file['tmp_name']) === false) throw new RuntimeException('Utilize uma imagem JPG, PNG ou WebP válida.');
    $dir=__DIR__.'/uploads/perfis';
    if (!is_dir($dir) && !mkdir($dir,0755,true) && !is_dir($dir)) throw new RuntimeException('Não foi possível criar a pasta de fotografias.');
    $nome=bin2hex(random_bytes(16)).'.'.$ext;
    if (!move_uploaded_file($file['tmp_name'],$dir.'/'.$nome)) throw new RuntimeException('Não foi possível guardar a fotografia.');
    return $nome;
}
function url_foto_perfil(?string $foto): ?string {
    return $foto && preg_match('/^[a-f0-9]{32}\.(jpg|png|webp)$/',$foto) ? 'uploads/perfis/'.$foto : null;
}

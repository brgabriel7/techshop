<?php
/**
 * Módulo produto imagem da plataforma TechShop.
 * Projecto: TechShop — Loja Virtual de Acessórios Informáticos
 * Autor: Bernabé Rofino Gabriel
 * Instituição: Universidade Licungo
 * Curso: Mestrado em Tecnologias Educativas e Inovação
 * Módulo: Aplicações Web
 * Docente: Prof. Félix Singo
 * Ano: 2026
 * Direitos: Todos os direitos reservados.
 */

/** Valida a fotografia enviada e guarda-a com nome aleatório. */
function guardar_imagem_produto(array $file): ?string {
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) return null;
    if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > 3 * 1024 * 1024) throw new RuntimeException('Imagem inválida ou superior a 3 MB.');
    $info = @getimagesize($file['tmp_name']);
    $mime = $info['mime'] ?? '';
    $ext = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'][$mime] ?? null;
    if (!$ext) throw new RuntimeException('Use uma imagem JPG, PNG ou WebP.');
    $name = bin2hex(random_bytes(16)).'.'.$ext;
    if (!move_uploaded_file($file['tmp_name'], __DIR__.'/uploads/produtos/'.$name)) throw new RuntimeException('Não foi possível guardar a imagem.');
    return $name;
}

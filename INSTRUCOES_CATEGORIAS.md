# TechShop — Categorias e comentários

**Autor:** Bernabé Rofino Gabriel  
**Instituição:** Universidade Licungo  
**Curso:** Mestrado em Tecnologias Educativas e Inovação  
**Módulo:** Aplicações Web — Prof. Félix Singo (2026)

## Instalação
1. Faça cópia de segurança da base de dados e do projecto.
2. No phpMyAdmin, seleccione a base de dados usada no `config.php`.
3. Execute o ficheiro `migracao_categorias_comentarios.sql` **uma única vez**. Se a coluna `comentarios` já existir, não repita o `ALTER TABLE`.
4. Copie os ficheiros PHP e CSS do ZIP para a pasta TechShop.
5. Abra `categorias_produtos.php` no painel para registar outras categorias.
6. Em `inserir_produtos.php`, escolha uma categoria da lista e preencha os comentários.

## Alterações
- As categorias antigas são importadas automaticamente da tabela `produtos`.
- O administrador pode adicionar novas categorias; a eliminação foi intencionalmente omitida para não afectar produtos existentes.
- O campo `comentarios` é guardado na tabela `produtos` e pode ser editado em `editar_produto.php`.
- O campo é destinado a informação interna/descrição; não é apresentado automaticamente na vitrine pública.

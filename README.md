# TechShop — Loja Virtual de Acessórios Informáticos

## Enquadramento académico

Projecto desenvolvido por **Bernabé Rofino Gabriel** como actividade de conclusão do módulo de **Aplicações Web**, do **Mestrado em Tecnologias Educativas e Inovação**, da **Universidade Licungo**, leccionado pelo **Prof. Félix Singo** (2026).

[Módulo de Aplicações Web](https://unilicungo.ac.mz/courses/mestrado-em-tecnologia-educacional-e-inovacao/lessons/ite12b-aplicacoes-web/)

## Tecnologias e funcionalidades

PHP, MySQL/MariaDB, HTML, CSS e JavaScript. Inclui loja pública, catálogo, carrinho, checkout, área de cliente e administração de produtos, utilizadores e encomendas.

## Estrutura existente

- `config.php` — configuração, sessão e acesso a dados
- `loja.php`, `public_header.php`, `public_footer.php` — loja pública
- `carrinho.php`, `checkout.php` — compras e encomendas
- `cliente_*.php`, `minha_conta.php` — área do cliente
- `painel.php`, `header.php`, `admin_footer.php`, `admin_*.php` — administração
- `assets/` — estilos, scripts e imagens
- `migracao_*.sql`, `techshop_base_unica.sql` — scripts SQL

Esta edição mantém os caminhos e nomes originais para reduzir o risco de incompatibilidades. Cada ficheiro de código recebeu identificação académica e comentário descritivo. Os ficheiros PHP foram submetidos a verificação sintáctica (`php -l`), mas não foram realizados testes funcionais com uma base de dados activa.

## Instalação local

1. Faça cópia de segurança da pasta e da base de dados existentes.
2. Extraia `techshop/` para `C:\xampp\htdocs\techshop`.
3. Inicie Apache e MySQL no XAMPP.
4. Verifique as definições de `config.php` e a base de dados correspondente.
5. Aceda a `http://localhost/techshop/loja.php`.

**Segurança:** o ficheiro `config.php` da cópia de origem foi preservado. Reveja-o e retire credenciais antes de publicar no GitHub. O `.gitignore` não substitui essa revisão.

## Direitos de autor

Copyright © 2026 **Bernabé Rofino Gabriel**. Todos os direitos reservados. Consulte `LICENSE`.

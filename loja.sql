-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 03/11/2026 às 20:34
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `loja`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `carrinho_guardado`
--

CREATE TABLE `carrinho_guardado` (
  `cliente_id` int(10) UNSIGNED NOT NULL,
  `produto_id` int(11) NOT NULL,
  `quantidade` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `carrinho_guardado`
--

INSERT INTO `carrinho_guardado` (`cliente_id`, `produto_id`, `quantidade`) VALUES
(1, 3, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(160) NOT NULL,
  `email` varchar(190) DEFAULT NULL,
  `telefone` varchar(35) NOT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `foto` varchar(120) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `contas_clientes`
--

CREATE TABLE `contas_clientes` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(160) NOT NULL,
  `email` varchar(190) NOT NULL,
  `telefone` varchar(35) NOT NULL DEFAULT '',
  `senha_hash` varchar(255) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `foto` varchar(120) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `contas_clientes`
--

INSERT INTO `contas_clientes` (`id`, `nome`, `email`, `telefone`, `senha_hash`, `criado_em`, `foto`, `endereco`) VALUES
(1, 'Luana', 'luana@gmail.com', '872872515', '$2y$10$pFYRwD/0SiT/HaRke6tDZuAkCGYG3pNyPub/tOQCOWllmXFkYbJz6', '2026-11-03 15:14:03', '514dc3f999e7c731a1a3ff56753ca088.png', '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `encomendas`
--

CREATE TABLE `encomendas` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome_cliente` varchar(160) NOT NULL,
  `telefone` varchar(25) NOT NULL,
  `endereco` varchar(255) NOT NULL DEFAULT '',
  `metodo_pagamento` enum('mpesa','emola','whatsapp') NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `estado` enum('pendente','pago','cancelado') NOT NULL DEFAULT 'pendente',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `cliente_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `encomendas`
--

INSERT INTO `encomendas` (`id`, `nome_cliente`, `telefone`, `endereco`, `metodo_pagamento`, `total`, `estado`, `criado_em`, `cliente_id`) VALUES
(1, 'Bachir', '872872515', 'Quelimane', 'mpesa', 3000.00, 'pendente', '2026-10-23 17:46:45', NULL),
(2, 'Bernabé Rofino Gabriel', '848677983', 'Quelimane', 'emola', 2000.00, 'cancelado', '2026-10-23 17:59:20', NULL),
(3, 'Luana', '872872515', 'Quelimane', 'mpesa', 7000.00, 'pendente', '2026-11-03 14:45:09', NULL),
(4, 'Joãozinho', '846992821', 'Quelimane', 'emola', 3.00, 'pendente', '2026-11-03 15:00:50', NULL),
(5, 'Bernabe', '872872515', 'Carlos', 'mpesa', 0.03, 'pago', '2026-11-03 15:04:23', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `encomenda_itens`
--

CREATE TABLE `encomenda_itens` (
  `id` int(10) UNSIGNED NOT NULL,
  `encomenda_id` int(10) UNSIGNED NOT NULL,
  `produto_id` int(11) DEFAULT NULL,
  `quantidade` int(10) UNSIGNED NOT NULL,
  `preco_unitario` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `encomenda_itens`
--

INSERT INTO `encomenda_itens` (`id`, `encomenda_id`, `produto_id`, `quantidade`, `preco_unitario`) VALUES
(1, 1, NULL, 3, 1000.00),
(2, 2, NULL, 2, 1000.00),
(3, 3, NULL, 7, 1000.00),
(4, 4, NULL, 1, 3.00),
(5, 5, NULL, 3, 0.01);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `categoria` varchar(100) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `mensagem` text DEFAULT NULL,
  `data_registo` timestamp NOT NULL DEFAULT current_timestamp(),
  `imagem` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `categoria`, `preco`, `stock`, `mensagem`, `data_registo`, `imagem`) VALUES
(7, 'Rato sem fio - Logitech M185', 'Rato', 899.90, 20, NULL, '2026-11-03 19:20:46', '4afbd9117e6a675786fe5094ff93b7c5.jpg'),
(8, 'TP-Link Archer C6', 'Access Points', 4980.50, 12, NULL, '2026-11-03 19:23:19', '4f4a6bbb0c5a9db3e1edf48fa717c6b9.jpg'),
(9, 'Kingston DataTraveler 64 GB', 'Armazenamento', 999.00, 5, NULL, '2026-11-03 19:25:18', '7d4e8084250d9780d2305d465ab2d6f7.jpg'),
(10, 'Logitech K120 USB', 'Teclados', 780.00, 3, NULL, '2026-11-03 19:27:07', '1b80c822702c4565b401356a1ff792a7.jpg'),
(11, 'Toner HP 26A, tinta Epson 003', 'Impressão e Consumíveis', 2490.99, 5, NULL, '2026-11-03 19:28:58', '3e35197f92280aaba3be477aff8b0519.jpg'),
(12, 'Auscultadores com microfone + webcam', 'Áudio e Vídeo', 6200.00, 2, NULL, '2026-11-03 19:31:45', 'ebd2ab0b907039762178e23363789eb2.jpg');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `password`) VALUES
(1, 'admin', '202cb962ac59075b964b07152d234b70');

-- --------------------------------------------------------

--
-- Estrutura para tabela `utilizadores`
--

CREATE TABLE `utilizadores` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(100) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `foto` varchar(120) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `utilizadores`
--

INSERT INTO `utilizadores` (`id`, `nome`, `senha_hash`, `criado_em`, `foto`) VALUES
(2, 'bgabriel', '$2y$10$v/CXylfdJM0PiyMGr/lmTuAPnbC707Gpw5WUBsaPznK05Pk0bumAG', '2026-10-23 16:05:10', '44f3fa9171d93f732b174d49a831980f.png'),
(3, 'Administrador', '$2y$10$FXfPYtp5DBV1/UTXwDa4AuBjGK2WNqkBRMLu4K5MJDF7uTea0181.', '2026-11-03 16:38:10', 'e780d2cb4b941fc67f55a4413816b6e2.png');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `carrinho_guardado`
--
ALTER TABLE `carrinho_guardado`
  ADD PRIMARY KEY (`cliente_id`,`produto_id`);

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `contas_clientes`
--
ALTER TABLE `contas_clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_contas_email` (`email`);

--
-- Índices de tabela `encomendas`
--
ALTER TABLE `encomendas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_encomendas_cliente` (`cliente_id`);

--
-- Índices de tabela `encomenda_itens`
--
ALTER TABLE `encomenda_itens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_encomenda` (`encomenda_id`),
  ADD KEY `idx_produto` (`produto_id`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Índices de tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contas_clientes`
--
ALTER TABLE `contas_clientes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `encomendas`
--
ALTER TABLE `encomendas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `encomenda_itens`
--
ALTER TABLE `encomenda_itens`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `carrinho_guardado`
--
ALTER TABLE `carrinho_guardado`
  ADD CONSTRAINT `fk_carrinho_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `contas_clientes` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `encomendas`
--
ALTER TABLE `encomendas`
  ADD CONSTRAINT `fk_encomendas_conta` FOREIGN KEY (`cliente_id`) REFERENCES `contas_clientes` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `encomenda_itens`
--
ALTER TABLE `encomenda_itens`
  ADD CONSTRAINT `fk_itens_encomenda` FOREIGN KEY (`encomenda_id`) REFERENCES `encomendas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_itens_produto` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

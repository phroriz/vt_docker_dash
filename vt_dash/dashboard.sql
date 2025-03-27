-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 26/03/2025 às 22:32
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `dashboard`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `groups`
--

INSERT INTO `groups` (`id`, `name`) VALUES
(1, 'VOIT');

-- --------------------------------------------------------

--
-- Estrutura para tabela `powerbis`
--

CREATE TABLE `powerbis` (
  `id` int(11) NOT NULL,
  `hash` text NOT NULL,
  `groups_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `src` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `finshed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `powerbis`
--

INSERT INTO `powerbis` (`id`, `hash`, `groups_id`, `title`, `description`, `src`, `status`, `created_at`, `updated_at`, `finshed_at`) VALUES
(1, '698dc19d489c4e4db73e28a713eab07b', 1, 'Ranking de Projetos', 'Teste', 'https://app.powerbi.com/view?r=eyJrIjoiY2Y4ZjlhODEtMzc1My00MGMyLWIyOGMtNmZlODkxMGQ2NzlhIiwidCI6IjA5N2I4ZjkwLTU2NjAtNDRkNi05NzBjLTZjZjcyMTkzOWU1NCJ9', 1, '2025-03-26 19:48:19', '2025-03-26 20:21:40', NULL),
(2, 'fc6f89cfbad195c7af74806827c30e26', 1, 'DRE GERENCIAL', '', 'https://app.powerbi.com/view?r=eyJrIjoiYzM3NGU0ZjMtYjVkMy00Y2QwLWEyNDktMzUzYzVmN2UwMDlkIiwidCI6IjA5N2I4ZjkwLTU2NjAtNDRkNi05NzBjLTZjZjcyMTkzOWU1NCJ9', 1, '2025-03-26 20:23:21', '2025-03-26 20:23:21', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(250) NOT NULL,
  `pass` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `email`, `pass`) VALUES
(1, 'pedro.roriz@voitconusltoria.com.br', 'e5ab29eee37a2cb280c09963e3c4ab4b');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usersgroups`
--

CREATE TABLE `usersgroups` (
  `id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `groups_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `powerbis`
--
ALTER TABLE `powerbis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fky_pbigroupsid` (`groups_id`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usersgroups`
--
ALTER TABLE `usersgroups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fky_usersid` (`users_id`),
  ADD KEY `fky_groupsid` (`groups_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `powerbis`
--
ALTER TABLE `powerbis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `usersgroups`
--
ALTER TABLE `usersgroups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `powerbis`
--
ALTER TABLE `powerbis`
  ADD CONSTRAINT `fky_pbigroupsid` FOREIGN KEY (`groups_id`) REFERENCES `groups` (`id`);

--
-- Restrições para tabelas `usersgroups`
--
ALTER TABLE `usersgroups`
  ADD CONSTRAINT `fky_groupsid` FOREIGN KEY (`groups_id`) REFERENCES `groups` (`id`),
  ADD CONSTRAINT `fky_usersid` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

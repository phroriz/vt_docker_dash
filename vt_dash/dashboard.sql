-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 15/05/2025 às 22:23
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
-- Estrutura para tabela `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `admins`
--

INSERT INTO `admins` (`id`, `user_id`, `created_at`) VALUES
(5, 1, '2025-04-14 11:27:49');

-- --------------------------------------------------------

--
-- Estrutura para tabela `comments`
--

CREATE TABLE `comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `dashs_id` int(11) NOT NULL,
  `parent_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `text` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `dashs`
--

CREATE TABLE `dashs` (
  `id` int(11) NOT NULL,
  `hash` text NOT NULL,
  `groups_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `src` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `finshed_at` timestamp NULL DEFAULT NULL,
  `qtd_access` int(7) UNSIGNED ZEROFILL NOT NULL DEFAULT 0000000,
  `last_access` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `dashs`
--

INSERT INTO `dashs` (`id`, `hash`, `groups_id`, `title`, `description`, `src`, `status`, `created_at`, `updated_at`, `finshed_at`, `qtd_access`, `last_access`) VALUES
(1, '698dc19d489c4e4db73e28a713eab07b', 1, 'Ranking de Projetos', 'Teste 123', 'https://app.powerbi.com/view?r=eyJrIjoiY2Y4ZjlhODEtMzc1My00MGMyLWIyOGMtNmZlODkxMGQ2NzlhIiwidCI6IjA5N2I4ZjkwLTU2NjAtNDRkNi05NzBjLTZjZjcyMTkzOWU1NCJ9', 1, '2025-03-26 19:48:19', '2025-05-15 20:20:03', NULL, 0000205, '2025-05-16 01:20:03'),
(2, 'fc6f89cfbad195c7af74806827c30e26', 1, 'DRE GERENCIAL', 'aaaaaaaaaa', 'https://app.powerbi.com/view?r=eyJrIjoiYzM3NGU0ZjMtYjVkMy00Y2QwLWEyNDktMzUzYzVmN2UwMDlkIiwidCI6IjA5N2I4ZjkwLTU2NjAtNDRkNi05NzBjLTZjZjcyMTkzOWU1NCJ9', 1, '2025-02-14 20:23:21', '2025-05-15 20:18:49', NULL, 0000039, '2025-05-16 01:18:49'),
(6, 'c793ec9d25353dd98f53ccde92cd5d7165ae2479', 1, 'teste 123', 'aaa', 'https://app.powerbi.com/view?r=eyJrIjoiYzM3NGU0ZjMtYjVkMy00Y2QwLWEyNDktMzUzYzVmN2UwMDlkIiwidCI6IjA5N2I4ZjkwLTU2NjAtNDRkNi05NzBjLTZjZjcyMTkzOWU1NCJ9', 0, '2025-04-11 18:17:30', '2025-04-14 13:35:24', NULL, 0000000, '2025-04-14 19:47:37'),
(7, '36a6813b819d1a4696a392714e6af2872622bf18', 4, 't', 'a', 'a', 0, '2025-04-14 13:43:34', '2025-04-14 13:44:00', NULL, 0000000, '2025-04-14 19:47:37'),
(8, '9b62d99485dc914bf469551776e9613af68a09db', 3, 'aaaa', 'aaa', 'a123', 1, '2025-04-15 18:23:17', '2025-04-15 20:56:54', NULL, 0000020, '2025-04-16 01:56:54');

-- --------------------------------------------------------

--
-- Estrutura para tabela `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `expires` date DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `groups`
--

INSERT INTO `groups` (`id`, `hash`, `name`, `description`, `expires`, `status`) VALUES
(1, 'da39a3ee5e6b4b0d3255bfef95601890afd80709', 'TESTE DASH', 'teste descrição do dash interno', '0000-00-00', 1),
(3, '0eb389b08b676aff71990badaf927540aacce3cb', 'teste', 'aa', '2025-04-30', 1),
(4, '629ef804b78eb2eda8755c57995984cc8e90a469', 'teste 123', 'a', '2025-05-01', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `dashs_id` int(11) NOT NULL,
  `score` tinyint(4) NOT NULL,
  `suggestion` text DEFAULT NULL,
  `submitted_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `ratings`
--

INSERT INTO `ratings` (`id`, `user_id`, `dashs_id`, `score`, `suggestion`, `submitted_at`) VALUES
(13, 1, 1, 3, 'uma bosta', '2025-04-15 21:00:16'),
(14, 1, 2, 5, '', '2025-04-15 21:02:38'),
(15, 1, 2, 5, '', '2025-05-15 22:11:54'),
(16, 1, 1, 5, '', '2025-05-15 22:18:41');

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(250) NOT NULL,
  `pass` varchar(250) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `fisth_login` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `pass`, `status`, `created_at`, `updated_at`, `fisth_login`, `last_login`) VALUES
(1, 'Admin', 'admin@voit.tech', '$2y$10$5zBKsHV42J15IWOvV/AsRup/Y4eCCzRugOyyhW1M/T4mqiEtpDqL2', 1, '2025-04-04 17:54:23', '2025-05-15 17:18:21', '2025-04-15 17:10:16', '2025-05-15 22:18:21'),
(11, 'aa', 'teste@aa.cc', '$2y$10$989VkNk6yD2iy787zhbpFOrw66vd8E9n1Lf3Whd86Hw3GPuNYuCXW', 2, '2025-04-11 16:20:22', '2025-05-15 17:10:07', '2025-04-16 15:41:03', '2025-05-15 22:10:07'),
(13, 'ava', 'ava@ava.cc', '$2y$10$ffa5RlXikDpjukQpB8lqaeZFxO/ce.jP1k17FUtUyGgqa2vYChmP6', 2, '2025-04-16 16:44:13', '2025-04-16 16:44:33', '2025-04-16 21:44:33', '2025-04-16 16:44:13');

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
-- Despejando dados para a tabela `usersgroups`
--

INSERT INTO `usersgroups` (`id`, `users_id`, `groups_id`) VALUES
(13, 11, 3),
(17, 11, 1),
(19, 1, 1),
(20, 1, 3),
(21, 13, 1);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `viewnpsdashboards`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `viewnpsdashboards` (
`dashs_id` int(11)
,`qtd` bigint(21)
,`score` decimal(6,2)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `viewusermenus`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `viewusermenus` (
`user_id` int(11)
,`user_name` varchar(255)
,`user_email` varchar(250)
,`group_id` int(11)
,`group_hash` varchar(255)
,`group_name` varchar(50)
,`group_description` text
,`group_expires` varchar(10)
,`group_status` tinyint(1)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `viewusersgroups`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `viewusersgroups` (
`usersgroups_id` int(11)
,`groups_id` int(11)
,`user_id` int(11)
,`name` varchar(255)
,`email` varchar(250)
,`pass` varchar(250)
,`group_status` tinyint(1)
,`group_expires` date
);

-- --------------------------------------------------------

--
-- Estrutura para view `viewnpsdashboards`
--
DROP TABLE IF EXISTS `viewnpsdashboards`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `viewnpsdashboards`  AS SELECT `ratings`.`dashs_id` AS `dashs_id`, count(0) AS `qtd`, round(avg(`ratings`.`score`),2) AS `score` FROM `ratings` GROUP BY `ratings`.`dashs_id` ;

-- --------------------------------------------------------

--
-- Estrutura para view `viewusermenus`
--
DROP TABLE IF EXISTS `viewusermenus`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `viewusermenus`  AS SELECT `u`.`id` AS `user_id`, `u`.`name` AS `user_name`, `u`.`email` AS `user_email`, `g`.`id` AS `group_id`, `g`.`hash` AS `group_hash`, `g`.`name` AS `group_name`, `g`.`description` AS `group_description`, date_format(`g`.`expires`,'%Y/%m/%d') AS `group_expires`, `g`.`status` AS `group_status` FROM ((`users` `u` join `usersgroups` `ug` on(`u`.`id` = `ug`.`users_id`)) join `groups` `g` on(`g`.`id` = `ug`.`groups_id`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `viewusersgroups`
--
DROP TABLE IF EXISTS `viewusersgroups`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `viewusersgroups`  AS SELECT `ug`.`id` AS `usersgroups_id`, `ug`.`groups_id` AS `groups_id`, `u`.`id` AS `user_id`, `u`.`name` AS `name`, `u`.`email` AS `email`, `u`.`pass` AS `pass`, `g`.`status` AS `group_status`, `g`.`expires` AS `group_expires` FROM ((`usersgroups` `ug` join `users` `u` on(`ug`.`users_id` = `u`.`id`)) join `groups` `g` on(`ug`.`groups_id` = `g`.`id`)) ;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fky_users_adm` (`user_id`);

--
-- Índices de tabela `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Índices de tabela `dashs`
--
ALTER TABLE `dashs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fky_pbigroupsid` (`groups_id`);

--
-- Índices de tabela `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT de tabela `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `dashs`
--
ALTER TABLE `dashs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `usersgroups`
--
ALTER TABLE `usersgroups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `fky_users_adm` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Restrições para tabelas `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `dashs`
--
ALTER TABLE `dashs`
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

-- Script SQL para criação do banco de dados do Sistema CIPA
-- Execute este script no phpMyAdmin após criar o banco 'projetocipat3'

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Criar banco de dados (descomente se necessário)
-- CREATE DATABASE IF NOT EXISTS `projetocipat3` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
-- USE `projetocipat3`;

-- Tabela de usuários
CREATE TABLE IF NOT EXISTS `usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nome_usuario` varchar(255) NOT NULL,
  `sobrenome_usuario` varchar(255) NOT NULL,
  `email_usuario` varchar(255) DEFAULT NULL,
  `senha_usuario` varchar(255) NOT NULL,
  `data_nascimento_usuario` datetime NOT NULL,
  `data_contratacao_usuario` datetime NOT NULL,
  `ativo_usuario` tinyint(1) DEFAULT 1,
  `adm_usuario` tinyint(1) DEFAULT 0,
  `matricula_usuario` varchar(50) NOT NULL,
  `cpf_usuario` varchar(14) NOT NULL,
  `telefone_usuario` varchar(20) DEFAULT NULL,
  `codigo_voto_usuario` varchar(8) DEFAULT NULL,
  `data_codigo_expiracao` datetime DEFAULT NULL,
  `ultimo_acesso_usuario` datetime DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `cpf_usuario` (`cpf_usuario`),
  UNIQUE KEY `matricula_usuario` (`matricula_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de eleições
CREATE TABLE IF NOT EXISTS `eleicao` (
  `id_eleicao` int(11) NOT NULL AUTO_INCREMENT,
  `titulo_eleicao` varchar(255) NOT NULL,
  `descricao_eleicao` text,
  `data_registro_eleicao` datetime DEFAULT CURRENT_TIMESTAMP,
  `data_inicio_eleicao` datetime NOT NULL,
  `data_fim_eleicao` datetime NOT NULL,
  `ativo_eleicao` tinyint(1) DEFAULT 1,
  `status_eleicao` varchar(50) DEFAULT 'Em espera',
  `permite_voto_branco` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id_eleicao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de candidatos
CREATE TABLE IF NOT EXISTS `candidato` (
  `id_candidato` int(11) NOT NULL AUTO_INCREMENT,
  `eleicao_fk` int(11) NOT NULL,
  `funcionario_fk` int(11) NOT NULL,
  `vice_fk` int(11) DEFAULT NULL,
  `foto_candidato` varchar(255) DEFAULT NULL,
  `numero_candidato` int(11) NOT NULL,
  `data_registro_candidato` datetime DEFAULT CURRENT_TIMESTAMP,
  `ativo_candidato` tinyint(1) DEFAULT 1,
  `status_candidato` varchar(50) DEFAULT 'Pendente',
  PRIMARY KEY (`id_candidato`),
  KEY `eleicao_fk` (`eleicao_fk`),
  KEY `funcionario_fk` (`funcionario_fk`),
  KEY `vice_fk` (`vice_fk`),
  CONSTRAINT `candidato_ibfk_1` FOREIGN KEY (`eleicao_fk`) REFERENCES `eleicao` (`id_eleicao`) ON DELETE CASCADE,
  CONSTRAINT `candidato_ibfk_2` FOREIGN KEY (`funcionario_fk`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  CONSTRAINT `candidato_ibfk_3` FOREIGN KEY (`vice_fk`) REFERENCES `usuario` (`id_usuario`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de lista de candidatos
CREATE TABLE IF NOT EXISTS `lista_candidatos` (
  `id_lista_candidato` int(11) NOT NULL AUTO_INCREMENT,
  `candidato_fk` int(11) NOT NULL,
  `eleicao_fk` int(11) NOT NULL,
  `status_lista_candidato` varchar(50) DEFAULT 'Pendente',
  `data_registro_lista_candidato` datetime DEFAULT CURRENT_TIMESTAMP,
  `quantidade_votos_lista_candidato` int(11) DEFAULT 0,
  PRIMARY KEY (`id_lista_candidato`),
  KEY `candidato_fk` (`candidato_fk`),
  KEY `eleicao_fk` (`eleicao_fk`),
  CONSTRAINT `lista_candidatos_ibfk_1` FOREIGN KEY (`candidato_fk`) REFERENCES `candidato` (`id_candidato`) ON DELETE CASCADE,
  CONSTRAINT `lista_candidatos_ibfk_2` FOREIGN KEY (`eleicao_fk`) REFERENCES `eleicao` (`id_eleicao`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de votos
CREATE TABLE IF NOT EXISTS `voto` (
  `id_voto` int(11) NOT NULL AUTO_INCREMENT,
  `funcionario_fk` int(11) NOT NULL,
  `eleicao_fk` int(11) NOT NULL,
  `lista_candidato_fk` int(11) DEFAULT NULL,
  `data_hora_voto` datetime DEFAULT CURRENT_TIMESTAMP,
  `ip_voto` varchar(45) DEFAULT NULL,
  `hash_confirmacao` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_voto`),
  KEY `funcionario_fk` (`funcionario_fk`),
  KEY `eleicao_fk` (`eleicao_fk`),
  KEY `lista_candidato_fk` (`lista_candidato_fk`),
  CONSTRAINT `voto_ibfk_1` FOREIGN KEY (`funcionario_fk`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  CONSTRAINT `voto_ibfk_2` FOREIGN KEY (`eleicao_fk`) REFERENCES `eleicao` (`id_eleicao`) ON DELETE CASCADE,
  CONSTRAINT `voto_ibfk_3` FOREIGN KEY (`lista_candidato_fk`) REFERENCES `lista_candidatos` (`id_lista_candidato`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de votos brancos/nulos
CREATE TABLE IF NOT EXISTS `branco_nulo` (
  `id_branco_nulo` int(11) NOT NULL AUTO_INCREMENT,
  `eleicao_fk` int(11) NOT NULL,
  `quantidade_branco` int(11) DEFAULT 0,
  `quantidade_nulo` int(11) DEFAULT 0,
  PRIMARY KEY (`id_branco_nulo`),
  UNIQUE KEY `eleicao_fk` (`eleicao_fk`),
  CONSTRAINT `branco_nulo_ibfk_1` FOREIGN KEY (`eleicao_fk`) REFERENCES `eleicao` (`id_eleicao`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de documentos
CREATE TABLE IF NOT EXISTS `documentos` (
  `id_documento` int(11) NOT NULL AUTO_INCREMENT,
  `eleicao_fk` int(11) DEFAULT NULL,
  `titulo_documento` varchar(255) NOT NULL,
  `tipo_documento` varchar(50) NOT NULL,
  `arquivo_documento` varchar(255) NOT NULL,
  `data_registro_documento` datetime DEFAULT CURRENT_TIMESTAMP,
  `data_inicio_documento` datetime DEFAULT NULL,
  `data_fim_documento` datetime DEFAULT NULL,
  PRIMARY KEY (`id_documento`),
  KEY `eleicao_fk` (`eleicao_fk`),
  CONSTRAINT `documentos_ibfk_1` FOREIGN KEY (`eleicao_fk`) REFERENCES `eleicao` (`id_eleicao`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserir usuário administrador padrão
-- CPF: 00000000000 | Senha: password
INSERT INTO `usuario` (
  `nome_usuario`, `sobrenome_usuario`, `email_usuario`, `senha_usuario`,
  `data_nascimento_usuario`, `data_contratacao_usuario`,
  `matricula_usuario`, `cpf_usuario`, `telefone_usuario`,
  `ativo_usuario`, `adm_usuario`, `codigo_voto_usuario`
) VALUES (
  'Admin', 'Sistema', 'admin@cipa.com', 
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
  '1990-01-01 00:00:00', '2020-01-01 00:00:00',
  '0001', '00000000000', '(00) 00000-0000',
  1, 1, 'ADMIN001'
);

COMMIT;


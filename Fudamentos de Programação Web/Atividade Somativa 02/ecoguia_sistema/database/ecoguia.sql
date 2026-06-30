-- ============================================================
-- EcoGuia - Atividade Somativa 02
-- Banco de dados: ecoguia_db
-- Relacionamento 1:N -> categorias (1) : projetos (N)
-- ============================================================

CREATE DATABASE IF NOT EXISTS ecoguia_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE ecoguia_db;

-- Tabela de categorias de projetos ambientais
CREATE TABLE categorias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  descricao TEXT,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabela de projetos (relacionamento 1:N com categorias)
CREATE TABLE projetos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150) NOT NULL,
  descricao TEXT,
  data_inicio DATE NOT NULL,
  status ENUM('planejado', 'em_andamento', 'concluido') NOT NULL DEFAULT 'planejado',
  categoria_id INT NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_projetos_categoria
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Tabela de usuarios (autenticacao)
CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Dados de exemplo: categorias
INSERT INTO categorias (nome, descricao) VALUES
('Reflorestamento', 'Projetos de plantio e recuperacao de areas verdes'),
('Reciclagem', 'Iniciativas de coleta seletiva e reaproveitamento de materiais'),
('Educacao Ambiental', 'Campanhas e oficinas de conscientizacao ecologica');

-- Dados de exemplo: projetos
INSERT INTO projetos (nome, descricao, data_inicio, status, categoria_id) VALUES
('Mata Viva', 'Plantio de 500 mudas nativas em area urbana', '2025-03-10', 'em_andamento', 1),
('EcoPonto Comunitario', 'Instalacao de pontos de coleta de plastico e papel', '2025-01-15', 'concluido', 2),
('Escola Sustentavel', 'Oficinas semanais sobre consumo consciente', '2025-06-01', 'planejado', 3);

-- Usuario administrador de demonstracao
-- E-mail: admin@ecoguia.com | Senha: password
-- Hash bcrypt gerado com password_hash('password', PASSWORD_DEFAULT)
INSERT INTO usuarios (nome, email, senha) VALUES
('Administrador', 'admin@ecoguia.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

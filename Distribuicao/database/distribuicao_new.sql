CREATE DATABASE IF NOT EXISTS distribuicao_alimentos;
USE distribuicao_alimentos;

-- =========================================
-- TABELA USUARIO
-- =========================================
CREATE TABLE usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20),
    tipo ENUM('doador','beneficiario','voluntario','deposito','admin') NOT NULL,
    cnpj_cpf VARCHAR(20),
    num_pessoas_casa INT,
    disponibilidade VARCHAR(100),
    nome_deposito VARCHAR(100),
    endereco VARCHAR(255),
    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- =========================================
-- TABELA DOADOR
-- =========================================
CREATE TABLE doador (
    id_doador INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    cnpj_cpf VARCHAR(20) NOT NULL,

    CONSTRAINT fk_doador_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES usuario(id_usuario)
        ON DELETE CASCADE
);

-- =========================================
-- TABELA BENEFICIARIO
-- =========================================
CREATE TABLE beneficiario (
    id_beneficiario INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    num_pessoas_casa INT NOT NULL,

    CONSTRAINT fk_beneficiario_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES usuario(id_usuario)
        ON DELETE CASCADE
);

-- =========================================
-- TABELA VOLUNTARIO
-- =========================================
CREATE TABLE voluntario (
    id_voluntario INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    disponibilidade VARCHAR(100),

    CONSTRAINT fk_voluntario_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES usuario(id_usuario)
        ON DELETE CASCADE
);

-- =========================================
-- TABELA DEPOSITO
-- =========================================
CREATE TABLE deposito (
    id_deposito INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    nome VARCHAR(120) NOT NULL,
    endereco VARCHAR(255) NOT NULL,
    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8),

    CONSTRAINT fk_deposito_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES usuario(id_usuario)
        ON DELETE CASCADE
);

-- =========================================
-- TABELA DOACAO
-- =========================================
CREATE TABLE doacao (
    id_doacao INT AUTO_INCREMENT PRIMARY KEY,
    id_doador INT NOT NULL,
    id_deposito INT NOT NULL,
    data_doacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    descricao TEXT,
    status ENUM('pendente', 'recebida', 'cancelada') DEFAULT 'pendente',

    CONSTRAINT fk_doacao_doador
        FOREIGN KEY (id_doador)
        REFERENCES doador(id_doador)
        ON DELETE CASCADE,

    CONSTRAINT fk_doacao_deposito
        FOREIGN KEY (id_deposito)
        REFERENCES deposito(id_deposito)
        ON DELETE CASCADE
);

-- =========================================
-- TABELA ESTOQUE
-- =========================================
CREATE TABLE estoque (
    id_estoque INT AUTO_INCREMENT PRIMARY KEY,
    id_deposito INT NOT NULL,
    nome_alimento VARCHAR(120) NOT NULL,
    quantidade DECIMAL(10,2) NOT NULL,
    unidade VARCHAR(20) NOT NULL,
    validade DATE,

    CONSTRAINT fk_estoque_deposito
        FOREIGN KEY (id_deposito)
        REFERENCES deposito(id_deposito)
        ON DELETE CASCADE
);

-- =========================================
-- TABELA ENTREGA
-- =========================================
CREATE TABLE entrega (
    id_entrega INT AUTO_INCREMENT PRIMARY KEY,
    id_voluntario INT NOT NULL,
    id_beneficiario INT NOT NULL,
    id_deposito INT NOT NULL,
    data_entrega DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pendente', 'em_transporte', 'entregue', 'cancelada') DEFAULT 'pendente',

    CONSTRAINT fk_entrega_voluntario
        FOREIGN KEY (id_voluntario)
        REFERENCES voluntario(id_voluntario)
        ON DELETE CASCADE,

    CONSTRAINT fk_entrega_beneficiario
        FOREIGN KEY (id_beneficiario)
        REFERENCES beneficiario(id_beneficiario)
        ON DELETE CASCADE,

    CONSTRAINT fk_entrega_deposito
        FOREIGN KEY (id_deposito)
        REFERENCES deposito(id_deposito)
        ON DELETE CASCADE
);

-- =========================================
-- TABELA RECEITA
-- =========================================
CREATE TABLE receita (
    id_receita INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    ingredientes TEXT NOT NULL,
    modo_preparo TEXT NOT NULL,
    id_alimento INT,

    CONSTRAINT fk_receita_estoque
        FOREIGN KEY (id_alimento)
        REFERENCES estoque(id_estoque)
        ON DELETE SET NULL
);

-- =========================================
-- DADOS EXEMPLO
-- =========================================

INSERT INTO usuario (nome, email, senha, telefone, tipo)
VALUES
('João Silva', 'joao@email.com', '$2y$10$0daGqOD4RIGT1ZU49rCrGuJ94eQ/Rp1JlDPUf2NmsdcAyyeY8qFgK', '11999999999', 'doador'),
('Maria Souza', 'maria@email.com', '$2y$10$wQnaDbH0Hbn.poBJ/STBUOaD0LPrWbli/vfBAMn/f7o8mS4qjUbDa', '11888888888', 'beneficiario'),
('Carlos Lima', 'carlos@email.com', '$2y$10$NgMXbDP4bXSYIJC8yGvbbO7FsEgNf27rZJZUIttDzH3mt3O8KLQ0C', '11777777777', 'voluntario'),
('Depósito Central', 'deposito@email.com', '$2y$10$I88CSDV15JHEcRaDjieOxOsRznFngwFNtG3h4NrAOr0WglbZ3w7wq', '11666666666', 'deposito');

INSERT INTO doador (id_usuario, cnpj_cpf)
VALUES (1, '123.456.789-00');

INSERT INTO beneficiario (id_usuario, num_pessoas_casa)
VALUES (2, 4);

INSERT INTO voluntario (id_usuario, disponibilidade)
VALUES (3, 'Segunda a Sexta - Integral');

INSERT INTO deposito (id_usuario, nome, endereco, latitude, longitude)
VALUES (
    4,
    'Depósito Central',
    'Rua das Flores, 123',
    -23.550520,
    -46.633308
);

INSERT INTO doacao (id_doador, id_deposito, descricao, status)
VALUES (
    1,
    1,
    'Doação de arroz, feijão e leite',
    'recebida'
);

INSERT INTO estoque (id_deposito, nome_alimento, quantidade, unidade, validade)
VALUES
(1, 'Arroz', 50, 'kg', '2026-12-31'),
(1, 'Feijão', 30, 'kg', '2026-10-15'),
(1, 'Leite', 100, 'litros', '2026-07-20');

INSERT INTO entrega (id_voluntario, id_beneficiario, id_deposito, status)
VALUES (
    1,
    1,
    1,
    'entregue'
);

INSERT INTO receita (titulo, ingredientes, modo_preparo, id_alimento)
VALUES (
    'Arroz com Legumes',
    'Arroz, cenoura, ervilha, alho e cebola',
    'Cozinhe o arroz e misture os legumes refogados.',
    1
);

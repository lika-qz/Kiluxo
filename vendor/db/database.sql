CREATE DATABASE kiluxo;


CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    cor VARCHAR(100), 
    cores TEXT,        
    tamanhos TEXT,   
    preco DECIMAL(10,2) NOT NULL,
    estoque INT NOT NULL,
    imagem VARCHAR(255), 
    descricao TEXT,
    status ENUM('disponivel', 'indisponivel') DEFAULT 'disponivel',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_cliente VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    endereco TEXT NOT NULL,
    metodo_pagamento ENUM('dinheiro','pix','boleto','cartao') NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE itens_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    produto_id INT NOT NULL,
    tamanho VARCHAR(50),
    cor VARCHAR(50),
    preco_unitario DECIMAL(10,2) NOT NULL,
    quantidade INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
    FOREIGN KEY (produto_id) REFERENCES produtos(id)
);


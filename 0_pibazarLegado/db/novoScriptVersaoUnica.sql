CREATE DATABASE bdecoeficiencia;

USE bdecoeficiencia;

CREATE TABLE usuarios (
idUsuario INT AUTO_INCREMENT NOT NULL,
nome VARCHAR(100) NOT NULL,
email VARCHAR(120) NOT NULL UNIQUE,
senha VARCHAR(4) NOT NULL,
perfil VARCHAR(20) NOT NULL,
coin INT NOT NULL default 0,
CONSTRAINT PRIMARY KEY(idUsuario)
);

ALTER TABLE usuarios MODIFY COLUMN senha VARCHAR(255) NOT NULL;

CREATE TABLE produtos(
idProduto INT AUTO_INCREMENT NOT NULL,
coin INT NOT NULL,
categorias VARCHAR(40) NOT NULL,
quantidade INT NOT NULL,
CONSTRAINT PRIMARY KEY(idProduto)
);

CREATE TABLE doacao(
idDoacao INT AUTO_INCREMENT NOT NULL,
idUsuario INT NOT NULL,
CONSTRAINT PRIMARY KEY(idDoacao),
CONSTRAINT fk_doacao_usuarios FOREIGN KEY(idUsuario) REFERENCES usuarios(idUsuario)
);

CREATE TABLE troca(
idTroca INT AUTO_INCREMENT NOT NULL, 
idUsuario INT NOT NULL,
CONSTRAINT PRIMARY KEY(idTroca),
CONSTRAINT fk_troca_usuarios FOREIGN KEY(idUsuario) REFERENCES usuarios(idUsuario)
);

CREATE TABLE doacaoProduto (
idDoacao INT NOT NULL,
idProduto INT NOT NULL,
quantidade INT NOT NULL,
CONSTRAINT fk_doacaoProduto_doacao FOREIGN KEY (idDoacao) REFERENCES doacao (idDoacao),
CONSTRAINT fk_doacaoProduto_produtos FOREIGN KEY (idProduto) REFERENCES produtos (idProduto)
);

CREATE TABLE trocaProduto (
idTroca INT NOT NULL,
idProduto INT NOT NULL,
quantidade INT NOT NULL,
CONSTRAINT fk_trocaProduto_troca FOREIGN KEY (idTroca) REFERENCES troca (idTroca),
CONSTRAINT fk_trocaProduto_produtos FOREIGN KEY (idProduto) REFERENCES produtos (idProduto)
);

INSERT INTO usuarios (nome, email, senha, perfil, coin) 
VALUES ('Root','ROOT.ADMIN','$2y$10$/wAxGUyNePt6VFRqh5fBQOojnMAhTlVEGWSpkbfn8GXHtQ67QbRsO','adm',0);

INSERT INTO produtos (coin, categorias, quantidade)
VALUES 
(3,'Acessórios - $ 3',0),
(3,'Livros | DVD | CD | Disco - $ 3',0),
(3,'Utensílios de Cozinha - $ 3',0),
(3,'Artigos de Decoração - $ 3',0),
(5,'Vestuário e Calçados - $ 5',0),
(5,'Brinquedos e Jogos - $ 5',0),
(6,'Artigos Automotivos - $ 6',0),
(10,'Eletrônicos e Eletrodomésticos - $ 10',0),
(10,'Mochilas - $ 10',0);



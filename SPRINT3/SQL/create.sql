CREATE TABLE usuarios ( 
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL, 
  email VARCHAR(100) UNIQUE NOT NULL, 
  senha_hash VARCHAR(255) NOT NULL, 
  data_nascimento DATE NOT NULL, 
  idade INT, genero VARCHAR(30) NOT NULL, 
  altura DECIMAL(5,2) NOT NULL, 
  peso DECIMAL(5,2) NOT NULL, 
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
  IMC DECIMAL(4,2) 

);

/* Criar nova coluna de 
A classificação do Índice de Massa Corporal (IMC) é feita de acordo com os seguintes valores: 
Abaixo do peso: IMC menor que 18,5
Eutrófico: IMC entre 18,5 e 24,9
Sobrepeso: IMC entre 25 e 29,9
Obesidade grau I: IMC entre 30 e 34,9
Obesidade grau II: IMC entre 35 e 39,9
Obesidade grau III: IMC maior que 40
*/
ALTER TABLE usuarios 
MODIFY COLUMN senha_hash VARCHAR(256) NOT NULL;

ALTER TABLE usuarios ADD COLUMN sexo ENUM('M', 'F', 'Outro') NOT NULL;

ALTER TABLE usuarios 
MODIFY COLUMN IMC DECIMAL(5,2);


CREATE TABLE hidratacao (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT,
  quantidade_ml INT,
  registrado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios (id)
);

CREATE TABLE atividades_fisicas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT,
  tipo VARCHAR(100),
  duracao_min INT,
  calorias_queimadas INT,
  registrado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios (id)
);

CREATE TABLE alimentacao (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT,
  descricao VARCHAR(255),
  calorias INT,
  registrado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios (id)
);

CREATE TABLE sentimentos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  humor VARCHAR(50),
  categoria VARCHAR(50)
);

CREATE TABLE humor (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sentimento_id INT,
  usuario_id INT,
  atividade_id INT,
  alimentacao_id INT,
  observacao TEXT,
  registrado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (sentimento_id) REFERENCES sentimentos (id),
  FOREIGN KEY (usuario_id) REFERENCES usuarios (id),
  FOREIGN KEY (atividade_id) REFERENCES atividades_fisicas (id),
  FOREIGN KEY (alimentacao_id) REFERENCES alimentacao (id)
);

CREATE TABLE alertas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT,
  mensagem VARCHAR(255),
  tipo ENUM('Água','Exercício','Alimentação','Outro'),
  horario TIME,
  ativo BOOLEAN DEFAULT TRUE,
  FOREIGN KEY (usuario_id) REFERENCES usuarios (id)
);

CREATE TABLE conteudos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(255),
  tipo ENUM('Artigo','Vídeo'),
  url TEXT,
  descricao TEXT,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE interacoes_conteudo (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT,
  conteudo_id INT,
  tipo ENUM('Visualizado','Curtido'),
  registrado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios (id),
  FOREIGN KEY (conteudo_id) REFERENCES conteudos (id)
);

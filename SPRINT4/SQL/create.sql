DROP TABLE IF EXISTS humor;
DROP TABLE IF EXISTS sentimentos;
DROP TABLE IF EXISTS atividades_fisicas;
DROP TABLE IF EXISTS tipos_atividades_fisicas;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS alimentacao;
DROP TABLE IF EXISTS alimentos;
DROP TABLE IF EXISTS alimentacao_alimentos;

/* Criar nova coluna de 
A classificação do Índice de Massa Corporal (IMC) é feita de acordo com os seguintes valores: 
Abaixo do peso: IMC menor que 18,5
Eutrófico: IMC entre 18,5 e 24,9
Sobrepeso: IMC entre 25 e 29,9
Obesidade grau I: IMC entre 30 e 34,9
Obesidade grau II: IMC entre 35 e 39,9
Obesidade grau III: IMC maior que 40
*/



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


CREATE TABLE tipos_atividades_fisicas(
  id INT AUTO_INCREMENT PRIMARY KEY,
  ds_atividade VARCHAR(100) NOT NULL 
);

CREATE TABLE atividades_fisicas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  id_tipo_atividade INT NOT NULL,
  duracao_min INT,
  calorias_queimadas INT,
  dt_plano_treino DATE NOT NULL,
  hr_plano_treino TIME,
  registrado_executado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  registrado_plano_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios (id),
  FOREIGN KEY (id_tipo_atividade) REFERENCES tipos_atividades_fisicas(id)
);
ALTER TABLE atividades_fisicas
ADD concluida BOOLEAN DEFAULT FALSE;
ALTER TABLE atividades_fisicas
MODIFY registrado_executado_em TIMESTAMP NULL;

CREATE TABLE tipos_atividades_fisicas(
  id INT AUTO_INCREMENT PRIMARY KEY,
  ds_atividade VARCHAR(100) NOT NULL -- Nessa tabela terá todos os tipos de atividades fisicas registradas
);

-- Tabela alimentação
CREATE TABLE alimentacao (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  descricao VARCHAR(255),
  registrado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE alimentos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ds_alimento VARCHAR(100) NOT NULL,
  calorias_por_100g FLOAT,        -- valor preenchido com a API
  origem_api VARCHAR(100) DEFAULT 'FatSecret',        -- ex: 'USDA', 'FatSecret'
  codigo_externo VARCHAR(50)      -- ID do alimento na API externa (facilita atualizações futuras)
);

ALTER TABLE alimentacao
ADD COLUMN ds_refeicao VARCHAR(50);

ALTER TABLE alimentacao
ADD COLUMN qt_calorias_gr INT;

ALTER TABLE alimentacao
ADD COLUMN qt_carboidratos_gr INT;

ALTER TABLE alimentacao
ADD COLUMN qt_proteinas_gr INT;

ALTER TABLE alimentacao
ADD COLUMN qt_gorduras_gr INT;

CREATE TABLE alimentacao_alimentos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  alimentacao_id INT NOT NULL,
  alimento_id INT NOT NULL,
  quantidade_g FLOAT NOT NULL,
  qt_calorias_gr INT,
  qt_carboidratos_gr INT,
  qt_proteinas_gr INT,
  qt_gorduras_gr INT,     -- peso em gramas informado pelo usuário
  FOREIGN KEY (alimentacao_id) REFERENCES alimentacao(id),
  FOREIGN KEY (alimento_id) REFERENCES alimentos(id)
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
  titulo VARCHAR(255) NOT NULL,
  tipo ENUM('Artigo', 'Vídeo', 'Plano de Treino', 'Plano Alimentar') NOT NULL,
  url TEXT,
  descricao TEXT,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  administrador_id INT NOT NULL,
  FOREIGN KEY (administrador_id) REFERENCES usuarios(id)
);

CREATE TABLE dieta(
  id INT PRIMARY KEY,
  tipo_dieta VARCHAR(20),
  titulo_dieta VARCHAR(100),
  ds_cafe VARCHAR(200),
  ds_almoco VARCHAR(200),
  ds_lanche VARCHAR(200),
  ds_jantar VARCHAR(200),
  ds_ceia VARCHAR(200),
  qt_calorias INT,
  perc_proteinas DECIMAL(4,2),
  perc_carboidrato DECIMAL(4,2),
  perc_gordura DECIMAL(4,2)
);

CREATE TABLE planos_treino (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nome_plano VARCHAR(100),        -- Ex: "Força Muscular"
  descricao_plano VARCHAR(200),   -- Ex: "Treino ABC para hipertrofia"
  duracao_min INT,
  qt_frequencia_semana INT,
  intensidade VARCHAR(20)         -- Ex: "Alta"
);

CREATE TABLE dias_treino (
  id INT PRIMARY KEY AUTO_INCREMENT,
  plano_id INT,
  nome_dia VARCHAR(50),           -- Ex: "Dia A"
  grupo_muscular VARCHAR(100),    -- Ex: "Peito e Tríceps"
  FOREIGN KEY (plano_id) REFERENCES planos_treino(id)
);

CREATE TABLE exercicios (
  id INT PRIMARY KEY AUTO_INCREMENT,
  dia_id INT,
  nome_exercicio VARCHAR(100),    -- Ex: "Supino reto"
  series_reps VARCHAR(20),        -- Ex: "4x8-10"
  FOREIGN KEY (dia_id) REFERENCES dias_treino(id)
);

CREATE TABLE interacoes_conteudo (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  conteudo_id INT NOT NULL,
  tipo ENUM('Visualizado', 'Curtido', 'Favoritado') NOT NULL,
  registrado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
  FOREIGN KEY (conteudo_id) REFERENCES conteudos(id)
);

-- Possível evolução é na aba de edução caso o usuário goste de alguma dieta ele pode adiciona-la em seu plano alimentar e todos os registros seriam feitos automaticamente ele só precisaria conclui-los.

<?php

require_once 'bancodedados.php';

$conn = mysqli_connect($db_host, $db_user, $db_password);  // inicia a conexão

if(!$conn) {  // checa se a conexão deu certo
  die("A conexão falhou: " . mysqli_connect_error());
}

$sql = "CREATE DATABASE $db_name";  // criação do banco de dados
if (mysqli_query($conn, $sql)) {
  echo "<br>O banco de dados foi criado com sucesso.<br>";
} else {
  echo "<br>Erro! Não foi possível criar o banco de dados: <br>" . mysqli_error($conn);
}

mysqli_select_db($conn, $db_name);

$table_users = 'usuario';  // tabela 'usuario' foi criada
$sql = "CREATE TABLE $table_users (
  id_usuario INT AUTO_INCREMENT PRIMARY KEY,
  user_usuario VARCHAR(50) NOT NULL UNIQUE,
  senha VARCHAR(30) NOT NULL,
  criado TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) COMMENT='Dados dos usuários armazenados.';";

$table_matches = 'partida';
$sql = "CREATE TABLE $table_matches (
  id_jogo INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL,
  pontos INT NOT NULL DEFAULT 0,
  palavras_minuto INT NOT NULL,
  ortografia DECIMAL(5, 2) NOT NULL,
  jogado TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE
)";

$table_leagues = 'liga';
$sql = "CREATE TABLE $table_leagues (
  id_liga INT AUTO_INCREMENT PRIMARY KEY,
nome VARCHAR(50) NOT NULL,
palavra_chave VARCHAR(30) NOT NULL,
id_criador_liga INT NOT NULL,
criada_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (id_criador_liga) REFERENCES usuario(id_usuario)
) COMMENT='Os dados referentes às ligas foram salvos.';";

$table_league_members = 'liga_membros';
$sql = "CREATE TABLE $table_league_members (
  id_filiacao INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL,
  id_liga INT NOT NULL,
  entrou_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE,
  FOREIGN KEY (id_liga) REFERENCES liga(id_liga) ON DELETE CASCADE,
  UNIQUE KEY id_usuario_liga(id_usuario, id_liga)
) COMMENT='Indica os usuários que criaram e estão nas ligas';";

if (mysqli_query($conn, $sql)) {
  echo "<br>A tabela foi criada com sucesso!<br>";
} else {
  echo "<br>Erro! Não foi possível criar a tabela.<br> " . mysqli_error($conn);
}

if (mysqli_query($conn, $sql_score)) {
  echo "<br>A tabela foi criada com sucesso!<br>";
} else {
  echo "<br>Erro! Não foi possível criar a tabela.<br> " . mysqli_error($conn);
}

mysqli_close($conn);

?>

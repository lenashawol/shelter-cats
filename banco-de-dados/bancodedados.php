<?php

$db_host = "localhost";
$db_name = "shelter_cats";
$db_user = "root";
$db_password = "";

if (session_status() === PHP_SESSION_NONE) {  // inicia a sessão
  session_start();
}

$mysqli = new mysqli($db_host, $db_name, $db_user, $db_password);  // conexão com mysqli

if ($mysqli->connect_error) {
  die("Erro! Não foi possível se conectar com o banco de dados: " . $mysqli->connect_error);
}

$mysqli->set_charset("utf8mb4");

?>

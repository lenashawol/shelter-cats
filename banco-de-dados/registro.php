<?php

require_once 'bancodedados.php';

$username = $_POST['user_usuario'] ?? '';
$password = $_POST['senha'] ?? '';

if (empty($username) || empty($password)) {
  header('Location: shelter-cats/banco-de-dados/registro.php?error=invalid_data');
  exit();
}

$stmt = $mysqli->prepare("INSERT INTO usuario (username, password) VALUES (?, ?)");

$stmt->bind_param("ss", $username, $password);  // duas variáveis de string

if ($stmt->execute()) {
  header('Location: shelter-cats/jogo/index.php?success=registered');
} else {
  if ($mysqli->errno === 1062) {
    header('Location: shelter-cats/jogo/registrar.php?error=user_exists');
  } else {
    die("Erro! Não foi possível registrar sua conta: " . $stmt->error);
  }
}

$stmt->close();
$mysqli->close();

?>

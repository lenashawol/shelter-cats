<?php

function check_user_logged_in() {   // verificação de login do usuário
  if (!isset($_SESSION['id_usuario'])) {
    header('Location: shelter-cats/jogo/index.php?error=denied_access');
    exit();
  }
}

function get_username_by_id($pdo, $id_usuario) {
  $stmt = $pdo->prepare("SELECT user_usuario FROM usuario WHERE id_usuario = ?");
  $stmt->execute([$id_usuario]);
  $id_usuario = $stmt->fetch();
  return $id_usuario ? $id_usuario['user_usuario'] : 'Voluntárie não encontrade';
}

?>

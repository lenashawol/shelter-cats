<?php
  session_start();

  if (isset($_SESSION['id_usuario']) && isset($_SESSION['id_liga'])) {
      $login = true;
      $id_usuario = $_SESSION['id_usuario'];
      $id_liga = $_SESSION['id_liga'];
  } else {
      if (!isset($_SESSION['id_usuario'])) {
          $login = false;
          header("Location: /index.php?error=login_falso");  // usuário não está logado
          exit();
      }
  }
?>

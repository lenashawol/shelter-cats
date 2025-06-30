<?php

require_once 'config.php';

if (!isset($_SESSION['id_usuario'])) {   // checa se 'id_usuario' existe para confirmar se o usuário está logado
  header('Location: /index.php?error=not_logged_in');  // vai para a pág de login
  exit();
}
?>

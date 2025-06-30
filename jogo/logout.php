<?php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$_SESSION = array();  // remove os dados armazenados

session_destroy();  // invalida o ID da sessão

header('Location: shelter-cats/jogo/index.php?status=logout_success');

exit();
?>

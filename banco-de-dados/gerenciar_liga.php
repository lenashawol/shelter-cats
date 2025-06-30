<?php

require_once 'shelter-cats/banco-de-dados/bancodedados.php';
require_once 'shelter-cats/banco-de-dados/funcs.php';
check_user_logged_in();

$action = $_POST['action'] ?? '';
if (empty($action)) {
  header('Location: shelter-cats/jogo/ligas.php?error=invalid_action');
  exit();
}

$id_usuario = $_SESSION['id_usuario'];

switch ($action) {  // para criar uma liga
  case 'create':
  $league_name = trim($_POST['nome'] ?? '');
  $league_keyword = trim($_POST['palavra_chave'] ?? '');

  if (empty($league_name) || empty($league_keyword)) {
    header('Location: shelter-cats/jogo/ligas.php?error=empty_fields');
    exit();
  }

  // inserção da nova liga + seu ID
  $stmt_create = $pdo->prepare("INSERT INTO liga (nome, palavra_chave, id_criador_liga) VALUES (?, ?, ?)");
  $stmt_create->execute([$league_name, $league_keyword, $id_usuario]);
  $new_league_id = $pdo->lastInsertId();

  // adc o criador da liga como primeiro membro dela
  $stmt_join = $pdo->prepare("INSERT INTO liga_membros (id_usuario, id_liga) VALUES (?, ?)");
  $stmt_join->execute("[$id_usuario, $new_league_id]");

  header('Location: shelter-cats/jogo/ligas.php?success=league_created');
  break;

  // para entrar em uma liga
  case 'join':
  $league_id = $_POST['id_liga'] ?? 0;
  $keyword_attempt = $_POST['keyword_attempt'] ?? '';

  // busca da palavra-chave referente à liga
  $stmt_check = $pdo->prepare("SELECT palavra_chave FROM liga WHERE id_liga = ?");
  $stmt_check->execute([$league_id]);
  $league = $stmt_check->fetch();

  if ($league && $league['palavra_chave'] === $keyword_attempt) {
    try {   // para colocar o usuário como um dos membros da liga
      $stmt_join = $pdo->prepare("INSERT INTO liga_membros (id_usuario, id_liga) VALUES (?, ?)");
      $stmt_join->execute([$id_usuario, $league_id]);
      header('Location: shelter-cats/jogo/ligas.php?success=joined_league');
    } catch (PDOException $e) {  // erro caso o usuário já seja membro da liga
      if ($e->getCode() == 23000) {
        header('Location: shelter-cats/jogo/ligas.php?error=already_member');
      } else {
        header('Location: shelter-cats/jogo/ligas.php?error=db_error');
      }
    }
  } else {
    header('Location: shelter-cats/jogo/ligas.php?error=wrong_keyword');
  }
  break;

  // para sair de uma liga
  case 'leave':
  $league_id = $_POST['id_liga'] ?? 0;

  $stmt = $pdo->prepare("DELETE FROM liga_membros WHERE id_usuario = ? AND id_liga = ?");
  $stmt->execute([$id_usuario, $league_id]);

  header('Location: shelter-cats/jogo/ligas.php?success=left_league');
  break;

  // para excluir uma liga
  case 'delete':
  $league_id = $_POST['id_liga'] ?? 0;

  // verifica se o usuário é o criador da liga
  $stmt_check = $pdo->prepare("SELECT id_criador_liga FROM liga WHERE id_liga = ?");
  $stmt_check->execute([$league_id]);
  $league = $stmt_check->fetch();

  if ($league && $league['id_criador_liga'] == $id_usuario) {
    $stmt_delete = $pdo->prepare("DELETE FROM liga WHERE id_liga = ?");
    $stmt_delete->execute([$league_id]);

    header('Location: shelter-cats/jogo/ligas.php?success=league_deleted');
  } else {
    header('Location: shelter-cats/jogo/ligas.php?error=unauthorized');
  }
  break;

  default:
  header('Location: shelter-cats/jogo/ligas.php?error=unknown_action');
  break;
}

exit();
?>

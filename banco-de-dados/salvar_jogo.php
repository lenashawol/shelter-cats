<?php

require_once 'shelter-cats/banco-de-dados/check_authentication.php';

header('Content-Type: application/json');

$score = $_POST['pontos'] ?? 0;
$wpm = $_POST['palavras_minuto'] ?? 0;
$accuracy = $_POST['ortografia'] ?? 0;
$id_usuario = $_POST['id_usuario'];

if ($score <= 0 || $wpm < 0 || $accuracy < 0 || $accuracy > 100) {
  echo json_encode(['success' => false, 'error' => 'Os dados são inválidos.']);
  exit();
}

try {
  $stmt = $pdo->prepare(
    "INSERT INTO jogo (id_usuario, pontos, palavras_minuto, ortografia, played_at) VALUES (?, ?, ?, ?, NOW())"
  );
  $stmt->execute([$id_usuario, $score, $wpm, $accuracy]);

  echo json_encode([
    'success' => true,
    'message' => 'A partida foi salva!',
    'new_game_score' => $score
  ]);

} catch (PDOException $e) {
  echo json_encode(['success' => false, 'error' => 'Erro! Não foi possível salvar a partida.']);
}
?>

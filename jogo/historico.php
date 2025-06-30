<?php

require_once 'shelter-cats/banco-de-dados/bancodedados.php';
require_once 'shelter-cats/banco-de-dados/funcs.php';
check_user_logged_in();

$id_usuario = $_SESSION['id_usuario'];

$stmt = $pdo->prepare("SELECT pontos, jogado FROM partida WHERE id_usuario = ? ORDER BY jogado DESC");
$stmt->execute([$id_usuario]);
$matches = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <title>Histórico de Partidas</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="container m-4">
    <h1>Histórico de Voluntariado</h1>
    <a href="inicio.php">Voltar ao Painel</a>
    <hr>
    <table class="table table-stripped table-hover">
      <thead>
        <tr>
          <th>Data e Hora</th>
          <th>Pontuação</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($matches)): ?>
          <tr>
            <td colspan="2" class="text-center">Você ainda não jogou nenhuma partida. Os gatinhos estão te esperando, vamos!</td>
          </tr>
        <?php else: ?>
          <?php foreach ($matches as $match): ?>
            <tr>
              <td><?php echo date('d/m/y H:i:s', strtotime($match['jogado'])); ?></td>
              <td><?php echo $match['pontos']; ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
  </table>
</div>
</body>
</html>

<?php

require_once 'shelter-cats/banco-de-dados/bancodedados.php';
require_once 'shelter-cats/banco-de-dados/funcs.php';
check_user_logged_in();

$scope = $_GET['scope'] ?? 'general';  //parâmetros ou valores padronizados
$period = $_GET['period'] ?? 'weekly';
$league_id = isset($_GET['id_liga']) ? (int)$_GET['id_liga'] : null;

$page_title = "Quadro de Pontos";  // título da página
$page_title .= ($scope === 'general') ? " Geral" : " da Liga";
$page_title .= ($period === 'weekly') ? " - Semanal" : " - Total";

$sql = "SELECT u.id_usuario, SUM(g.pontos) as total_score FROM partida g JOIN id_usuario u ON g.id_usuario = u.id_usuario";  // query SQL
$params = [];

if ($scope === 'id_liga') {
  if(!$league_id) {
    die("Erro! O ID da Liga não foi informado.");
  }
  $sql .= "JOIN liga_membros lm ON u.id_usuario = lm.id_usuario_liga WHERE lm.id_usuario_liga = ?";
  $params[] = $league_id;

  $stmt_league_name = $pdo->prepare("SELECT nome FROM liga WHERE id_liga = ?");  // att do título de acordo com o nome da liga correspondente
  $stmt_league_name->execute([$league_id]);
  $league_name_result = $stmt_league_name->fetch();
  if ($league_name_result) {
    $page_title = "Quadro de Pontos: " . htmlspecialchars($league_name_result['nome']);
    $page_title .= ($period === 'weekly') ? " - Semanal" : " - Total";
  }
}

if ($period === 'weekly') {  // add tempo
  $sql .= ($scope === 'id_liga' ? " AND" : " WHERE") . " g.jogado >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
}

$sql .= " GROUP BY u.id_usuario, u.user_usuario ORDER BY total_score DESC LIMIT 20";

$stmt = $pdo->prepare($sql);  // execução da query
$stmt->execute($params);
$leaderboard_data = $stmt->fetchAll();

// busca as ligas do usuário
$stmt_my_leagues = $pdo->prepare("SELECT l.id_liga, l.nome FROM liga l JOIN liga_membros lm ON l.id_liga = lm.id_liga WHERE lm.id_usuario = ?");
$stmt_my_leagues->execute([$_SESSION['id_usuario']]);
$my_leagues = $stmt_my_leagues->fetchAll();

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title><?php echo $page_title; ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="container m-4">
    <h1><?php echo $page_title; ?></h1>
    <a href="inicio.php">Voltar ao Painel</a>
    <hr>
    <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded">
      <div>
        <strong>Ver Placar:</strong>
        <a href="pontos.php?scope=general&period=<?php echo $period; ?>" class="btn btn-<?php echo ($scope === 'general' ? 'primary' : 'outline-primary'); ?>">Geral</a>
        <select class="form-select d-inline-block w-auto" onchange="if(this.value) window.location.href=this.value;">
          <option value="">Ver placar de uma liga</option>
          <?php foreach($my_leagues as $league): ?>
            <option value="pontos.php?scope=league&league_id=<?php echo $league['id_liga']; ?>&period=<?php echo $period; ?>" <?php echo ($league_id === $league['id_liga'] ? 'selected' : ''); ?>>
              <?php echo htmlspecialchars($league['nome']); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <strong>Período:</strong>
        <a href="pontos.php?scope=<?php echo $scope;?><?php if($league_id) echo '&league_id=' .$league_id; ?>&period=weekly" class="btn btn-<?php echo ($period === 'weekly' ? 'info' : 'outline-info'); ?>">Semanal</a>
        <a href="pontos.php?scope=<?php echo $scope;?><?php if($league_id) echo '&league_id=' .$league_id; ?>&period=alltime" class="btn btn-<?php echo ($period === 'alltime' ? 'info' : 'outline-info'); ?>">Total</a>
      </div>
    </div>

    <table class="table table-stripped table-bordered">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Nome do Voluntárie</th>
          <th>Pontuação</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($leaderboard_data)): ?>
          <tr>
            <td colspan="3" class="text-center">Nenhuma pessoa pontuou nessa tabela até o momento. Os gatinhos estão esperando!</td>
          </tr>
        <?php else: ?>
          <?php foreach($leaderboard_data as $index => $row): ?>
            <tr>
              <td><?php echo $index + 1; ?></td>
              <td><?php echo htmlspecialchars($row['user_usuario']); ?></td>
              <td><?php echo $row['total_score']; ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

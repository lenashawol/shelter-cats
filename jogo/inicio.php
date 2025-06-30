<?php
 require 'shelter-cats/banco-de-dados/authenticate.php';
 require_once 'shelter-cats/check_authentication.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Shelter Cats - Home</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="shelter-cats/css/style.css">
</head>
<body>
  <div class="container mt-4">
    <h1>Bem-vinde, Voluntárie <?php echo htmlspecialchars($_SESSION['user_usuario']); ?>!</h1>
    <p>Os gatinhos do abrigo precisam de você. Está preparade?</p>

    <a href="jogo.php" class="btn btn-success btn-lg">Bora se voluntariar (Jogar)</a>
    <a href="ligas.php" class="btn btn-info">Ver Ligas</a>
    <a href="shelter-cats/jogo/logout.php" class="btn btn-danger">Sair</a>

    <div class="row mt-5">
      <div class="col-md-6">
        <h2>Quadro Geral com Pontuação Semanal</h2>
        <ul id="general-leaderboard" class="list-group">
          <li class="list-group-item">Carregando...</li>
        </ul>
      </div>
      <div class="col-md-6">
        <h3>Histórico de partidas</h3>
        <ul id="user-history" class="list-group">
          <li class="list-group-item">Carregando...</li>
        </ul>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.7.0.min.js"</script>
  <script src="js/jogo.js"</script>
</body>
</html>

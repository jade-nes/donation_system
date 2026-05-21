<?php
require_once("auth.php"); // garante que está logado

include("../config/db.php");
require_once("../classes/Receita.php");
require_once("../classes/Beneficiario.php");

// permite beneficiário OU admin
if ($_SESSION["tipo"] !== "beneficiario" && $_SESSION["tipo"] !== "admin") {
    header("Location: dashboard.php");
    exit();
}

$usuario = $_SESSION["usuario"];
$beneficiarioService = new Beneficiario($conn);
$estoque = $beneficiarioService->consultarDepositos();
$pontos = $beneficiarioService->localizarPontos();
$receitas = $beneficiarioService->verReceitas();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Beneficiário - Alimentos Disponíveis</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="d-flex">
  <div class="sidebar bg-primary text-white p-3">
    <h3 class="mb-4">Distribuição</h3>
    <ul class="nav flex-column">
      <li class="nav-item mb-2"><a href="dashboard.php" class="nav-link text-white">Início</a></li>
      <li class="nav-item mb-2"><a href="beneficiario.php" class="nav-link text-white">Alimentos Disponíveis</a></li>
      <li class="nav-item mt-4"><a href="logout.php" class="btn btn-light w-100">Sair</a></li>
    </ul>
  </div>

  <div class="content flex-grow-1 p-4">
    <h2>Bem-vindo, <?php echo htmlspecialchars($usuario); ?>!</h2>
    <p class="lead">Consulte os alimentos cadastrados nos depósitos.</p>

    <!-- Alimentos -->
    <div class="card p-3 shadow-sm mt-4">
      <h5>Alimentos Disponíveis</h5>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Alimento</th><th>Quantidade</th><th>Unidade</th><th>Validade</th><th>Depósito</th><th>Endereço</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($estoque)): ?>
            <?php foreach ($estoque as $row): ?>
              <tr>
                <td><?php echo htmlspecialchars($row["nome_alimento"]); ?></td>
                <td><?php echo htmlspecialchars($row["quantidade"]); ?></td>
                <td><?php echo htmlspecialchars($row["unidade"]); ?></td>
                <td><?php echo htmlspecialchars($row["validade"] ?? ""); ?></td>
                <td><?php echo htmlspecialchars($row["deposito"]); ?></td>
                <td><?php echo htmlspecialchars($row["endereco"]); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="6" class="text-center">Nenhum alimento disponível no momento.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Pontos -->
    <div class="card p-3 shadow-sm mt-4">
      <h5>Pontos de Distribuição</h5>
      <table class="table table-striped">
        <thead>
          <tr><th>Depósito</th><th>Endereço</th><th>Latitude</th><th>Longitude</th></tr>
        </thead>
        <tbody>
          <?php if (!empty($pontos)): ?>
            <?php foreach ($pontos as $ponto): ?>
              <tr>
                <td><?php echo htmlspecialchars($ponto["nome"]); ?></td>
                <td><?php echo htmlspecialchars($ponto["endereco"]); ?></td>
                <td><?php echo htmlspecialchars($ponto["latitude"]); ?></td>
                <td><?php echo htmlspecialchars($ponto["longitude"]); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="4" class="text-center">Nenhum ponto com localização cadastrada.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Receitas -->
    <div class="card p-3 shadow-sm mt-4">
      <h5>Receitas</h5>
      <div class="row">
        <?php if (!empty($receitas)): ?>
          <?php foreach ($receitas as $receita): ?>
            <div class="col-md-6 mb-3">
              <div class="border rounded p-3 h-100 bg-white">
                <h6><?php echo htmlspecialchars($receita["titulo"]); ?></h6>
                <p><strong>Ingredientes:</strong> <?php echo htmlspecialchars($receita["ingredientes"]); ?></p>
                <p class="mb-0"><strong>Preparo:</strong> <?php echo htmlspecialchars($receita["modo_preparo"]); ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="text-center mb-0">Nenhuma receita cadastrada.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<footer class="text-center mt-5">
  <img src="../assets/logo.png" alt="Distribuição" class="logo-footer">
</footer>

</body>
</html>

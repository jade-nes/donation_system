<?php
require_once("auth.php");

include("../config/db.php");
require_once("../classes/Receita.php");
require_once("../classes/Beneficiario.php");

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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../assets/style.css">
  <style>
    .sidebar {
      position: fixed; top: 0; left: 0;
      width: 230px; height: 100vh;
      display: flex; flex-direction: column;
      overflow-y: auto; z-index: 100;
    }
    .sidebar ul { display: flex; flex-direction: column; flex: 1; }
    .main-content { margin-left: 230px; padding: 2rem; }
  </style>
</head>
<body>

<div class="sidebar bg-primary text-white p-3">
  <h3 class="mb-4">Distribuição</h3>
  <ul class="nav flex-column flex-grow-1">
    <li class="nav-item mb-2"><a href="dashboard.php" class="nav-link text-white"><i class="bi bi-house-door me-2"></i>Início</a></li>
    <li class="nav-item mb-2"><a href="beneficiario.php" class="nav-link text-white"><i class="bi bi-basket me-2"></i>Alimentos Disponíveis</a></li>
    <li class="nav-item mb-2"><a href="editar.php?id=<?php echo $_SESSION['id_usuario']; ?>" class="nav-link text-white"><i class="bi bi-pencil-square me-2"></i>Editar Cadastro</a></li>
    <li style="margin-top:auto; padding-top:1rem;">
      <a href="logout.php" class="btn btn-light w-100"><i class="bi bi-box-arrow-right me-2"></i>Sair</a>
    </li>
  </ul>
</div>

<div class="main-content">
  <h2>Bem-vindo, <?php echo htmlspecialchars($usuario); ?>!</h2>
  <p class="lead">Consulte os alimentos cadastrados nos depósitos.</p>

  <div class="card p-3 shadow-sm mt-4">
    <h5>Alimentos Disponíveis</h5>
    <table class="table table-striped">
      <thead><tr><th>Alimento</th><th>Quantidade</th><th>Unidade</th><th>Validade</th><th>Depósito</th><th>Endereço</th></tr></thead>
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

  <div class="card p-3 shadow-sm mt-4">
    <h5>Pontos de Distribuição</h5>
    <table class="table table-striped">
      <thead><tr><th>Depósito</th><th>Endereço</th><th>Latitude</th><th>Longitude</th></tr></thead>
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

<footer class="mt-5" style="margin-left:230px; text-align:center;">
  <img src="../assets/logo.png" alt="Distribuição" class="logo-footer">
</footer>

</body>
</html>

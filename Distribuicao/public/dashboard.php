<?php
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit();
}

$usuario = $_SESSION["usuario"];
$tipo = $_SESSION["tipo"];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Distribuicao</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="d-flex">
  <div class="sidebar bg-primary text-white p-3">
    <h3 class="mb-4">Distribuicao</h3>
    <ul class="nav flex-column">
      <li class="nav-item mb-2"><a href="dashboard.php" class="nav-link text-white">Inicio</a></li>

      <?php if ($tipo == "doador"): ?>
        <li class="nav-item mb-2"><a href="doador.php" class="nav-link text-white">Registrar Doacao</a></li>
      <?php elseif ($tipo == "beneficiario"): ?>
        <li class="nav-item mb-2"><a href="beneficiario.php" class="nav-link text-white">Alimentos Disponiveis</a></li>
      <?php elseif ($tipo == "voluntario"): ?>
        <li class="nav-item mb-2"><a href="voluntario.php" class="nav-link text-white">Entregas</a></li>
      <?php elseif ($tipo == "deposito"): ?>
        <li class="nav-item mb-2"><a href="deposito.php" class="nav-link text-white">Gestao de Estoque</a></li>
      <?php endif; ?>

      <li class="nav-item mb-2"><a href="editar.php" class="nav-link text-white">Editar Cadastro</a></li>
      <li class="nav-item mt-4"><a href="logout.php" class="btn btn-light w-100">Sair</a></li>
    </ul>
  </div>

  <div class="content flex-grow-1 p-4">
    <h2>Bem-vindo, <?php echo htmlspecialchars($usuario); ?>!</h2>
    <p class="lead">Voce esta logado como <strong><?php echo htmlspecialchars(ucfirst($tipo)); ?></strong>.</p>

    <div class="row mt-4">
      <?php if ($tipo == "doador"): ?>
        <div class="col-md-4">
          <div class="card p-3 shadow-sm">
            <h5>Registrar Doacao</h5>
            <p>Cadastre novas doacoes de alimentos.</p>
            <a href="doador.php" class="btn btn-primary">Acessar</a>
          </div>
        </div>
      <?php elseif ($tipo == "beneficiario"): ?>
        <div class="col-md-4">
          <div class="card p-3 shadow-sm">
            <h5>Alimentos Disponiveis</h5>
            <p>Consulte alimentos cadastrados nos depositos.</p>
            <a href="beneficiario.php" class="btn btn-primary">Acessar</a>
          </div>
        </div>
      <?php elseif ($tipo == "voluntario"): ?>
        <div class="col-md-4">
          <div class="card p-3 shadow-sm">
            <h5>Entregas</h5>
            <p>Acompanhe e registre entregas realizadas.</p>
            <a href="voluntario.php" class="btn btn-primary">Acessar</a>
          </div>
        </div>
      <?php elseif ($tipo == "deposito"): ?>
        <div class="col-md-4">
          <div class="card p-3 shadow-sm">
            <h5>Gestao de Estoque</h5>
            <p>Gerencie os alimentos armazenados no deposito.</p>
            <a href="deposito.php" class="btn btn-primary">Acessar</a>
          </div>
        </div>
      <?php endif; ?>

      <div class="col-md-4">
        <div class="card p-3 shadow-sm">
          <h5>Editar Cadastro</h5>
          <p>Atualize suas informacoes pessoais e de acesso.</p>
          <a href="editar.php" class="btn btn-secondary">Editar</a>
        </div>
      </div>
    </div>
  </div>
</div>

<footer class="text-center mt-5">
  <img src="../assets/logo.png" alt="Distribuicao" class="logo-footer">
</footer>

</body>
</html>

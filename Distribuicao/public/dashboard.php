<?php 
session_start();
if(!isset($_SESSION["usuario"])) {
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
  <title>Dashboard - Distribuição</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="d-flex">
  <!-- Menu lateral -->
  <div class="sidebar bg-primary text-white p-3">
    <h3 class="mb-4">Distribuição</h3>
    <ul class="nav flex-column">
      <li class="nav-item mb-2"><a href="dashboard.php" class="nav-link text-white">🏠 Início</a></li>
      
      <?php if($tipo == "doador"): ?>
        <li class="nav-item mb-2"><a href="doador.php" class="nav-link text-white">📦 Registrar Doação</a></li>
      <?php elseif($tipo == "beneficiario"): ?>
        <li class="nav-item mb-2"><a href="beneficiario.php" class="nav-link text-white">🛒 Solicitar Alimentos</a></li>
      <?php elseif($tipo == "voluntario"): ?>
        <li class="nav-item mb-2"><a href="voluntario.php" class="nav-link text-white">🚚 Entregas</a></li>
      <?php elseif($tipo == "deposito"): ?>
        <li class="nav-item mb-2"><a href="deposito.php" class="nav-link text-white">🏢 Gestão de Estoque</a></li>
      <?php endif; ?>

      <!-- Nova opção de edição -->
      <li class="nav-item mb-2"><a href="editar.php" class="nav-link text-white">⚙️ Editar Cadastro</a></li>

      <li class="nav-item mt-4"><a href="logout.php" class="btn btn-light w-100">Sair</a></li>
    </ul>
  </div>

  <!-- Área principal -->
  <div class="content flex-grow-1 p-4">
    <h2>Bem-vindo, <?php echo $usuario; ?>!</h2>
    <p class="lead">Você está logado como <strong><?php echo ucfirst($tipo); ?></strong>.</p>

    <div class="row mt-4">
      <?php if($tipo == "doador"): ?>
        <div class="col-md-4">
          <div class="card p-3 shadow-sm">
            <h5>📦 Registrar Doação</h5>
            <p>Cadastre novas doações de alimentos.</p>
            <a href="doador.php" class="btn btn-primary">Acessar</a>
          </div>
        </div>
      <?php elseif($tipo == "beneficiario"): ?>
        <div class="col-md-4">
          <div class="card p-3 shadow-sm">
            <h5>🛒 Solicitar Alimentos</h5>
            <p>Solicite alimentos disponíveis para retirada.</p>
            <a href="beneficiario.php" class="btn btn-primary">Acessar</a>
          </div>
        </div>
      <?php elseif($tipo == "voluntario"): ?>
        <div class="col-md-4">
          <div class="card p-3 shadow-sm">
            <h5>🚚 Entregas</h5>
            <p>Acompanhe e registre entregas realizadas.</p>
            <a href="voluntario.php" class="btn btn-primary">Acessar</a>
          </div>
        </div>
      <?php elseif($tipo == "deposito"): ?>
        <div class="col-md-4">
          <div class="card p-3 shadow-sm">
            <h5>🏢 Gestão de Estoque</h5>
            <p>Gerencie os alimentos armazenados no depósito.</p>
            <a href="deposito.php" class="btn btn-primary">Acessar</a>
          </div>
        </div>
      <?php endif; ?>

      <!-- Card de edição de cadastro -->
      <div class="col-md-4">
        <div class="card p-3 shadow-sm">
          <h5>⚙️ Editar Cadastro</h5>
          <p>Atualize suas informações pessoais e de acesso.</p>
          <a href="editar.php" class="btn btn-secondary">Editar</a>
        </div>
      </div>
    </div>
  </div>
</div>

<footer class="text-center mt-5">
  <img src="../assets/logo.png" alt="Distribuição" class="logo-footer">
</footer>

</body>
</html>

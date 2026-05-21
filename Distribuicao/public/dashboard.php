<?php 
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit();
}

$usuario = $_SESSION["usuario"];
$tipo = $_SESSION["tipo"];
$id = $_SESSION["id_usuario"];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Distribuição</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/style.css">
  <style>
    .card {
      min-height: 200px;             /* altura mínima igual */
      display: flex;
      flex-direction: column;
      justify-content: space-between; /* botão sempre embaixo */
    }
    .nav-link {
      font-size: 1rem;
    }
  </style>
</head>
<body>

<div class="d-flex">
  <!-- Sidebar lateral -->
  <div class="sidebar bg-primary text-white p-3">
    <h3 class="mb-4">Distribuição</h3>
    <ul class="nav flex-column">
      <li class="nav-item mb-2"><a href="dashboard.php" class="nav-link text-white">Início</a></li>

      <?php if ($tipo == "admin"): ?>
        <li class="nav-item mb-2"><a href="doador.php" class="nav-link text-white">Registrar Doação</a></li>
        <li class="nav-item mb-2"><a href="beneficiario.php" class="nav-link text-white">Alimentos Disponíveis</a></li>
        <li class="nav-item mb-2"><a href="voluntario.php" class="nav-link text-white">Entregas</a></li>
        <li class="nav-item mb-2"><a href="deposito.php" class="nav-link text-white">Gestão de Estoque</a></li>
        <li class="nav-item mb-2"><a href="usuario.php" class="nav-link text-white">Gestão de Usuários</a></li>
      <?php elseif ($tipo == "doador"): ?>
        <li class="nav-item mb-2"><a href="doador.php" class="nav-link text-white">Registrar Doação</a></li>
      <?php elseif ($tipo == "beneficiario"): ?>
        <li class="nav-item mb-2"><a href="beneficiario.php" class="nav-link text-white">Solicitar Alimentos</a></li>
      <?php elseif ($tipo == "voluntario"): ?>
        <li class="nav-item mb-2"><a href="voluntario.php" class="nav-link text-white">Entregas</a></li>
      <?php elseif ($tipo == "deposito"): ?>
        <li class="nav-item mb-2"><a href="deposito.php" class="nav-link text-white">Gestão de Estoque</a></li>
      <?php endif; ?>

      <li class="nav-item mb-2"><a href="editar.php?id=<?php echo $id; ?>" class="nav-link text-white">Editar Cadastro</a></li>
      <li class="nav-item mt-4"><a href="logout.php" class="btn btn-light w-100">Sair</a></li>
    </ul>
  </div>

  <!-- Conteúdo principal -->
  <div class="content flex-grow-1 p-4">
    <h2>Bem-vindo, <?php echo htmlspecialchars($usuario); ?>!</h2>
    <p class="lead">Você está logado como <strong><?php echo htmlspecialchars(ucfirst($tipo)); ?></strong>.</p>

    <div class="row mt-4 g-3">
      <?php if ($tipo == "admin"): ?>
        <div class="col-md-6 col-lg-4">
          <div class="card p-3 shadow-sm">
            <h5>Registrar Doação</h5>
            <p>Cadastre novas doações de alimentos.</p>
            <a href="doador.php" class="btn btn-primary mt-auto">Acessar</a>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="card p-3 shadow-sm">
            <h5>Alimentos Disponíveis</h5>
            <p>Consulte alimentos disponíveis nos depósitos.</p>
            <a href="beneficiario.php" class="btn btn-primary mt-auto">Acessar</a>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="card p-3 shadow-sm">
            <h5>Entregas</h5>
            <p>Acompanhe e registre entregas realizadas.</p>
            <a href="voluntario.php" class="btn btn-primary mt-auto">Acessar</a>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="card p-3 shadow-sm">
            <h5>Gestão de Estoque</h5>
            <p>Gerencie os alimentos armazenados no depósito.</p>
            <a href="deposito.php" class="btn btn-primary mt-auto">Acessar</a>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="card p-3 shadow-sm">
            <h5>Gestão de Usuários</h5>
            <p>Visualize e gerencie todos os cadastrados.</p>
            <a href="usuario.php" class="btn btn-primary mt-auto">Acessar</a>
          </div>
        </div>
      <?php elseif ($tipo == "doador"): ?>
        <div class="col-md-6 col-lg-4">
          <div class="card p-3 shadow-sm">
            <h5>Registrar Doação</h5>
            <p>Cadastre novas doações de alimentos.</p>
            <a href="doador.php" class="btn btn-primary mt-auto">Acessar</a>
          </div>
        </div>
      <?php elseif ($tipo == "beneficiario"): ?>
        <div class="col-md-6 col-lg-4">
          <div class="card p-3 shadow-sm">
            <h5>Alimentos Disponíveis</h5>
            <p>Consulte alimentos cadastrados nos depósitos.</p>
            <a href="beneficiario.php" class="btn btn-primary mt-auto">Acessar</a>
          </div>
        </div>
      <?php elseif ($tipo == "voluntario"): ?>
        <div class="col-md-6 col-lg-4">
          <div class="card p-3 shadow-sm">
            <h5>Entregas</h5>
            <p>Acompanhe e registre entregas realizadas.</p>
            <a href="voluntario.php" class="btn btn-primary mt-auto">Acessar</a>
          </div>
        </div>
      <?php elseif ($tipo == "deposito"): ?>
        <div class="col-md-6 col-lg-4">
          <div class="card p-3 shadow-sm">
            <h5>Gestão de Estoque</h5>
            <p>Gerencie os alimentos armazenados no depósito.</p>
            <a href="deposito.php" class="btn btn-primary mt-auto">Acessar</a>
          </div>
        </div>
      <?php endif; ?>

      <!-- Card de edição de cadastro -->
      <div class="col-md-6 col-lg-4">
        <div class="card p-3 shadow-sm">
          <h5>Editar Cadastro</h5>
          <p>Atualize suas informações pessoais e de acesso.</p>
          <a href="editar.php?id=<?php echo $id; ?>" class="btn btn-secondary mt-auto">Editar</a>
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

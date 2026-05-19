<?php 
include("../config/db.php"); 
session_start();
if(!isset($_SESSION["usuario"]) || $_SESSION["tipo"] != "voluntario") {
    header("Location: login.php");
    exit();
}
$usuario = $_SESSION["usuario"];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Voluntário - Entregas</title>
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
      <li class="nav-item mb-2"><a href="voluntario.php" class="nav-link text-white">🚚 Registrar Entrega</a></li>
      <li class="nav-item mt-4"><a href="logout.php" class="btn btn-light w-100">Sair</a></li>
    </ul>
  </div>

  <!-- Área principal -->
  <div class="content flex-grow-1 p-4">
    <h2>Bem-vindo, <?php echo $usuario; ?>!</h2>
    <p class="lead">Aqui você pode registrar e acompanhar entregas realizadas.</p>

    <div class="card p-4 shadow-sm mt-3">
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Beneficiário</label>
          <input type="text" name="beneficiario" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Alimento</label>
          <input type="text" name="alimento" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Quantidade</label>
          <input type="number" name="quantidade" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Local de Entrega</label>
          <input type="text" name="local" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Registrar Entrega</button>
      </form>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $beneficiario = $_POST["beneficiario"];
        $alimento = $_POST["alimento"];
        $quantidade = $_POST["quantidade"];
        $local = $_POST["local"];
        $voluntario = $_SESSION["usuario"];

        $sql = "INSERT INTO entregas (voluntario, beneficiario, alimento, quantidade, local) 
                VALUES ('$voluntario','$beneficiario','$alimento','$quantidade','$local')";
        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert alert-success mt-3'>Entrega registrada com sucesso!</div>";
        } else {
            echo "<div class='alert alert-danger mt-3'>Erro: " . $conn->error . "</div>";
        }
    }
    ?>
  </div>
</div>

<footer class="text-center mt-5">
  <img src="../assets/logo.png" alt="Distribuição" class="logo-footer">
</footer>

</body>
</html>

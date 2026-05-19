<?php 
include("../config/db.php"); 
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login - Distribuição</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="container mt-5">
  <div class="card shadow-lg p-4" style="max-width: 400px; margin: auto;">
    <h2 class="text-center mb-4">Acesso ao Sistema</h2>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Senha</label>
        <input type="password" name="senha" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Entrar</button>
    </form>
    <div class="text-center mt-3">
      <a href="cadastro.php" class="text-decoration-none">Ainda não tem conta? Cadastre-se</a>
    </div>
  </div>
</div>

<footer class="text-center mt-5">
  <img src="../assets/logo.png" alt="Distribuição" class="logo-footer">
</footer>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    $sql = "SELECT * FROM usuario WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($senha, $user["senha"])) {
            $_SESSION["usuario"] = $user["nome"];
            $_SESSION["tipo"] = $user["tipo"];
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<div class='alert alert-danger text-center mt-3'>Senha incorreta.</div>";
        }
    } else {
        echo "<div class='alert alert-warning text-center mt-3'>Usuário não encontrado.</div>";
    }
}
?>
</body>
</html>

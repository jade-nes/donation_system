<?php
include("../config/db.php");
require_once("../classes/Usuario.php");
session_start();

$mensagem = "";
$tipoMensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $senha = $_POST["senha"];
    $usuarioService = new Usuario($conn);
    $user = $usuarioService->login($email, $senha);

    if ($user) {
        $_SESSION["usuario"] = $user["nome"];
        $_SESSION["id_usuario"] = $user["id_usuario"];
        $_SESSION["tipo"] = $user["tipo"];

        header("Location: dashboard.php");
        exit();
    } else {
        $mensagem = "Email ou senha invalidos.";
        $tipoMensagem = "warning";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login - Distribuicao</title>
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
      <a href="cadastro.php" class="text-decoration-none">Ainda nao tem conta? Cadastre-se</a>
    </div>

    <?php if (!empty($mensagem)): ?>
      <div class="alert alert-<?php echo $tipoMensagem; ?> text-center mt-3">
        <?php echo $mensagem; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<footer class="text-center mt-5">
  <img src="../assets/logo.png" alt="Distribuicao" class="logo-footer">
</footer>

</body>
</html>

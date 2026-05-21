<?php
include("../config/db.php");
require_once("../classes/Usuario.php");

$email = $_GET["email"] ?? "";
$mensagem = "";
$tipoMensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $novaSenha = $_POST["nova_senha"];
    $usuario = new Usuario($conn);

    if ($usuario->atualizarSenha($email, $novaSenha)) {
        $mensagem = "Senha redefinida com sucesso!";
        $tipoMensagem = "success";
    } else {
        $mensagem = "Erro ao redefinir senha.";
        $tipoMensagem = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Redefinir Senha - Distribuição</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="container mt-5">
  <div class="card shadow-lg p-4" style="max-width: 500px; margin: auto;">
    <h2 class="text-center mb-4">Redefinir Senha</h2>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Nova Senha</label>
        <input type="password" name="nova_senha" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-success w-100">Salvar Nova Senha</button>
    </form>

    <?php if (!empty($mensagem)): ?>
      <div class="alert alert-<?php echo $tipoMensagem; ?> text-center mt-3">
        <?php echo htmlspecialchars($mensagem); ?>
        <?php if ($tipoMensagem === "success"): ?>
          <p class="mt-2">Você pode agora <a href="login.php" class="text-decoration-none">fazer login</a> com sua nova senha.</p>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<footer class="text-center mt-5">
  <img src="../assets/logo.png" alt="Distribuição" class="logo-footer">
</footer>

</body>
</html>

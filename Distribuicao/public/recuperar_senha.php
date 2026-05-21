<?php
include("../config/db.php");
require_once("../classes/Usuario.php");

$mensagem = "";
$tipoMensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $nome = trim($_POST["nome"]);
    $tipo = trim($_POST["tipo"]);

    $usuario = new Usuario($conn);
    $dados = $usuario->buscarPorEmail($email);

    if ($dados && $dados["nome"] === $nome && $dados["tipo"] === $tipo) {
        // libera redefinição
        header("Location: redefinir_senha.php?email=" . urlencode($email));
        exit();
    } else {
        $mensagem = "Dados não conferem com o cadastro.";
        $tipoMensagem = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Recuperar Senha - Distribuição</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="container mt-5">
  <div class="card shadow-lg p-4" style="max-width: 500px; margin: auto;">
    <h2 class="text-center mb-4">Recuperar Senha</h2>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Nome</label>
        <input type="text" name="nome" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Tipo de Usuário</label>
        <select name="tipo" class="form-select" required>
          <option value="doador">Doador</option>
          <option value="beneficiario">Beneficiário</option>
          <option value="voluntario">Voluntário</option>
          <option value="deposito">Depósito</option>
          <option value="admin">Admin</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary w-100">Validar Dados</button>
    </form>

    <?php if (!empty($mensagem)): ?>
      <div class="alert alert-<?php echo $tipoMensagem; ?> text-center mt-3">
        <?php echo htmlspecialchars($mensagem); ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<footer class="text-center mt-5">
  <img src="../assets/logo.png" alt="Distribuição" class="logo-footer">
</footer>

</body>
</html>

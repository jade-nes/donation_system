<?php
include("../config/db.php");
require_once("../classes/Usuario.php");
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit();
}

$idUsuario = $_SESSION["id_usuario"];
$mensagem = "";
$tipoMensagem = "";
$usuarioService = new Usuario($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($usuarioService->atualizarDados($idUsuario, $_POST)) {
        $_SESSION["usuario"] = trim($_POST["nome"]);
        $mensagem = "Cadastro atualizado com sucesso!";
        $tipoMensagem = "success";
    } else {
        $mensagem = "Erro ao atualizar cadastro.";
        $tipoMensagem = "danger";
    }
}

$dados = $usuarioService->consultarPorId($idUsuario);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Editar Cadastro</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<h2>Editar Cadastro</h2>
<form method="POST">
  <div class="mb-3">
    <label class="form-label">Nome</label>
    <input type="text" name="nome" class="form-control" value="<?php echo htmlspecialchars($dados['nome']); ?>" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($dados['email']); ?>" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Telefone</label>
    <input type="text" name="telefone" class="form-control" value="<?php echo htmlspecialchars($dados['telefone'] ?? ''); ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Senha (deixe em branco se nao quiser alterar)</label>
    <input type="password" name="senha" class="form-control">
  </div>
  <button type="submit" class="btn btn-primary">Salvar Alteracoes</button>
  <a href="dashboard.php" class="btn btn-secondary">Voltar</a>
</form>

<?php if (!empty($mensagem)): ?>
  <div class="alert alert-<?php echo $tipoMensagem; ?> mt-3">
    <?php echo htmlspecialchars($mensagem); ?>
  </div>
<?php endif; ?>

</body>
</html>

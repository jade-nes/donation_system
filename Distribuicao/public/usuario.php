<?php
session_start();
if (!isset($_SESSION["id_usuario"]) || $_SESSION["tipo"] !== "admin") {
    header("Location: login.php");
    exit();
}

include("../config/db.php");
require_once("../classes/Usuario.php");

$usuarioObj = new Usuario($conn);

// Excluir usuário
if (isset($_GET['excluir'])) {
    $idExcluir = intval($_GET['excluir']);
    try {
        $usuarioObj->excluir($idExcluir);
        $mensagem = "Usuário excluído com sucesso!";
        $tipoMensagem = "success";
    } catch (Throwable $e) {
        $mensagem = "Erro ao excluir: " . $e->getMessage();
        $tipoMensagem = "danger";
    }
}

// Buscar todos os usuários
$usuarios = $usuarioObj->listarTodos();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Gestão de Usuários - Distribuição</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="container mt-5">
  <h2 class="mb-4">Gestão de Usuários</h2>

  <?php if (!empty($mensagem)): ?>
    <div class="alert alert-<?php echo $tipoMensagem; ?>">
      <?php echo htmlspecialchars($mensagem); ?>
    </div>
  <?php endif; ?>

  <table class="table table-striped table-bordered">
    <thead class="table-primary">
      <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Telefone</th>
        <th>Tipo</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($usuarios as $u): ?>
        <tr>
          <td><?php echo $u['id_usuario']; ?></td>
          <td><?php echo htmlspecialchars($u['nome']); ?></td>
          <td><?php echo htmlspecialchars($u['email']); ?></td>
          <td><?php echo htmlspecialchars($u['telefone']); ?></td>
          <td><?php echo ucfirst($u['tipo']); ?></td>
          <td>
            <a href="editar.php?id=<?php echo $u['id_usuario']; ?>" class="btn btn-sm btn-warning">Editar</a>
            <a href="usuario.php?excluir=<?php echo $u['id_usuario']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">Excluir</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<footer class="text-center mt-5">
  <img src="../assets/logo.png" alt="Distribuição" class="logo-footer">
</footer>

</body>
</html>

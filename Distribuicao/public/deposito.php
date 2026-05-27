<?php
require_once("auth.php");

include("../config/db.php");
require_once("../classes/Estoque.php");
require_once("../classes/Deposito.php");

if ($_SESSION["tipo"] !== "deposito" && $_SESSION["tipo"] !== "admin") {
    header("Location: dashboard.php");
    exit();
}

$usuario = $_SESSION["usuario"];
$idUsuario = $_SESSION["id_usuario"];
$mensagem = "";
$tipoMensagem = "";

$depositoService = new Deposito($conn);
$estoqueService = new Estoque($conn);

$deposito = $depositoService->obterPorUsuario($idUsuario);

if ($_SESSION["tipo"] === "admin") {
    $deposito = true;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $deposito) {
    $acao = $_POST["acao"] ?? "adicionar";

    if ($acao === "receber_doacao") {
        $ok = $depositoService->receberDoacao((int)$_POST["id_doacao"]);
        $mensagem = $ok ? "Doação marcada como recebida!" : "Erro ao receber doação.";
        $tipoMensagem = $ok ? "success" : "danger";
    } elseif ($acao === "remover_estoque") {
        $ok = $estoqueService->remover((int)$_POST["id_estoque"], (float)$_POST["quantidade_remover"]);
        $mensagem = $ok ? "Quantidade removida do estoque!" : "Erro ao remover item.";
        $tipoMensagem = $ok ? "success" : "danger";
    } else {
        $alimento = trim($_POST["alimento"]);
        $quantidade = (float)$_POST["quantidade"];
        $unidade = trim($_POST["unidade"]);
        $validade = $_POST["validade"] !== "" ? $_POST["validade"] : null;

        if ($depositoService->atualizarEstoque($idUsuario, $alimento, $quantidade, $unidade, $validade)) {
            $mensagem = "Item adicionado ao estoque!";
            $tipoMensagem = "success";
        } else {
            $mensagem = "Erro ao atualizar estoque.";
            $tipoMensagem = "danger";
        }
    }
}

$estoque = $depositoService->gerenciarEstoque($idUsuario);
$doacoesPendentes = $depositoService->listarDoacoesPendentes($idUsuario);
$alertasVencimento = $estoqueService->alertarVencimento();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Depósito - Gestão de Estoque</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../assets/style.css">
  <style>
    .sidebar {
      position: fixed; top: 0; left: 0;
      width: 230px; height: 100vh;
      display: flex; flex-direction: column;
      overflow-y: auto; z-index: 100;
    }
    .sidebar ul { display: flex; flex-direction: column; flex: 1; }
    .main-content { margin-left: 230px; padding: 2rem; }
  </style>
</head>
<body>

<div class="sidebar bg-primary text-white p-3">
  <h3 class="mb-4">Distribuição</h3>
  <ul class="nav flex-column flex-grow-1">
    <li class="nav-item mb-2"><a href="dashboard.php" class="nav-link text-white"><i class="bi bi-house-door me-2"></i>Início</a></li>
    <li class="nav-item mb-2"><a href="deposito.php" class="nav-link text-white"><i class="bi bi-building me-2"></i>Gestão de Estoque</a></li>
    <li class="nav-item mb-2"><a href="editar.php?id=<?php echo $_SESSION['id_usuario']; ?>" class="nav-link text-white"><i class="bi bi-pencil-square me-2"></i>Editar Cadastro</a></li>
    <li style="margin-top:auto; padding-top:1rem;">
      <a href="logout.php" class="btn btn-light w-100"><i class="bi bi-box-arrow-right me-2"></i>Sair</a>
    </li>
  </ul>
</div>

<div class="main-content">
  <h2>Bem-vindo, <?php echo htmlspecialchars($usuario); ?>!</h2>
  <p class="lead">Aqui você pode gerenciar os alimentos armazenados no depósito.</p>

  <?php if (!$deposito): ?>
    <div class="alert alert-danger">Seu usuário não possui cadastro de depósito vinculado.</div>
  <?php else: ?>
    <div class="card p-4 shadow-sm mt-3">
      <form method="POST">
        <input type="hidden" name="acao" value="adicionar">
        <div class="mb-3">
          <label class="form-label">Alimento</label>
          <input type="text" name="alimento" class="form-control" required>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Quantidade</label>
            <input type="number" step="0.01" name="quantidade" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Unidade</label>
            <input type="text" name="unidade" class="form-control" placeholder="kg, litros, unidades" required>
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">Validade</label>
          <input type="date" name="validade" class="form-control">
        </div>
        <button type="submit" class="btn btn-success w-100">Adicionar ao Estoque</button>
      </form>
    </div>

    <div class="card p-3 shadow-sm mt-4">
      <h5>Estoque Atual</h5>
      <table class="table table-striped">
        <thead><tr><th>Alimento</th><th>Quantidade</th><th>Unidade</th><th>Validade</th><th>Remover</th></tr></thead>
        <tbody>
          <?php if (!empty($estoque)): ?>
            <?php foreach ($estoque as $row): ?>
            <tr>
              <td><?php echo htmlspecialchars($row["nome_alimento"]); ?></td>
              <td><?php echo htmlspecialchars($row["quantidade"]); ?></td>
              <td><?php echo htmlspecialchars($row["unidade"]); ?></td>
              <td><?php echo htmlspecialchars($row["validade"] ?? ""); ?></td>
              <td>
                <form method="POST" class="d-flex gap-2">
                  <input type="hidden" name="acao" value="remover_estoque">
                  <input type="hidden" name="id_estoque" value="<?php echo $row["id_estoque"]; ?>">
                  <input type="number" step="0.01" min="0.01" name="quantidade_remover" class="form-control form-control-sm" style="max-width:120px;" required>
                  <button type="submit" class="btn btn-sm btn-outline-danger">Remover</button>
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="5" class="text-center">Nenhum item cadastrado.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="card p-3 shadow-sm mt-4">
      <h5>Doações Pendentes</h5>
      <table class="table table-striped">
        <thead><tr><th>Data</th><th>Doador</th><th>Descrição</th><th>Ações</th></tr></thead>
        <tbody>
          <?php if (!empty($doacoesPendentes)): ?>
            <?php foreach ($doacoesPendentes as $doacao): ?>
              <tr>
                <td><?php echo htmlspecialchars($doacao["data_doacao"]); ?></td>
                <td><?php echo htmlspecialchars($doacao["doador"]); ?></td>
                <td><?php echo htmlspecialchars($doacao["descricao"]); ?></td>
                <td>
                  <form method="POST">
                    <input type="hidden" name="acao" value="receber_doacao">
                    <input type="hidden" name="id_doacao" value="<?php echo $doacao["id_doacao"]; ?>">
                    <button type="submit" class="btn btn-sm btn-success">Receber</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="4" class="text-center">Nenhuma doação pendente.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="card p-3 shadow-sm mt-4">
      <h5>Alertas de Vencimento</h5>
      <table class="table table-striped">
        <thead><tr><th>Alimento</th><th>Quantidade</th><th>Unidade</th><th>Validade</th></tr></thead>
        <tbody>
          <?php if (!empty($alertasVencimento)): ?>
            <?php foreach ($alertasVencimento as $alerta): ?>
              <tr>
                <td><?php echo htmlspecialchars($alerta["nome_alimento"]); ?></td>
                <td><?php echo htmlspecialchars($alerta["quantidade"]); ?></td>
                <td><?php echo htmlspecialchars($alerta["unidade"]); ?></td>
                <td><?php echo htmlspecialchars($alerta["validade"]); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="4" class="text-center">Nenhum alimento perto do vencimento.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>

  <?php if (!empty($mensagem)): ?>
    <div class="alert alert-<?php echo $tipoMensagem; ?> mt-3">
      <?php echo htmlspecialchars($mensagem); ?>
    </div>
  <?php endif; ?>
</div>

<footer class="mt-5" style="margin-left:230px; text-align:center;">
  <img src="../assets/logo.png" alt="Distribuição" class="logo-footer">
</footer>

</body>
</html>

<?php
include("../config/db.php");
require_once("../classes/Doador.php");
require_once("../classes/Doacao.php");
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["tipo"] != "doador") {
    header("Location: login.php");
    exit();
}

$usuario = $_SESSION["usuario"];
$idUsuario = $_SESSION["id_usuario"];
$mensagem = "";
$tipoMensagem = "";
$doadorService = new Doador($conn);
$doacaoService = new Doacao($conn);

$doador = $doadorService->obterPorUsuario($idUsuario);
$depositos = $doadorService->consultarDepositos();

if ($_SERVER["REQUEST_METHOD"] == "POST" && $doador) {
    $acao = $_POST["acao"] ?? "registrar";

    if ($acao === "cancelar") {
        $ok = $doacaoService->cancelar((int)$_POST["id_doacao"]);
        $mensagem = $ok ? "Doacao cancelada!" : "Erro ao cancelar doacao.";
        $tipoMensagem = $ok ? "success" : "danger";
    } else {
        $alimento = trim($_POST["alimento"]);
        $quantidade = trim($_POST["quantidade"]);
        $idDeposito = (int)$_POST["id_deposito"];

        if ($doadorService->realizarDoacao($idUsuario, $idDeposito, $alimento, $quantidade)) {
            $mensagem = "Doacao registrada com sucesso!";
            $tipoMensagem = "success";
        } else {
            $mensagem = "Erro ao registrar doacao.";
            $tipoMensagem = "danger";
        }
    }
}

$historico = $doadorService->verHistorico($idUsuario);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Doador - Registrar Doacao</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="d-flex">
  <div class="sidebar bg-primary text-white p-3">
    <h3 class="mb-4">Distribuicao</h3>
    <ul class="nav flex-column">
      <li class="nav-item mb-2"><a href="dashboard.php" class="nav-link text-white">Inicio</a></li>
      <li class="nav-item mb-2"><a href="doador.php" class="nav-link text-white">Registrar Doacao</a></li>
      <li class="nav-item mt-4"><a href="logout.php" class="btn btn-light w-100">Sair</a></li>
    </ul>
  </div>

  <div class="content flex-grow-1 p-4">
    <h2>Bem-vindo, <?php echo htmlspecialchars($usuario); ?>!</h2>
    <p class="lead">Aqui voce pode registrar novas doacoes de alimentos.</p>

    <?php if (!$doador): ?>
      <div class="alert alert-danger">Seu usuario nao possui cadastro de doador vinculado.</div>
    <?php else: ?>
      <div class="card p-4 shadow-sm mt-3">
        <form method="POST">
          <input type="hidden" name="acao" value="registrar">
          <div class="mb-3">
            <label class="form-label">Nome do Alimento</label>
            <input type="text" name="alimento" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Quantidade</label>
            <input type="text" name="quantidade" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Deposito de Entrega</label>
            <select name="id_deposito" class="form-select" required>
              <?php foreach ($depositos as $deposito): ?>
                <option value="<?php echo $deposito["id_deposito"]; ?>">
                  <?php echo htmlspecialchars($deposito["nome"] . " - " . $deposito["endereco"]); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <button type="submit" class="btn btn-success w-100">Registrar Doacao</button>
        </form>
      </div>

      <div class="card p-3 shadow-sm mt-4">
        <h5>Historico de Doacoes</h5>
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Data</th>
              <th>Deposito</th>
              <th>Descricao</th>
              <th>Status</th>
              <th>Acoes</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($historico)): ?>
              <?php foreach ($historico as $row): ?>
                <tr>
                  <td><?php echo htmlspecialchars($row["data_doacao"]); ?></td>
                  <td><?php echo htmlspecialchars($row["deposito"]); ?></td>
                  <td><?php echo htmlspecialchars($row["descricao"]); ?></td>
                  <td><?php echo htmlspecialchars($row["status"]); ?></td>
                  <td>
                    <?php if ($row["status"] === "pendente"): ?>
                      <form method="POST">
                        <input type="hidden" name="acao" value="cancelar">
                        <input type="hidden" name="id_doacao" value="<?php echo $row["id_doacao"]; ?>">
                        <button type="submit" class="btn btn-sm btn-outline-danger">Cancelar</button>
                      </form>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" class="text-center">Nenhuma doacao registrada.</td>
              </tr>
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
</div>

<footer class="text-center mt-5">
  <img src="../assets/logo.png" alt="Distribuicao" class="logo-footer">
</footer>

</body>
</html>

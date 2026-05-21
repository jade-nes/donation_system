<?php
require_once("auth.php"); // garante login

include("../config/db.php");
require_once("../classes/Voluntario.php");
require_once("../classes/Entrega.php");

// permite voluntário OU admin
if ($_SESSION["tipo"] !== "voluntario" && $_SESSION["tipo"] !== "admin") {
    header("Location: dashboard.php");
    exit();
}

$usuario = $_SESSION["usuario"];
$idUsuario = $_SESSION["id_usuario"];
$mensagem = "";
$tipoMensagem = "";

$voluntarioService = new Voluntario($conn);
$entregaService = new Entrega($conn);

$voluntario = $voluntarioService->obterPorUsuario($idUsuario);

// se for admin, força como válido
if ($_SESSION["tipo"] === "admin") {
    $voluntario = true;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $voluntario) {
    $acao = $_POST["acao"] ?? "registrar";

    if ($acao === "confirmar") {
        $ok = $entregaService->confirmar((int)$_POST["id_entrega"]);
        $mensagem = $ok ? "Entrega confirmada!" : "Erro ao confirmar entrega.";
        $tipoMensagem = $ok ? "success" : "danger";
    } elseif ($acao === "cancelar") {
        $ok = $entregaService->cancelar((int)$_POST["id_entrega"]);
        $mensagem = $ok ? "Entrega cancelada!" : "Erro ao cancelar entrega.";
        $tipoMensagem = $ok ? "success" : "danger";
    } else {
        $idBeneficiario = (int)$_POST["id_beneficiario"];
        $idDeposito = (int)$_POST["id_deposito"];
        $status = $_POST["status"];

        if ($voluntarioService->registrarEntrega($idUsuario, $idBeneficiario, $idDeposito, $status)) {
            $mensagem = "Entrega registrada com sucesso!";
            $tipoMensagem = "success";
        } else {
            $mensagem = "Erro ao registrar entrega.";
            $tipoMensagem = "danger";
        }
    }
}

$beneficiarios = $voluntarioService->listarBeneficiarios();
$depositos = $voluntarioService->listarDepositos();
$entregas = $voluntarioService->verAgenda($idUsuario);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Voluntario - Entregas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="d-flex">
  <div class="sidebar bg-primary text-white p-3">
    <h3 class="mb-4">Distribuicao</h3>
    <ul class="nav flex-column">
      <li class="nav-item mb-2"><a href="dashboard.php" class="nav-link text-white">Inicio</a></li>
      <li class="nav-item mb-2"><a href="voluntario.php" class="nav-link text-white">Registrar Entrega</a></li>
      <li class="nav-item mt-4"><a href="logout.php" class="btn btn-light w-100">Sair</a></li>
    </ul>
  </div>

  <div class="content flex-grow-1 p-4">
    <h2>Bem-vindo, <?php echo htmlspecialchars($usuario); ?>!</h2>
    <p class="lead">Aqui voce pode registrar e acompanhar entregas realizadas.</p>

    <?php if (!$voluntario): ?>
      <div class="alert alert-danger">Seu usuario nao possui cadastro de voluntario vinculado.</div>
    <?php else: ?>
      <div class="card p-4 shadow-sm mt-3">
        <form method="POST">
          <div class="mb-3">
            <label class="form-label">Beneficiario</label>
            <select name="id_beneficiario" class="form-select" required>
              <?php foreach ($beneficiarios as $beneficiario): ?>
                <option value="<?php echo $beneficiario["id_beneficiario"]; ?>">
                  <?php echo htmlspecialchars($beneficiario["nome"]); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Deposito</label>
            <select name="id_deposito" class="form-select" required>
              <?php foreach ($depositos as $deposito): ?>
                <option value="<?php echo $deposito["id_deposito"]; ?>">
                  <?php echo htmlspecialchars($deposito["nome"] . " - " . $deposito["endereco"]); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
              <option value="pendente">Pendente</option>
              <option value="em_transporte">Em transporte</option>
              <option value="entregue">Entregue</option>
              <option value="cancelada">Cancelada</option>
            </select>
          </div>
          <button type="submit" class="btn btn-success w-100">Registrar Entrega</button>
        </form>
      </div>

      <div class="card p-3 shadow-sm mt-4">
        <h5>Entregas</h5>
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Data</th>
              <th>Beneficiario</th>
              <th>Deposito</th>
              <th>Rota</th>
              <th>Status</th>
              <th>Acoes</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($entregas)): ?>
              <?php foreach ($entregas as $row): ?>
                <tr>
                  <td><?php echo htmlspecialchars($row["data_entrega"]); ?></td>
                  <td><?php echo htmlspecialchars($row["beneficiario"]); ?></td>
                  <td><?php echo htmlspecialchars($row["deposito"]); ?></td>
                  <td><?php echo htmlspecialchars($row["endereco_deposito"]); ?></td>
                  <td><?php echo htmlspecialchars($row["status"]); ?></td>
                  <td>
                    <form method="POST" class="d-inline">
                      <input type="hidden" name="id_entrega" value="<?php echo $row["id_entrega"]; ?>">
                      <input type="hidden" name="acao" value="confirmar">
                      <button type="submit" class="btn btn-sm btn-success">Confirmar</button>
                    </form>
                    <form method="POST" class="d-inline">
                      <input type="hidden" name="id_entrega" value="<?php echo $row["id_entrega"]; ?>">
                      <input type="hidden" name="acao" value="cancelar">
                      <button type="submit" class="btn btn-sm btn-outline-danger">Cancelar</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="text-center">Nenhuma entrega registrada.</td>
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

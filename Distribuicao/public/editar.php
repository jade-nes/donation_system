<?php
session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit();
}

include("../config/db.php");
require_once("../classes/Usuario.php");

$usuarioObj = new Usuario($conn);

$id = isset($_GET['id']) ? intval($_GET['id']) : $_SESSION["id_usuario"];
$mensagem = "";
$tipoMensagem = "";

// Buscar dados do usuário
$dados = $usuarioObj->buscarPorId($id);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $usuarioObj->atualizar($id, $_POST);
        $mensagem = "Dados atualizados com sucesso!";
        $tipoMensagem = "success";
        $dados = $usuarioObj->buscarPorId($id); // recarrega dados atualizados
    } catch (Throwable $e) {
        $mensagem = "Erro ao atualizar: " . $e->getMessage();
        $tipoMensagem = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Editar Cadastro - Distribuição</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="container mt-5">
  <div class="card shadow-lg p-4" style="max-width: 600px; margin: auto;">
    <h2 class="text-center mb-4">Editar Cadastro</h2>
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
        <input type="text" name="telefone" class="form-control" value="<?php echo htmlspecialchars($dados['telefone']); ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Tipo de Usuário</label>
        <select name="tipo" class="form-select" id="tipoUsuario">
          <option value="doador" <?php if($dados['tipo']=="doador") echo "selected"; ?>>Doador</option>
          <option value="beneficiario" <?php if($dados['tipo']=="beneficiario") echo "selected"; ?>>Beneficiário</option>
          <option value="voluntario" <?php if($dados['tipo']=="voluntario") echo "selected"; ?>>Voluntário</option>
          <option value="deposito" <?php if($dados['tipo']=="deposito") echo "selected"; ?>>Depósito</option>
          <option value="admin" <?php if($dados['tipo']=="admin") echo "selected"; ?>>Admin</option>
        </select>
      </div>

      <!-- Campos extras -->
      <div class="mb-3 campo-extra <?php echo ($dados['tipo']!='doador')?'d-none':''; ?>" data-tipo="doador">
        <label class="form-label">CPF/CNPJ</label>
        <input type="text" name="cnpj_cpf" class="form-control" value="<?php echo htmlspecialchars($dados['cnpj_cpf'] ?? ''); ?>">
      </div>
      <div class="mb-3 campo-extra <?php echo ($dados['tipo']!='beneficiario')?'d-none':''; ?>" data-tipo="beneficiario">
        <label class="form-label">Número de pessoas na casa</label>
        <input type="number" name="num_pessoas_casa" class="form-control" min="1" value="<?php echo htmlspecialchars($dados['num_pessoas_casa'] ?? '1'); ?>">
      </div>
      <div class="mb-3 campo-extra <?php echo ($dados['tipo']!='voluntario')?'d-none':''; ?>" data-tipo="voluntario">
        <label class="form-label">Disponibilidade</label>
        <input type="text" name="disponibilidade" class="form-control" value="<?php echo htmlspecialchars($dados['disponibilidade'] ?? ''); ?>">
      </div>
      <div class="campo-extra <?php echo ($dados['tipo']!='deposito')?'d-none':''; ?>" data-tipo="deposito">
        <div class="mb-3">
          <label class="form-label">Nome do depósito</label>
          <input type="text" name="nome_deposito" class="form-control" value="<?php echo htmlspecialchars($dados['nome_deposito'] ?? ''); ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Endereço</label>
          <input type="text" name="endereco" class="form-control" value="<?php echo htmlspecialchars($dados['endereco'] ?? ''); ?>">
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Latitude</label>
            <input type="number" step="0.00000001" name="latitude" class="form-control" value="<?php echo htmlspecialchars($dados['latitude'] ?? ''); ?>">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Longitude</label>
            <input type="number" step="0.00000001" name="longitude" class="form-control" value="<?php echo htmlspecialchars($dados['longitude'] ?? ''); ?>">
          </div>
        </div>
      </div>

      <!-- Campo extra para Admin -->
      <div class="mb-3 campo-extra <?php echo ($dados['tipo']!='admin')?'d-none':''; ?>" data-tipo="admin">
        <label class="form-label">Código de Autenticação</label>
        <input type="text" name="codigo_admin" class="form-control" value="">
        <small class="text-muted">Necessário para manter ou alterar para Admin (código: secreto123)</small>
      </div>

      <!-- Campo de senha (sempre visível) -->
      <div class="mb-3">
        <label class="form-label">Senha</label>
        <input type="password" name="senha" class="form-control">
        <small class="text-muted">Deixe em branco se não quiser alterar</small>
      </div>

      <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
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

<script>
  const tipoUsuario = document.getElementById('tipoUsuario');
  const camposExtras = document.querySelectorAll('.campo-extra');

  function atualizarCamposExtras() {
    camposExtras.forEach((campo) => {
      campo.classList.toggle('d-none', campo.dataset.tipo !== tipoUsuario.value);
    });
  }

  tipoUsuario.addEventListener('change', atualizarCamposExtras);
  atualizarCamposExtras();
</script>

</body>
</html>

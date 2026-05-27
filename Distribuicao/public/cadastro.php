<?php
include("../config/db.php");
require_once("../classes/Usuario.php");

$mensagem = "";
$tipoMensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Validação extra para Admin
        if ($_POST['tipo'] === 'admin') {
            if (empty($_POST['codigo_admin']) || $_POST['codigo_admin'] !== 'secreto123') {
                throw new Exception("Código de autenticação inválido para Admin.");
            }
        }

        $usuario = new Usuario($conn);
        $usuario->cadastrar($_POST);
        $mensagem = "Usuário cadastrado com sucesso!";
        $tipoMensagem = "success";
    } catch (Throwable $e) {
        $mensagem = "Erro ao cadastrar: " . $e->getMessage();
        $tipoMensagem = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro - Distribuição</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/style.css">
  <?php if ($tipoMensagem === "success"): ?>
    <script>
      setTimeout(function(){
        window.location.href = "login.php";
      }, 10000); // 10 segundos
    </script>
  <?php endif; ?>
</head>
<body>

<div class="container mt-5">
  <div class="card shadow-lg p-4" style="max-width: 600px; margin: auto;">
    <h2 class="text-center mb-4">Cadastro de Usuário</h2>
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
        <label class="form-label">Senha</label>
        <input type="password" name="senha" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Telefone</label>
        <input type="text" name="telefone" class="form-control">
      </div>
      <div class="mb-3">
        <label class="form-label">Tipo de Usuário</label>
        <select name="tipo" class="form-select" id="tipoUsuario">
          <option value="doador">Doador</option>
          <option value="beneficiario">Beneficiário</option>
          <option value="voluntario">Voluntário</option>
          <option value="deposito">Depósito</option>
          <option value="admin">Admin</option>
        </select>
      </div>

      <!-- Campos extras -->
      <div class="mb-3 campo-extra" data-tipo="doador">
        <label class="form-label">CPF/CNPJ</label>
        <input type="text" name="cnpj_cpf" class="form-control">
      </div>
      <div class="mb-3 campo-extra d-none" data-tipo="beneficiario">
        <label class="form-label">Número de pessoas na casa</label>
        <input type="number" name="num_pessoas_casa" class="form-control" min="1" value="1">
      </div>
      <div class="mb-3 campo-extra d-none" data-tipo="voluntario">
        <label class="form-label">Disponibilidade</label>
        <input type="text" name="disponibilidade" class="form-control">
      </div>
      <div class="campo-extra d-none" data-tipo="deposito">
        <div class="mb-3">
          <label class="form-label">Nome do depósito</label>
          <input type="text" name="nome_deposito" class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">Endereço</label>
          <input type="text" name="endereco" class="form-control">
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Latitude</label>
            <input type="number" step="0.00000001" name="latitude" class="form-control">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Longitude</label>
            <input type="number" step="0.00000001" name="longitude" class="form-control">
          </div>
        </div>
      </div>

      <!-- Campo extra para Admin -->
      <div class="mb-3 campo-extra d-none" data-tipo="admin">
        <label class="form-label">Código de Autenticação</label>
        <input type="text" name="codigo_admin" class="form-control" placeholder="Digite o código secreto">
      </div>

      <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
      <a href="login.php" class="btn btn-secondary w-100 mt-2">Voltar à página de login</a>
    </form>

    <?php if (!empty($mensagem)): ?>
  <div class="alert alert-<?php echo $tipoMensagem; ?> text-center mt-3">
    <?php echo htmlspecialchars($mensagem); ?>
    <?php if ($tipoMensagem === "success"): ?>
      <p id="contador" class="mt-2">Você será redirecionado para o login em 10 segundos...</p>
      <script>
        let segundos = 10;
        const contador = document.getElementById("contador");
        const intervalo = setInterval(() => {
          segundos--;
          contador.textContent = "Você será redirecionado para o login em " + segundos + " segundos...";
          if (segundos <= 0) {
            clearInterval(intervalo);
            window.location.href = "login.php";
          }
        }, 1000);
      </script>
    <?php endif; ?>
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

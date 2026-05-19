<?php include("../config/db.php"); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro - Distribuição</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="container mt-5">
  <div class="card shadow-lg p-4" style="max-width: 500px; margin: auto;">
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
        <select name="tipo" class="form-select">
          <option value="doador">Doador</option>
          <option value="beneficiario">Beneficiário</option>
          <option value="voluntario">Voluntário</option>
          <option value="deposito">Depósito</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
    </form>
  </div>
</div>

<footer class="text-center mt-5">
  <img src="../assets/logo.png" alt="Distribuição" class="logo-footer">
</footer>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);
    $telefone = $_POST["telefone"];
    $tipo = $_POST["tipo"];

    $sql = "INSERT INTO usuario (nome,email,senha,telefone,tipo) 
            VALUES ('$nome','$email','$senha','$telefone','$tipo')";
    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success text-center mt-3'>Usuário cadastrado com sucesso!</div>";
    } else {
        echo "<div class='alert alert-danger text-center mt-3'>Erro: " . $conn->error . "</div>";
    }
}
?>
</body>
</html>

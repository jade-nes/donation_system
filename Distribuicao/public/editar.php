<?php 
include("../config/db.php"); 
session_start();
if(!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}
$usuario = $_SESSION["usuario"];
$tipo = $_SESSION["tipo"];

// Busca dados atuais
$sql = "SELECT * FROM usuario WHERE nome='$usuario' AND tipo='$tipo'";
$result = $conn->query($sql);
$dados = $result->fetch_assoc();
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
    <input type="text" name="nome" class="form-control" value="<?php echo $dados['nome']; ?>" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" value="<?php echo $dados['email']; ?>" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Telefone</label>
    <input type="text" name="telefone" class="form-control" value="<?php echo $dados['telefone']; ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Senha (deixe em branco se não quiser alterar)</label>
    <input type="password" name="senha" class="form-control">
  </div>
  <button type="submit" class="btn btn-primary">Salvar Alterações</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $telefone = $_POST["telefone"];
    $senha = $_POST["senha"];

    if (!empty($senha)) {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $sqlUpdate = "UPDATE usuario SET nome='$nome', email='$email', telefone='$telefone', senha='$senhaHash' 
                      WHERE id=".$dados['id'];
    } else {
        $sqlUpdate = "UPDATE usuario SET nome='$nome', email='$email', telefone='$telefone' 
                      WHERE id=".$dados['id'];
    }

    if ($conn->query($sqlUpdate) === TRUE) {
        echo "<div class='alert alert-success mt-3'>Cadastro atualizado com sucesso!</div>";
        $_SESSION["usuario"] = $nome; // Atualiza sessão
    } else {
        echo "<div class='alert alert-danger mt-3'>Erro: " . $conn->error . "</div>";
    }
}
?>

</body>
</html>

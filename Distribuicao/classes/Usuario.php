<?php
class Usuario {
    private mysqli $conn; // tipagem explícita para evitar aviso do Intelephense

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    // Cadastro de usuário
    public function cadastrar(array $dados): bool {
        $nome = trim($dados["nome"]);
        $email = trim($dados["email"]);
        $senha = password_hash($dados["senha"], PASSWORD_DEFAULT);
        $telefone = $dados["telefone"] ?? null;
        $tipo = $dados["tipo"];

        $sql = "INSERT INTO usuario (nome, email, senha, telefone, tipo) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssss", $nome, $email, $senha, $telefone, $tipo);
        return $stmt->execute();
    }

    // Login de usuário
    public function login(string $email, string $senha): array|false {
        $sql = "SELECT * FROM usuario WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($senha, $user["senha"])) {
            return $user;
        }
        return false;
    }

    // Buscar usuário pelo email
    public function buscarPorEmail(string $email): ?array {
        $sql = "SELECT * FROM usuario WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    // Atualizar senha do usuário
    public function atualizarSenha(string $email, string $novaSenha): bool {
        $hash = password_hash($novaSenha, PASSWORD_DEFAULT);
        $sql = "UPDATE usuario SET senha = ? WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $hash, $email);
        return $stmt->execute();
    }

    // Excluir usuário
    public function excluir(int $id): bool {
        $sql = "DELETE FROM usuario WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // Listar todos os usuários
    public function listarTodos(): array {
        $sql = "SELECT * FROM usuario ORDER BY criado_em DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Buscar usuário por ID
    public function buscarPorId(int $id): ?array {
        $sql = "SELECT * FROM usuario WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    // Atualizar dados do usuário
    public function atualizar(int $id, array $dados): bool {
        $nome = trim($dados["nome"]);
        $email = trim($dados["email"]);
        $telefone = $dados["telefone"] ?? null;
        $tipo = $dados["tipo"];

        $sql = "UPDATE usuario SET nome = ?, email = ?, telefone = ?, tipo = ? WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssi", $nome, $email, $telefone, $tipo, $id);
        return $stmt->execute();
    }
}
?>

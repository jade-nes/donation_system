<?php
class Usuario {
    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    // Cadastrar novo usuário
    public function cadastrar(array $dados): void {
        $sql = "INSERT INTO usuario (nome, email, senha, telefone, tipo) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        $senhaHash = password_hash($dados['senha'], PASSWORD_DEFAULT);

        $stmt->bind_param(
            "sssss",
            $dados['nome'],
            $dados['email'],
            $senhaHash,
            $dados['telefone'],
            $dados['tipo']
        );
        $stmt->execute();
    }

    // Listar todos os usuários
    public function listarTodos(): array {
        $sql = "SELECT * FROM usuario";
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

    // Atualizar usuário
    public function atualizar(int $id, array $dados): void {
        $sql = "UPDATE usuario SET nome=?, email=?, telefone=?, tipo=? WHERE id_usuario=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "ssssi",
            $dados['nome'],
            $dados['email'],
            $dados['telefone'],
            $dados['tipo'],
            $id
        );
        $stmt->execute();
    }

    // Excluir usuário
    public function excluir(int $id): void {
        $sql = "DELETE FROM usuario WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    // Login
    public function login(string $email, string $senha): ?array {
        $sql = "SELECT * FROM usuario WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            return $usuario;
        }
        return null;
    }
}

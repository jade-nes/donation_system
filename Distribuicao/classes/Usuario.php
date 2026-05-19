<?php

class Usuario
{
    protected mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function cadastrar(array $dados): int
    {
        $nome = trim($dados["nome"] ?? "");
        $email = trim($dados["email"] ?? "");
        $senha = password_hash($dados["senha"] ?? "", PASSWORD_DEFAULT);
        $telefone = trim($dados["telefone"] ?? "");
        $tipo = $dados["tipo"] ?? "";

        $this->conn->begin_transaction();

        try {
            $stmt = $this->conn->prepare("INSERT INTO usuario (nome, email, senha, telefone, tipo) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $nome, $email, $senha, $telefone, $tipo);
            $stmt->execute();
            $idUsuario = $this->conn->insert_id;
            $stmt->close();

            $this->cadastrarEspecializacao($idUsuario, $tipo, $dados, $nome);

            $this->conn->commit();
            return $idUsuario;
        } catch (Throwable $e) {
            $this->conn->rollback();
            throw $e;
        }
    }

    public function login(string $email, string $senha): ?array
    {
        $stmt = $this->conn->prepare("SELECT id_usuario, nome, email, senha, tipo FROM usuario WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $usuario = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$usuario || !password_verify($senha, $usuario["senha"])) {
            return null;
        }

        unset($usuario["senha"]);
        return $usuario;
    }

    public function atualizarDados(int $idUsuario, array $dados): bool
    {
        $nome = trim($dados["nome"] ?? "");
        $email = trim($dados["email"] ?? "");
        $telefone = trim($dados["telefone"] ?? "");
        $senha = $dados["senha"] ?? "";

        if ($senha !== "") {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("UPDATE usuario SET nome = ?, email = ?, telefone = ?, senha = ? WHERE id_usuario = ?");
            $stmt->bind_param("ssssi", $nome, $email, $telefone, $senhaHash, $idUsuario);
        } else {
            $stmt = $this->conn->prepare("UPDATE usuario SET nome = ?, email = ?, telefone = ? WHERE id_usuario = ?");
            $stmt->bind_param("sssi", $nome, $email, $telefone, $idUsuario);
        }

        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_unset();
        session_destroy();
    }

    public function consultarPorId(int $idUsuario): ?array
    {
        $stmt = $this->conn->prepare("SELECT nome, email, telefone, tipo FROM usuario WHERE id_usuario = ?");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $usuario = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $usuario ?: null;
    }

    private function cadastrarEspecializacao(int $idUsuario, string $tipo, array $dados, string $nome): void
    {
        if ($tipo === "doador") {
            $cnpjCpf = trim($dados["cnpj_cpf"] ?? "");
            $cnpjCpf = $cnpjCpf !== "" ? $cnpjCpf : "Nao informado";

            $stmt = $this->conn->prepare("INSERT INTO doador (id_usuario, cnpj_cpf) VALUES (?, ?)");
            $stmt->bind_param("is", $idUsuario, $cnpjCpf);
        } elseif ($tipo === "beneficiario") {
            $numPessoasCasa = max(1, (int)($dados["num_pessoas_casa"] ?? 1));

            $stmt = $this->conn->prepare("INSERT INTO beneficiario (id_usuario, num_pessoas_casa) VALUES (?, ?)");
            $stmt->bind_param("ii", $idUsuario, $numPessoasCasa);
        } elseif ($tipo === "voluntario") {
            $disponibilidade = trim($dados["disponibilidade"] ?? "");

            $stmt = $this->conn->prepare("INSERT INTO voluntario (id_usuario, disponibilidade) VALUES (?, ?)");
            $stmt->bind_param("is", $idUsuario, $disponibilidade);
        } elseif ($tipo === "deposito") {
            $nomeDeposito = trim($dados["nome_deposito"] ?? "");
            $endereco = trim($dados["endereco"] ?? "");
            $latitude = isset($dados["latitude"]) && $dados["latitude"] !== "" ? (float)$dados["latitude"] : null;
            $longitude = isset($dados["longitude"]) && $dados["longitude"] !== "" ? (float)$dados["longitude"] : null;

            $nomeDeposito = $nomeDeposito !== "" ? $nomeDeposito : $nome;
            $endereco = $endereco !== "" ? $endereco : "Endereco nao informado";

            $stmt = $this->conn->prepare("INSERT INTO deposito (id_usuario, nome, endereco, latitude, longitude) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issdd", $idUsuario, $nomeDeposito, $endereco, $latitude, $longitude);
        } else {
            throw new InvalidArgumentException("Tipo de usuario invalido.");
        }

        $stmt->execute();
        $stmt->close();
    }
}

<?php

require_once(__DIR__ . "/Estoque.php");

class Deposito
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function obterPorUsuario(int $idUsuario): ?array
    {
        $stmt = $this->conn->prepare("SELECT id_deposito, nome, endereco, latitude, longitude FROM deposito WHERE id_usuario = ?");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $deposito = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $deposito ?: null;
    }

    public function gerenciarEstoque(int $idUsuario): array
    {
        $deposito = $this->obterPorUsuario($idUsuario);
        if (!$deposito) {
            return [];
        }

        $estoque = new Estoque($this->conn);
        return $estoque->consultar($deposito["id_deposito"]);
    }

    public function receberDoacao(int $idDoacao): bool
    {
        $status = "recebida";
        $stmt = $this->conn->prepare("UPDATE doacao SET status = ? WHERE id_doacao = ?");
        $stmt->bind_param("si", $status, $idDoacao);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function atualizarEstoque(int $idUsuario, string $alimento, float $quantidade, string $unidade, ?string $validade): bool
    {
        $deposito = $this->obterPorUsuario($idUsuario);
        if (!$deposito) {
            return false;
        }

        $estoque = new Estoque($this->conn);
        return $estoque->adicionar($deposito["id_deposito"], $alimento, $quantidade, $unidade, $validade);
    }

    public function listarDoacoesPendentes(int $idUsuario): array
    {
        $deposito = $this->obterPorUsuario($idUsuario);
        if (!$deposito) {
            return [];
        }

        $status = "pendente";
        $stmt = $this->conn->prepare("
            SELECT doacao.id_doacao, doacao.data_doacao, doacao.descricao, usuario.nome AS doador
            FROM doacao
            INNER JOIN doador ON doador.id_doador = doacao.id_doador
            INNER JOIN usuario ON usuario.id_usuario = doador.id_usuario
            WHERE doacao.id_deposito = ? AND doacao.status = ?
            ORDER BY doacao.data_doacao DESC
        ");
        $stmt->bind_param("is", $deposito["id_deposito"], $status);
        $stmt->execute();
        $doacoes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $doacoes;
    }
}

<?php

class Doador
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function obterPorUsuario(int $idUsuario): ?array
    {
        $stmt = $this->conn->prepare("SELECT id_doador, cnpj_cpf FROM doador WHERE id_usuario = ?");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $doador = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $doador ?: null;
    }

    public function realizarDoacao(int $idUsuario, int $idDeposito, string $alimento, string $quantidade): bool
    {
        $doador = $this->obterPorUsuario($idUsuario);
        if (!$doador) {
            return false;
        }

        $descricao = "Alimento: " . trim($alimento) . " | Quantidade: " . trim($quantidade);
        $status = "pendente";

        $stmt = $this->conn->prepare("INSERT INTO doacao (id_doador, id_deposito, descricao, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $doador["id_doador"], $idDeposito, $descricao, $status);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function consultarDepositos(): array
    {
        $result = $this->conn->query("SELECT id_deposito, nome, endereco, latitude, longitude FROM deposito ORDER BY nome");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function verHistorico(int $idUsuario): array
    {
        $doador = $this->obterPorUsuario($idUsuario);
        if (!$doador) {
            return [];
        }

        $stmt = $this->conn->prepare("
            SELECT doacao.id_doacao, doacao.data_doacao, doacao.descricao, doacao.status, deposito.nome AS deposito
            FROM doacao
            INNER JOIN deposito ON deposito.id_deposito = doacao.id_deposito
            WHERE doacao.id_doador = ?
            ORDER BY doacao.data_doacao DESC
        ");
        $stmt->bind_param("i", $doador["id_doador"]);
        $stmt->execute();
        $historico = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $historico;
    }
}

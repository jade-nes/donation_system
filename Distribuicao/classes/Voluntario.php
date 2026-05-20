<?php

class Voluntario
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function obterPorUsuario(int $idUsuario): ?array
    {
        $stmt = $this->conn->prepare("SELECT id_voluntario, disponibilidade FROM voluntario WHERE id_usuario = ?");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $voluntario = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $voluntario ?: null;
    }

    public function registrarEntrega(int $idUsuario, int $idBeneficiario, int $idDeposito, string $status): bool
    {
        $voluntario = $this->obterPorUsuario($idUsuario);
        if (!$voluntario) {
            return false;
        }

        $stmt = $this->conn->prepare("INSERT INTO entrega (id_voluntario, id_beneficiario, id_deposito, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $voluntario["id_voluntario"], $idBeneficiario, $idDeposito, $status);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function consultarRota(int $idEntrega): ?array
    {
        $stmt = $this->conn->prepare("
            SELECT e.id_entrega, e.status, d.nome AS deposito, d.endereco AS endereco_deposito,
                   ub.nome AS beneficiario, uben.telefone AS telefone_beneficiario
            FROM entrega e
            INNER JOIN deposito d ON d.id_deposito = e.id_deposito
            INNER JOIN beneficiario b ON b.id_beneficiario = e.id_beneficiario
            INNER JOIN usuario ub ON ub.id_usuario = b.id_usuario
            INNER JOIN usuario uben ON uben.id_usuario = b.id_usuario
            WHERE e.id_entrega = ?
        ");
        $stmt->bind_param("i", $idEntrega);
        $stmt->execute();
        $rota = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $rota ?: null;
    }

    public function verAgenda(int $idUsuario): array
    {
        $voluntario = $this->obterPorUsuario($idUsuario);
        if (!$voluntario) {
            return [];
        }

        $stmt = $this->conn->prepare("
            SELECT en.id_entrega, en.data_entrega, en.status, ub.nome AS beneficiario,
                   d.nome AS deposito, d.endereco AS endereco_deposito
            FROM entrega en
            INNER JOIN beneficiario b ON b.id_beneficiario = en.id_beneficiario
            INNER JOIN usuario ub ON ub.id_usuario = b.id_usuario
            INNER JOIN deposito d ON d.id_deposito = en.id_deposito
            WHERE en.id_voluntario = ?
            ORDER BY en.data_entrega DESC
        ");
        $stmt->bind_param("i", $voluntario["id_voluntario"]);
        $stmt->execute();
        $agenda = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $agenda;
    }

    public function listarBeneficiarios(): array
    {
        $result = $this->conn->query("
            SELECT b.id_beneficiario, u.nome
            FROM beneficiario b
            INNER JOIN usuario u ON u.id_usuario = b.id_usuario
            ORDER BY u.nome
        ");

        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function listarDepositos(): array
    {
        $result = $this->conn->query("SELECT id_deposito, nome, endereco FROM deposito ORDER BY nome");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}

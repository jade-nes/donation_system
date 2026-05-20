<?php

class Entrega
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function registrar(int $idVoluntario, int $idBeneficiario, int $idDeposito, string $status = "pendente"): bool
    {
        $stmt = $this->conn->prepare("INSERT INTO entrega (id_voluntario, id_beneficiario, id_deposito, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $idVoluntario, $idBeneficiario, $idDeposito, $status);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function confirmar(int $idEntrega): bool
    {
        $status = "entregue";
        $stmt = $this->conn->prepare("UPDATE entrega SET status = ? WHERE id_entrega = ?");
        $stmt->bind_param("si", $status, $idEntrega);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function cancelar(int $idEntrega): bool
    {
        $status = "cancelada";
        $stmt = $this->conn->prepare("UPDATE entrega SET status = ? WHERE id_entrega = ?");
        $stmt->bind_param("si", $status, $idEntrega);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }
}

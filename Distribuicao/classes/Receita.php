<?php

class Receita
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function exibir(int $idReceita): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM receita WHERE id_receita = ?");
        $stmt->bind_param("i", $idReceita);
        $stmt->execute();
        $receita = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $receita ?: null;
    }

    public function listar(): array
    {
        $result = $this->conn->query("SELECT * FROM receita ORDER BY titulo");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function buscarPorAlimento(int $idAlimento): array
    {
        $stmt = $this->conn->prepare("SELECT * FROM receita WHERE id_alimento = ? ORDER BY titulo");
        $stmt->bind_param("i", $idAlimento);
        $stmt->execute();
        $receitas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $receitas;
    }
}

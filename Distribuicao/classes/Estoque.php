<?php

class Estoque
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function adicionar(int $idDeposito, string $nomeAlimento, float $quantidade, string $unidade, ?string $validade): bool
    {
        $stmt = $this->conn->prepare("INSERT INTO estoque (id_deposito, nome_alimento, quantidade, unidade, validade) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isdss", $idDeposito, $nomeAlimento, $quantidade, $unidade, $validade);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function remover(int $idEstoque, float $quantidade): bool
    {
        $stmt = $this->conn->prepare("
            UPDATE estoque
            SET quantidade = GREATEST(quantidade - ?, 0)
            WHERE id_estoque = ?
        ");
        $stmt->bind_param("di", $quantidade, $idEstoque);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function consultar(?int $idDeposito = null): array
    {
        if ($idDeposito !== null) {
            $stmt = $this->conn->prepare("SELECT id_estoque, nome_alimento, quantidade, unidade, validade FROM estoque WHERE id_deposito = ? ORDER BY nome_alimento");
            $stmt->bind_param("i", $idDeposito);
            $stmt->execute();
            $estoque = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            return $estoque;
        }

        $result = $this->conn->query("
            SELECT estoque.*, deposito.nome AS deposito
            FROM estoque
            INNER JOIN deposito ON deposito.id_deposito = estoque.id_deposito
            ORDER BY estoque.nome_alimento
        ");

        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function alertarVencimento(int $dias = 30): array
    {
        $stmt = $this->conn->prepare("
            SELECT estoque.*, deposito.nome AS deposito
            FROM estoque
            INNER JOIN deposito ON deposito.id_deposito = estoque.id_deposito
            WHERE estoque.validade IS NOT NULL
              AND estoque.validade BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ? DAY)
            ORDER BY estoque.validade ASC
        ");
        $stmt->bind_param("i", $dias);
        $stmt->execute();
        $alertas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $alertas;
    }
}

<?php

require_once(__DIR__ . "/Receita.php");

class Beneficiario
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function consultarDepositos(): array
    {
        $result = $this->conn->query("
            SELECT e.nome_alimento, e.quantidade, e.unidade, e.validade, d.nome AS deposito, d.endereco
            FROM estoque e
            INNER JOIN deposito d ON d.id_deposito = e.id_deposito
            ORDER BY e.nome_alimento, d.nome
        ");

        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function localizarPontos(): array
    {
        $result = $this->conn->query("
            SELECT id_deposito, nome, endereco, latitude, longitude
            FROM deposito
            WHERE latitude IS NOT NULL AND longitude IS NOT NULL
            ORDER BY nome
        ");

        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function verReceitas(): array
    {
        $receita = new Receita($this->conn);
        return $receita->listar();
    }
}

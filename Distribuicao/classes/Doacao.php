<?php

class Doacao
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function registrar(int $idDoador, int $idDeposito, string $descricao): bool
    {
        $status = "pendente";
        $stmt = $this->conn->prepare("INSERT INTO doacao (id_doador, id_deposito, descricao, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $idDoador, $idDeposito, $descricao, $status);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function cancelar(int $idDoacao): bool
    {
        $status = "cancelada";
        $stmt = $this->conn->prepare("UPDATE doacao SET status = ? WHERE id_doacao = ?");
        $stmt->bind_param("si", $status, $idDoacao);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function consultar(?int $idDoador = null, ?int $idDeposito = null, ?string $status = null): array
    {
        $where = [];
        $params = [];
        $types = "";

        if ($idDoador !== null) {
            $where[] = "doacao.id_doador = ?";
            $params[] = $idDoador;
            $types .= "i";
        }

        if ($idDeposito !== null) {
            $where[] = "doacao.id_deposito = ?";
            $params[] = $idDeposito;
            $types .= "i";
        }

        if ($status !== null) {
            $where[] = "doacao.status = ?";
            $params[] = $status;
            $types .= "s";
        }

        $sql = "
            SELECT doacao.*, usuario.nome AS doador, deposito.nome AS deposito
            FROM doacao
            INNER JOIN doador ON doador.id_doador = doacao.id_doador
            INNER JOIN usuario ON usuario.id_usuario = doador.id_usuario
            INNER JOIN deposito ON deposito.id_deposito = doacao.id_deposito
        ";

        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $sql .= " ORDER BY doacao.data_doacao DESC";

        $stmt = $this->conn->prepare($sql);
        if ($params) {
            $refs = [];
            foreach ($params as $key => $value) {
                $refs[$key] = &$params[$key];
            }
            $stmt->bind_param($types, ...$refs);
        }
        $stmt->execute();
        $doacoes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $doacoes;
    }
}

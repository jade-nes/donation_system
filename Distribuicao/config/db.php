<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "distribuicao_alimentos";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexao falhou: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>

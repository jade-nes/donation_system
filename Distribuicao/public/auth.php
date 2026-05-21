<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit();
}

// Se quiser restringir acesso apenas para Admin em certas páginas,
// basta adicionar este trecho nas páginas específicas:
//
// if ($_SESSION["tipo"] !== "admin") {
//     header("Location: dashboard.php");
//     exit();
// }

<?php
include("../config/db.php");
require_once("../classes/Usuario.php");

$usuario = new Usuario($conn);
$usuario->logout();

header("Location: login.php");
exit();
?>

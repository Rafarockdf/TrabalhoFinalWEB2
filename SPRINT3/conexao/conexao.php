<?php
$servername = "localhost";
$usuario = "root";             
$senha = "";                   
$database = "healthpal2";       


$conn = new mysqli($servername, $usuario, $senha, $database);


if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
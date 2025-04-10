<?php
header('Content-Type: application/json');

include '../../Conexao/php/conexao.php';

$sql = "SELECT id, nome, email, genero, IMC FROM usuarios";
$result = $conn->query($sql);

$usuarios = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
    echo json_encode($usuarios);
} else {
    echo json_encode([
        "sucesso" => false,
        "mensagem" => "Erro ao consultar o banco de dados: " . $conn->error
    ]);
}

$conn->close();
?>

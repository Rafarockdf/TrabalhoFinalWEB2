<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

include __DIR__ . '/../../Conexao/php/conexao.php';

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'] ?? null;
$nome = $data['nome'] ?? '';
$email = $data['email'] ?? '';
$genero = $data['genero'] ?? '';
$imc = $data['imc'] ?? '';

if (!$id || !$nome || !$email || !$genero || !$imc) {
    echo json_encode(["sucesso" => false, "mensagem" => "Dados incompletos"]);
    exit;
}

$sql = "UPDATE usuarios SET nome=?, email=?, genero=?, IMC=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $nome, $email, $genero, $imc, $id);

if ($stmt->execute()) {
    echo json_encode(["sucesso" => true]);
} else {
    echo json_encode(["sucesso" => false, "mensagem" => $stmt->error]);
}

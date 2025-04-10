<?php
header("Content-Type: application/json");
include '../../Conexao/php/conexao.php';

$dados = json_decode(file_get_contents("php://input"), true);

$titulo = trim($dados['titulo'] ?? '');
$conteudo = trim($dados['conteudo'] ?? '');

if (empty($titulo) || empty($conteudo) || $conteudo == "<p><br></p>") {
    echo json_encode(["sucesso" => false, "mensagem" => "Título ou conteúdo vazios"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO dietas (titulo, conteudo) VALUES (?, ?)");
$stmt->bind_param("ss", $titulo, $conteudo);

if ($stmt->execute()) {
    echo json_encode(["sucesso" => true, "mensagem" => "Dieta publicada com sucesso"]);
} else {
    echo json_encode(["sucesso" => false, "mensagem" => "Erro ao salvar no banco"]);
}

$stmt->close();
$conn->close();
?>
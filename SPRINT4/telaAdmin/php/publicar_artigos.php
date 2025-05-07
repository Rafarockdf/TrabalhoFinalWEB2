<?php
header("Content-Type: application/json");
include '../../Conexao/php/conexao.php';

$titulo = trim($_POST['titulo'] ?? '');
$conteudo = trim($_POST['conteudo'] ?? '');

if (empty($titulo) || empty($conteudo) || $conteudo == "<p><br></p>") {
    echo json_encode(["sucesso" => false, "mensagem" => "Título ou conteúdo vazios"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO publiartigos (titulo, conteudo) VALUES (?, ?)");
$stmt->bind_param("ss", $titulo, $conteudo);

if ($stmt->execute()) {
    echo json_encode(["sucesso" => true, "mensagem" => "Artigo publicado com sucesso"]);
} else {
    echo json_encode(["sucesso" => false, "mensagem" => "Erro ao salvar no banco"]);
}

$stmt->close();
$conn->close();
?>

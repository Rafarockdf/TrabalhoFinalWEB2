<?php
session_start();
include '../../Conexao/php/conexao.php';

if (isset($_POST['atividade_id'])) {
    $atividade_id = intval($_POST['atividade_id']);

    // Atualiza a atividade para concluída
    $sql = "UPDATE atividades_fisicas SET concluida = 1, registrado_executado_em = NOW() WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $atividade_id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    $stmt->close();
}
$conn->close();
?>

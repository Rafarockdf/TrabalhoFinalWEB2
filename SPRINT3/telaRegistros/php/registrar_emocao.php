<?php
session_start();
include '../../Conexao/php/conexao.php';

if (isset($_POST['atividade_id']) && isset($_POST['emocao'])) {
    $atividade_id = intval($_POST['atividade_id']);
    $emocao = $_POST['emocao'];
    $usuario_email = $_SESSION['email'];

    // Busca ID do usuário
    $stmtUser = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmtUser->bind_param("s", $usuario_email);
    $stmtUser->execute();
    $resultUser = $stmtUser->get_result();

    if ($rowUser = $resultUser->fetch_assoc()) {
        $usuario_id = $rowUser['id'];

        // 1. Inserir sentimento (se ainda não existir)
        $stmtSentimento = $conn->prepare("SELECT id FROM sentimentos WHERE humor = ?");
        $stmtSentimento->bind_param("s", $emocao);
        $stmtSentimento->execute();
        $resultSentimento = $stmtSentimento->get_result();

        if ($rowSentimento = $resultSentimento->fetch_assoc()) {
            $sentimento_id = $rowSentimento['id'];
        } else {
            $insertSentimento = $conn->prepare("INSERT INTO sentimentos (humor, categoria) VALUES (?, 'atividade')");
            $insertSentimento->bind_param("s", $emocao);
            $insertSentimento->execute();
            $sentimento_id = $insertSentimento->insert_id;
            $insertSentimento->close();
        }

        // 2. Atualizar atividade como concluída
        $updateAtividade = $conn->prepare("UPDATE atividades_fisicas SET concluida = 1, registrado_executado_em = NOW() WHERE id = ?");
        $updateAtividade->bind_param("i", $atividade_id);
        $updateAtividade->execute();
        $updateAtividade->close();

        // 3. Inserir no humor
        $insertHumor = $conn->prepare("INSERT INTO humor (sentimento_id, usuario_id, atividade_id) VALUES (?, ?, ?)");
        $insertHumor->bind_param("iii", $sentimento_id, $usuario_id, $atividade_id);
        $insertHumor->execute();
        $insertHumor->close();

        echo "success";
    } else {
        echo "Usuário não encontrado!";
    }
    
    $stmtUser->close();
} else {
    echo "Dados incompletos!";
}

$conn->close();
?>

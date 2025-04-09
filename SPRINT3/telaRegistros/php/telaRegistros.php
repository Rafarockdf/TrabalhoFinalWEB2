<?php
session_start();
include '../../Conexao/php/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tipo_atividade = $_POST['tipo-atividade'];
    $tempo = $_POST['tempo'];
    $calorias = $_POST['calorias'];

    // Consulta para obter o id do tipo de atividade
    $stmt = $conn->prepare("SELECT id FROM tipos_atividades_fisicas WHERE ds_atividade = ?");
    $stmt->bind_param("s", $tipo_atividade);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $id_tipo_atividade = $row['id'];

        // Insere a atividade física
        $stmt_insert = $conn->prepare("INSERT INTO atividades_fisicas (usuario_id, id_tipo_atividade, duracao_min, calorias_queimadas) VALUES (?, ?, ?, ?)");
        $stmt_insert->bind_param("iiii", $usuario_id, $id_tipo_atividade, $tempo, $calorias);
        $stmt_insert->execute();

        // Pega o ID da atividade recém-inserida (para o humor)
        $atividade_id = $stmt_insert->insert_id;

        // Armazena o ID da atividade em sessão, para depois ser usado no humor
        $_SESSION['atividade_id'] = $atividade_id;

        echo "<script>window.location.href='../html/registros_atividade.php';</script>";
    } else {
        echo "Tipo de atividade inválido.";
    }
}
?>

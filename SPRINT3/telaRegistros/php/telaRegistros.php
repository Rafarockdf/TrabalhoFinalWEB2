<?php
session_start();
include '../../Conexao/php/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tipo_atividade = $_POST['tipo-atividade'];
    $tempo = $_POST['tempo'];
    $data = $_POST['data']

    // Consulta para obter o id do tipo de atividade
    $stmt = $conn->prepare("SELECT id FROM tipos_atividades_fisicas WHERE ds_atividade = ?");
    $stmt->bind_param("s", $tipo_atividade);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $id_tipo_atividade = $row['id'];

        $stmt_insert = $conn->prepare("INSERT INTO atividades_fisicas (usuario_id, id_tipo_atividade, duracao_min,dt_plano_treino) VALUES (?, ?, ?, ?)");
        $stmt_insert->bind_param("iiis", $usuario_id, $id_tipo_atividade, $tempo, $data);
        $stmt_insert->execute();

        $atividade_id = $stmt_insert->insert_id;

        $_SESSION['atividade_id'] = $atividade_id;

        echo "<script>window.location.href='../html/registros_atividade.php';</script>";
    } else {
        echo "Tipo de atividade inválido.";
    }
}
?>

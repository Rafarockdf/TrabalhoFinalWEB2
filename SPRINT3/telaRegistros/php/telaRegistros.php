<?php
session_start();
include '../../Conexao/php/conexao.php';

if (!isset($_SESSION['email'])) {
    header('Location: ../../Login/html/login.html');
    exit();
}

$email = $_SESSION['email'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tipo_atividade = $_POST['tipo-atividade'];
    $tempo = $_POST['tempo'];
    $data = $_POST['data'];
    $horario = $_POST['horario'];
    $horario_formatado = date("H:i:s", strtotime($horario));
    $concluida = false;


    $stmt = $conn->prepare("SELECT id FROM tipos_atividades_fisicas WHERE ds_atividade = ?");
    $stmt->bind_param("s", $tipo_atividade);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $stmt2 = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt2->bind_param("s", $email);
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    if($row2 = $result2->fetch_assoc()){
        if ($row = $result->fetch_assoc()) {
            $id_tipo_atividade = $row['id'];
            $usuario_id = $row2['id'];
            $stmt_insert = $conn->prepare("INSERT INTO atividades_fisicas (usuario_id, id_tipo_atividade, duracao_min,dt_plano_treino,hr_plano_treino,concluida) VALUES (?, ?, ?, ?,?,?)");
            $stmt_insert->bind_param("iiissi", $usuario_id, $id_tipo_atividade, $tempo, $data,$horario_formatado,$concluida);
            $stmt_insert->execute();
    
            $atividade_id = $stmt_insert->insert_id;
            header('Location: ../html/telaRegistros.html');
            exit();
            echo "<script>window.location.href='../html/registros_atividade.php';</script>";
        } else {
            echo "Tipo de atividade inválido.";
        }       
    }

}
?>

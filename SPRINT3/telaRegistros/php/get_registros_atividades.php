<?php
session_start();
include '../../Conexao/php/conexao.php';
$usuario['nome'] = $_SESSION['usuario']
$usuario['email'] = $_SESSION['email'] 

$sql = "SELECT af.id, taf.nome AS atividade, af.duracao_min, af.calorias_queimadas, 
               af.dt_plano_treino, 
               IF(af.registrado_executado_em = '0000-00-00 00:00:00', '', DATE_FORMAT(af.registrado_executado_em, '%d/%m/%Y %H:%i')) AS registrado_executado_em
        FROM atividades_fisicas af
        JOIN tipos_atividades_fisicas taf ON af.id_tipo_atividade = taf.id
        WHERE af.usuario_id = ?
        ORDER BY af.dt_plano_treino DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

$atividades = [];
while ($row = $result->fetch_assoc()) {
    $atividades[] = $row;
}

echo json_encode($atividades);

$stmt->close();
$conn->close();

?>
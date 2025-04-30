<?php
session_start();
include '../../Conexao/php/conexao.php';

$usuario_email = $_SESSION['email'];

$stmt2 = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt2->bind_param("s", $usuario_email);
$stmt2->execute();
$result2 = $stmt2->get_result();

$html = "";

if ($row2 = $result2->fetch_assoc()) {
    $usuario_id = $row2['id'];

    $sql = "SELECT af.id, taf.ds_atividade AS Atividade, af.duracao_min AS Duracao, 
            af.dt_plano_treino AS DataPlanejada ,af.hr_plano_treino AS HorarioPlanejado,
            IF(af.registrado_executado_em = '0000-00-00 00:00:00', '', DATE_FORMAT(af.registrado_executado_em, '%d/%m/%Y %H:%i')) AS registrado_executado_em, 
            af.concluida
            FROM atividades_fisicas af
            JOIN tipos_atividades_fisicas taf ON af.id_tipo_atividade = taf.id
            WHERE af.usuario_id = ?
            ORDER BY af.dt_plano_treino DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();


    while ($row = $result->fetch_assoc()) {
    $status = $row['concluida'] 
        ? "<button type='button' style='background-color: #4CAF50; color: white; border: none; padding: 5px 10px; border-radius: 5px;'>Concluída</button>" 
        : "<button style='z-index: 1000; 
background-color: #f44336;
 position: relative;' onclick='abrirCalculadora({$row["id"]})'>Pendente</button>
";

    $html .= "<tr>
                <td>{$row['Atividade']}</td>
                <td>{$row['Duracao']}</td>
                <td>{$row['DataPlanejada']}</td>
                <td>{$row['HorarioPlanejado']}</td>
                <td>{$row['registrado_executado_em']}</td>
                <td>{$status}</td>
              </tr>";
}

    
    $stmt->close();
    
}
$stmt2->close();
$conn->close();

echo $html;
?>

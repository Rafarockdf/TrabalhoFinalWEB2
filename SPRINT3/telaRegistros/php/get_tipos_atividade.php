<?php
include '../../Conexao/php/conexao.php';

$query = "SELECT ds_atividade FROM tipos_atividades_fisicas";
$result = $conn->query($query);

$options = "";

while ($row = $result->fetch_assoc()) {
    $atividade = htmlspecialchars($row['ds_atividade']);
    $options .= "<option value='{$atividade}'>{$atividade}</option>";
}

echo $options;
?>

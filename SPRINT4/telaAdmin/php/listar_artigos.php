<?php
include '../../Conexao/php/conexao.php';

$sql = "SELECT * FROM publiartigos";
$result = $conn->query($sql);

$dados = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $dados[] = $row;
    }
}
echo json_encode($dados);
$conn->close();
?>

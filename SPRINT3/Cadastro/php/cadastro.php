<?php

include '../../Conexao/php/conexao.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = hash('sha256', $_POST['senha']);
    $data = $_POST['data']; // A data já vem no formato adequado
    $sexo = $_POST['sexo'];
    $altura = floatval($_POST['altura']);
    $peso = floatval($_POST['peso']);

  
    if ($altura > 0) {
        $IMC = round($peso / ($altura * $altura), 2);
    } else {
        $IMC = 0; 
    }


    // Verificar o valor do IMC (com debug)
    echo "Valor do IMC: " . $IMC . "<br>";  // Verifique o valor calculado do IMC


    $sql = $conn->prepare("INSERT INTO usuarios (nome, email, senha_hash, data_nascimento, sexo, altura, peso, IMC) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    

    $sql->bind_param("ssssssdd", $nome, $email, $senha, $data, $sexo, $altura, $peso, $IMC);  

    if ($sql->execute()) {
        echo "Sucesso ao inserir no banco!";
        header("Location: ../../Login/html/login.html");
    } else {
        echo "<script>console.log('Erro ao inserir no banco: " . $sql->error . "');</script>";
    }

    $sql->close();
}

$conn->close();
?>

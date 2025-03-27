<?php

$servername = "localhost";
$usuario = "rafael2";
$senha = "1234";
$database = "healthpal";

$conexao = new mysqli($servername, $usuario, $senha, $database);

// Verificar se houve erro na conexão
if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = hash('sha256', $_POST['senha']);
    $data = $_POST['data']; // A data já vem no formato adequado
    $sexo = $_POST['sexo'];
    $altura = floatval($_POST['altura']);
    $peso = floatval($_POST['peso']);

    // Calcular IMC corretamente
    if ($altura > 0) {
        $IMC = round($peso / ($altura * $altura), 2); // Arredondar para 2 casas decimais
    } else {
        $IMC = 0; // Evita divisão por zero
    }

    // Verificar o valor do IMC (com debug)
    echo "Valor do IMC: " . $IMC . "<br>";  // Verifique o valor calculado do IMC

    // Preparar a query para evitar SQL Injection
    $sql = $conexao->prepare("INSERT INTO usuarios (nome, email, senha_hash, data_nascimento, sexo, altura, peso, IMC) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    // Corrigir o tipo para 'd' (decimal) no bind_param para o IMC
    $sql->bind_param("ssssssdd", $nome, $email, $senha, $data, $sexo, $altura, $peso, $IMC);  

    if ($sql->execute()) {
        echo "Sucesso ao inserir no banco!";
    } else {
        echo "<script>console.log('Erro ao inserir no banco: " . $sql->error . "');</script>";
    }

    $sql->close();
}

$conexao->close();
?>

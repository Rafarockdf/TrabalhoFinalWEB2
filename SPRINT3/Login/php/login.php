<?php
session_start();

// Caminho absoluto seguro
include __DIR__ . '/../../conexao/conexao.php';

$email = $_POST['email'] ?? '';
$senha_digitada = $_POST['senha'] ?? '';

if (empty($email) || empty($senha_digitada)) {
    die("Preencha todos os campos obrigatórios.");
}

$sql = "SELECT * FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $usuario = $resultado->fetch_assoc();

    if (password_verify($senha_digitada, $usuario['senha_hash'])) {
        $_SESSION['usuario'] = $usuario['nome'];
        $_SESSION['email'] = $usuario['email'];

        // Redirecionamento para a dashboard
        header("Location: ../../Dasboard/html/index.html");
        exit;
    } else {
        echo "Senha incorreta.";
    }
} else {
    echo "Usuário não encontrado.";
}
?>

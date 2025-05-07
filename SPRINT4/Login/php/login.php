<?php
// 1) Configura cookies de sessão e inicia sessão
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',      // cookie válido para toda a aplicação
    'secure'   => false,    // Defina como true se estiver usando HTTPS
    'httponly' => true,
    'samesite' => 'Lax'
  ]);
session_start(); // Inicia a sessão

include '../../Conexao/php/conexao.php'; // Inclui o arquivo de conexão

$email = $_POST['email'] ?? '';
$senha_digitada = hash('sha256', $_POST['senha']) ?? '';

if (empty($email) || empty($senha_digitada)) {
    die("Preencha todos os campos obrigatórios.");
}

$sql = "SELECT id, nome, senha_hash FROM usuarios WHERE email = ?"; // Seleciona também o ID
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $usuario = $resultado->fetch_assoc();

    if ($senha_digitada === $usuario['senha_hash']) {
        // Autenticação bem-sucedida
        $_SESSION['usuario'] = $usuario['nome'];
        $_SESSION['email'] = $usuario['email'];
        $_SESSION['usuario_id'] = $usuario['id']; // <--- ESTA LINHA É FUNDAMENTAL

        // Redireciona para a página de perfil
        header("Location: ../../Perfil/html/perfil.html");
        exit; // Garante que o script pare de executar após o redirecionamento
    } else {
        // Senha incorreta
        echo "Senha incorreta.";
    }
} else {
    // Usuário não encontrado
    echo "Usuário não encontrado.";
}

// Fecha a conexão com o banco de dados
$stmt->close();
$conn->close();
?>
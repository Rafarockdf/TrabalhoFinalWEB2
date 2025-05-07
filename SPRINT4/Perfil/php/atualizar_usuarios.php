<?php
// Habilita a exibição de erros para depuração (REMOVA EM PRODUÇÃO!)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ──────────────────────────────────────────────────────────────────────────
// 1) Configura cookies de sessão e inicia sessão
session_set_cookie_params([
  'lifetime' => 0,
  'path'     => '/',      // cookie válido para toda a aplicação
  'secure'   => false,    // true se for HTTPS
  'httponly' => true,
  'samesite' => 'Lax'
]);
session_start();

// ──────────────────────────────────────────────────────────────────────────
// 2) Sempre JSON; suprime warnings (mantido para evitar quebra do JSON se warnings não forem fatais, mas display_errors acima é mais forte)
header('Content-Type: application/json; charset=utf-8');
// ini_set('display_errors', 0); // Comentado ou remova esta linha se display_errors 1 estiver no topo
// error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE); // Ajustado ou remova esta linha se E_ALL estiver no topo

// ──────────────────────────────────────────────────────────────────────────
// 3) Inclui a conexão
// VERIFIQUE ESTE CAMINHO! Ele deve estar correto a partir da localização deste arquivo.
include("../../Conexao/conexao.php");

// Verifica se a conexão falhou (adicionado tratamento de erro básico para a conexão)
if ($conn->connect_error) {
    // Retorna um erro JSON se a conexão falhar
    echo json_encode(["status" => "erro", "mensagem" => "Falha na conexão com o banco de dados: " . $conn->connect_error]);
    exit; // Termina a execução
}


// ──────────────────────────────────────────────────────────────────────────
// 4) Verifica sessão
$usuarioId = $_SESSION['usuario_id'] ?? null;
if (!$usuarioId) {
    echo json_encode(["status" => "erro", "mensagem" => "Usuário não autenticado"]);
    exit;
}

// ──────────────────────────────────────────────────────────────────────────
// 5) Lê dado JSON do corpo
$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    // Retorna um erro JSON se os dados não forem JSON válido
    echo json_encode(["status" => "erro", "mensagem" => "Dados inválidos ou não recebidos como JSON."]);
    exit;
}

// ──────────────────────────────────────────────────────────────────────────
// 6) Prepara campos (com tratamento para campos ausentes - ?? '')
$nome            = $data['nome'] ?? '';
$email           = $data['email'] ?? '';
$senha           = $data['senha'] ?? ''; // A senha pode vir vazia se não for alterada
$data_nascimento = $data['data_nascimento'] ?? '';
$sexo            = $data['sexo'] ?? '';
$altura          = (float)($data['altura'] ?? 0); // Garante que é float, padrão 0
$peso            = (float)($data['peso'] ?? 0);   // Garante que é float, padrão 0

// Calcula o IMC - verifica se altura é maior que zero para evitar divisão por zero
$imc = 0; // Valor padrão para IMC
if ($altura > 0) {
    $imc = round($peso / ($altura * $altura), 2);
}


// ──────────────────────────────────────────────────────────────────────────
// 7) Monta UPDATE (com ou sem senha)
// Prepara partes da query e parâmetros que são sempre incluídos
$update_fields = "nome=?, email=?, data_nascimento=?, sexo=?, altura=?, peso=?, IMC=?";
$bind_types = "sssssdd";
$bind_params = [$nome, $email, $data_nascimento, $sexo, $altura, $peso, $imc];

// Adiciona a senha se ela for fornecida
if (!empty($senha)) {
    // Calcula o hash SHA256 da senha no PHP
    $senha_hash = hash('sha256', $senha);
    $update_fields .= ", senha_hash=?"; // Adiciona o campo senha_hash
    $bind_types .= "s"; // Adiciona o tipo 's' para a senha_hash
    $bind_params[] = $senha_hash; // Adiciona o hash da senha aos parâmetros
}

// Adiciona o ID do usuário ao final dos parâmetros
$bind_types .= "i";
$bind_params[] = $usuarioId;


// Monta a query final
$sql  = "UPDATE usuarios
         SET $update_fields
         WHERE id=?";

$stmt = $conn->prepare($sql);

// Vincula os parâmetros dinamicamente
// A função call_user_func_array permite passar um array de parâmetros para outra função (stmt->bind_param)
if ($stmt) {
    call_user_func_array([$stmt, 'bind_param'], array_merge([$bind_types], $bind_params));

    // ──────────────────────────────────────────────────────────────────────────
    // 8) Executa e retorna status
    if ($stmt->execute()) {
        echo json_encode(["status" => "sucesso", "mensagem" => "Perfil atualizado com sucesso!"]);
    } else {
        // Retorna um erro JSON se a execução falhar
        echo json_encode(["status" => "erro", "mensagem" => "Erro ao executar a atualização: " . $stmt->error]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // 9) Libera recursos
    $stmt->close();

} else {
    // Retorna um erro JSON se a preparação da query falhar
    echo json_encode(["status" => "erro", "mensagem" => "Erro ao preparar a query: " . $conn->error]);
}


// Fecha a conexão com o banco de dados
$conn->close();
?>
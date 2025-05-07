<?php
// ──────────────────────────────────────────────────────────────────────────
// 1) Configura cookies de sessão para todo o site e inicia sessão
session_set_cookie_params([
  'lifetime' => 0,
  'path'     => '/',      // ← cookie válido para toda a aplicação
  'secure'   => false,    // true se for HTTPS
  'httponly' => true,
  'samesite' => 'Lax'
]);
session_start();

// ──────────────────────────────────────────────────────────────────────────
// 2) Sempre JSON; suprime warnings para não “quebrar” o JSON
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);

// ──────────────────────────────────────────────────────────────────────────
// 3) Inclui a conexão (ajuste o caminho caso necessário)
include("../../Conexao/conexao.php");

// ──────────────────────────────────────────────────────────────────────────
// 4) Verifica se está logado
$usuarioId = $_SESSION['usuario_id'] ?? null;
if (!$usuarioId) {
    echo json_encode(["erro" => "Usuário não autenticado"]);
    exit;
}

// ──────────────────────────────────────────────────────────────────────────
// 5) Busca dados do usuário
$sql = "SELECT nome, email, data_nascimento, sexo, altura, peso, IMC, criado_em
        FROM usuarios
        WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuarioId);
$stmt->execute();

$result = $stmt->get_result();
$user   = $result->fetch_assoc();

// ──────────────────────────────────────────────────────────────────────────
// 6) Retorna JSON com os dados
echo json_encode($user);

// ──────────────────────────────────────────────────────────────────────────
// 7) Libera recursos
$stmt->close();
$conn->close();
?>

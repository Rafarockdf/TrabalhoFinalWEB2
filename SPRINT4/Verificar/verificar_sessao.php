<?php
// Inicia a sessão (garante que a sessão já existente seja carregada)
session_start();

// Define o cabeçalho para retornar JSON
header('Content-Type: application/json; charset=utf-8');

// Verifica se a variável de sessão 'usuario_id' está definida
if (isset($_SESSION['email']) && $_SESSION['email'] !== null) {
    // Usuário está logado
    echo json_encode(["logado" => true]);
} else {
    // Usuário não está logado
    echo json_encode(["logado" => false]);
}

// Opcional: Fechar a sessão se não for mais necessária (geralmente não precisa aqui)
// session_write_close();
?>
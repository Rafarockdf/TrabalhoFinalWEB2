<?php

session_start(); // Inicia ou resume a sessão existente

// Para destruir uma única variável de sessão:
unset($_SESSION['email']);

// Para destruir todas as variáveis de sessão:
// $_SESSION = array(); // Descomente esta linha para limpar o array $_SESSION

// Finalmente, destrói a sessão completamente:
session_destroy();

// Redirecionar o usuário para a página de login ou outra página apropriada após a destruição da sessão
header("Location: ../../Tela Inicial/html/TelaInicial.html"); // Substitua "login.php" pela sua página de login
exit();

?>
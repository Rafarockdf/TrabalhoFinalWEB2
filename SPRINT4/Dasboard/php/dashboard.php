<?php
session_start();

// Inclua o arquivo de conexão com o banco de dados
include '../../Conexao/php/conexao.php';

// Verifique se o usuário está logado
if (!isset($_SESSION['email'])) {
    header('Location: ../../Login/html/login.html');
    exit();
}

// 1. Obtenha o ID do usuário
$usuario_email = $_SESSION['email']; // Use o email da sessão
$stmtUser = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmtUser->bind_param("s", $usuario_email);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();

if ($rowUser = $resultUser->fetch_assoc()) {
    $usuario_id = $rowUser['id'];

    // ----------------------------------------------------------------------
    // 2. Consulta para o Card 1: Atividades Pendentes
    // ----------------------------------------------------------------------
    $sql_card1 = "SELECT COUNT(id) AS total_pendentes 
                  FROM atividades_fisicas 
                  WHERE concluida = FALSE AND usuario_id = ?";
    $stmtCard1 = $conn->prepare($sql_card1);
    $stmtCard1->bind_param("i", $usuario_id);
    $stmtCard1->execute();
    $resultCard1 = $stmtCard1->get_result();
    $card1 = $resultCard1->fetch_assoc();

    // ----------------------------------------------------------------------
    // 3. Consulta para o Gráfico 1: Atividades por Mês
    // ----------------------------------------------------------------------
    $sql_grafico1 = "SELECT 
                        T.ds_atividade AS Atividade,
                        COUNT(A.id) AS Contagem,
                        DATE_FORMAT(A.dt_plano_treino, '%M') AS Mes,
                        MONTH(A.dt_plano_treino)
                    FROM atividades_fisicas AS A
                    JOIN tipos_atividades_fisicas AS T 
                      ON A.id_tipo_atividade = T.id
                    WHERE A.usuario_id = ?
                    GROUP BY T.ds_atividade, DATE_FORMAT(A.dt_plano_treino, '%M')
                    ORDER BY MONTH(A.dt_plano_treino), T.ds_atividade"; //Ordenado por Mês e Atividade
    $stmtGrafico1 = $conn->prepare($sql_grafico1);
    $stmtGrafico1->bind_param("i", $usuario_id);
    $stmtGrafico1->execute();
    $resultGrafico1 = $stmtGrafico1->get_result();
    $grafico1 = [];
    while ($row = $resultGrafico1->fetch_assoc()) {
        $grafico1[] = $row;
    }

    // ----------------------------------------------------------------------
    // 4. Consulta para o Gráfico 2: Humor por Atividade
    // ----------------------------------------------------------------------
    $sql_grafico2 = "SELECT 
                        T.ds_atividade AS Atividade,
                        S.humor AS Sentimento,
                        COUNT(A.id) AS Contagem
                    FROM atividades_fisicas AS A
                    JOIN tipos_atividades_fisicas AS T 
                      ON A.id_tipo_atividade = T.id
                    JOIN humor AS H 
                      ON H.atividade_id = A.id
                    JOIN sentimentos AS S 
                      ON S.id = H.sentimento_id
                    WHERE A.usuario_id = ?
                    GROUP BY T.ds_atividade, S.humor
                    ORDER BY T.ds_atividade, S.humor";  //Ordenado por Atividade e Humor
    $stmtGrafico2 = $conn->prepare($sql_grafico2);
    $stmtGrafico2->bind_param("i", $usuario_id);
    $stmtGrafico2->execute();
    $resultGrafico2 = $stmtGrafico2->get_result();
    $grafico2 = [];
    while ($row = $resultGrafico2->fetch_assoc()) {
        $grafico2[] = $row;
    }

    // ----------------------------------------------------------------------
    // 5. Preparar a resposta JSON
    // ----------------------------------------------------------------------
    $response = [
        'success' => true, // Indica que a requisição foi bem-sucedida
        'card1' => $card1,
        'grafico1' => $grafico1,
        'grafico2' => $grafico2,
    ];

} else {
    // Usuário não encontrado
    $response = [
        'success' => false,
        'message' => 'Usuário não encontrado.',
    ];
}

// Defina o tipo de conteúdo como JSON e envie a resposta
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);

// Feche a conexão com o banco de dados
$stmtUser->close();
$stmtCard1->close();
$stmtGrafico1->close();
$stmtGrafico2->close();
$conn->close();
?>

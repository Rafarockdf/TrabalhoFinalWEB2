<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['email'])) {
    header('Location: ../../Login/html/login.html');
    exit();
}

include '../../Conexao/php/conexao.php';

$usuario_email = $_SESSION['email'];
$resultados = json_decode(file_get_contents("php://input"), true);

if (!isset($resultados[0]['nutrientes'])) {
    echo json_encode(["erro" => "Formato inesperado: campo 'nutrientes' ausente."]);
    exit;
}

if (!$resultados || !is_array($resultados)) {
    http_response_code(400);
    echo json_encode(["error" => "Dados de alimentos inválidos."]);
    exit;
}

$conn->begin_transaction();

try {
    // Obter o ID do usuário
    $stmt2 = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt2->bind_param("s", $usuario_email);
    $stmt2->execute();
    $result = $stmt2->get_result();
    $usuario = $result->fetch_assoc();

    if (!$usuario) {
        throw new Exception("Usuário não encontrado.");
    }
    $usuario_id = $usuario['id'];

    // Dados da refeição
    $ds_refeicao = $resultados[0]['refeicao'];
    $totalCalorias = 0;
    $totalProteinas = 0;
    $totalCarboidratos = 0;
    $totalGorduras = 0;

    foreach ($resultados as $item) {
        $nutrientes = $item['nutrientes'];
        $totalCalorias += (float)$nutrientes['calorias'];
        $totalProteinas += (float)$nutrientes['proteinas'];
        $totalCarboidratos += (float)$nutrientes['carboidratos'];
        $totalGorduras += (float)$nutrientes['gorduras'];
    }

    // Inserir alimentação
    $stmt = $conn->prepare("
        INSERT INTO alimentacao (usuario_id, descricao, ds_refeicao, qt_calorias_gr, qt_carboidratos_gr, qt_proteinas_gr, qt_gorduras_gr)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("issdddd", $usuario_id, $ds_refeicao, $ds_refeicao, $totalCalorias, $totalCarboidratos, $totalProteinas, $totalGorduras);
    $stmt->execute();
    $alimentacao_id = $conn->insert_id;

    // Inserir alimentos
    foreach ($resultados as $item) {
        $codigo = $item['id'];

        $checkStmt = $conn->prepare("SELECT id FROM alimentos WHERE codigo_externo = ?");
        $checkStmt->bind_param("s", $codigo);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        $alimento = $checkResult->fetch_assoc();

        if ($alimento) {
            $alimento_id = $alimento['id'];
        } else {
            $nome = $item['nome'];
            $calorias = (float)$item['nutrientes']['calorias'];
            $origem = 'FatSecret';

            $insertAlimento = $conn->prepare("
                INSERT INTO alimentos (ds_alimento, calorias_por_100g, origem_api, codigo_externo)
                VALUES (?, ?, ?, ?)
            ");
            $insertAlimento->bind_param("sdss", $nome, $calorias, $origem, $codigo);
            $insertAlimento->execute();
            $alimento_id = $conn->insert_id;
        }

        $quantidade = (float)$item['quantidade'];
        $calorias = (float)$item['nutrientes']['calorias'];
        $carboidratos = (float)$item['nutrientes']['carboidratos'];
        $proteinas = (float)$item['nutrientes']['proteinas'];
        $gorduras = (float)$item['nutrientes']['gorduras'];

        $insertVinculo = $conn->prepare("
            INSERT INTO alimentacao_alimentos (alimentacao_id, alimento_id, quantidade_g, qt_calorias_gr, qt_carboidratos_gr, qt_proteinas_gr, qt_gorduras_gr)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $insertVinculo->bind_param("iiddddd", $alimentacao_id, $alimento_id, $quantidade, $calorias, $carboidratos, $proteinas, $gorduras);
        $insertVinculo->execute();
    }

    $conn->commit();
    echo json_encode(["success" => true, "message" => "Refeição registrada com sucesso."]);
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(["erro" => "Erro na linha " . $e->getLine() . ": " . $e->getMessage()]);
}
?>

<?php
// CONFIGURAÇÕES - substitua pelos seus dados
$client_id = 'dce42afa44fb4e6b986bdecdf6b52d7e';
$client_secret = '869d60eae3604109a28b6c2b34c144e1';

// Função para obter o token OAuth2
function getAccessToken($client_id, $client_secret) {
    $url = 'https://oauth.fatsecret.com/connect/token';
    $headers = [
        'Content-Type: application/x-www-form-urlencoded',
        'Authorization: Basic ' . base64_encode("$client_id:$client_secret")
    ];
    $body = http_build_query([
        'grant_type' => 'client_credentials',
        'scope' => 'basic'
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $resposta = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => "Erro ao obter token. Código HTTP: $httpCode", 'details' => $resposta]);
        return null;
    }

    $dados = json_decode($resposta, true);
    return $dados['access_token'] ?? null;
}

// Se a requisição for um GET e o parâmetro 'alimento' estiver presente
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['alimento'])) {
    $alimento = trim($_GET['alimento']);
    if (empty($alimento)) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => "Por favor, digite o nome de um alimento."]);
        exit;
    }

    $token = getAccessToken($client_id, $client_secret);

    if (!$token) {
        exit; // A função getAccessToken já enviou uma resposta de erro
    }

    // Faz a busca pelo alimento
    $url = "https://platform.fatsecret.com/rest/server.api";
    $params = [
        'method' => 'foods.search',
        'format' => 'json',
        'search_expression' => $alimento
    ];

    $ch = curl_init($url . '?' . http_build_query($params));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $token"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $resposta = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $dados = json_decode($resposta, true);
    $lista_sugestoes = [];

    if ($httpCode === 200 && isset($dados['foods']['food'])) {
        // Garante que 'food' seja um array, mesmo que haja apenas um resultado
        $foods = is_array($dados['foods']['food']) ? $dados['foods']['food'] : [$dados['foods']['food']];

        foreach ($foods as $food) {
            $lista_sugestoes[] = [
                'id' => $food['food_id'], // Adicione um ID se disponível
                'nome' => $food['food_name'],
                'marca' => $food['brand_name'] ?? 'Desconhecida',
                'descricao' => $food['food_description']
            ];
        }
        header('Content-Type: application/json');
        echo json_encode($lista_sugestoes);
    } else {
        http_response_code(404); // Not Found
        echo json_encode(['error' => "Nenhum resultado encontrado para '$alimento'.", 'details' => $dados['error']['message'] ?? 'Erro desconhecido']);
    }
} else {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => "Requisição inválida. Use o método GET com o parâmetro 'alimento'."]);
}
?>
<?php

header('Content-Type: application/json');
$client_id = 'dce42afa44fb4e6b986bdecdf6b52d7e';
$client_secret = '869d60eae3604109a28b6c2b34c144e1';

// Obtem os alimentos recebidos do JavaScript
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !is_array($data)) {
    http_response_code(400);
    echo json_encode(["error" => "Dados inválidos"]);
    exit;
}

// Pega o token
$token = getAccessToken($client_id, $client_secret);

$resultados = [];

foreach ($data['alimentos'] as $alimento) {
    $nome = $alimento['nome'];
    $quantidade = $alimento['quantidade'];

    $url = "https://platform.fatsecret.com/rest/server.api";
    $params = [
        'method' => 'foods.search',
        'format' => 'json',
        'search_expression' => $nome
    ];

    $ch = curl_init($url . '?' . http_build_query($params));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $token"]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $resposta = curl_exec($ch);

    if (curl_errno($ch)) {
        curl_close($ch);
        echo json_encode(["error" => "Erro na requisição cURL", "detalhes" => curl_error($ch)]);
        exit;
    }

    curl_close($ch);

    $dados = json_decode($resposta, true);

    if (!isset($dados['foods']['food'][0])) {
        $resultados[] = [
            'nome' => $nome,
            'quantidade' => $quantidade,
            'descricao' => 'Não encontrado'
        ];
        continue;
    }

    $info = $dados['foods']['food'][0];
    $food_id = $info['food_id'];
    
    // Segunda requisição: detalhes nutricionais
    $paramsDetails = [
        'method' => 'food.get',
        'format' => 'json',
        'food_id' => $food_id
    ];
    
    $ch = curl_init($url . '?' . http_build_query($paramsDetails));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $token"]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $respostaDetalhes = curl_exec($ch);
    curl_close($ch);
    
    $detalhes = json_decode($respostaDetalhes, true);
    
    // Verifica se existe porção e dados nutricionais
    $servingData = $detalhes['food']['servings']['serving'] ?? null;

    // Se houver múltiplas porções (array), pega a primeira
    if (is_array($servingData) && isset($servingData[0])) {
        $serving = $servingData[0];
    } elseif (is_array($servingData)) {
        $serving = $servingData;
    } else {
        $serving = null;
}

    
    if (!$serving) {
        $resultados[] = [
            'nome' => $nome,
            'quantidade' => $quantidade,
            'descricao' => $info['food_description'] ?? 'Sem descrição',
            'id' => $food_id,
            'marca' => $info['brand_name'] ?? 'Genérico',
            'nutrientes' => 'Dados indisponíveis'
        ];
        continue;
    }
    $peso_por_serving = isset($serving['serving_weight_grams']) && is_numeric($serving['serving_weight_grams']) 
    ? (float) $serving['serving_weight_grams'] 
    : 100; // fallback se não houver informação

    $fator = $quantidade / $peso_por_serving;

    // Corrige os campos nutricionais multiplicando pelo fator
    $calorias = round(((float) $serving['calories']) * $fator, 2);
    $proteinas = round(((float) $serving['protein']) * $fator, 2);
    $carboidratos = round(((float) $serving['carbohydrate']) * $fator, 2);
    $gorduras = round(((float) $serving['fat']) * $fator, 2);
    
    $resultados[] = [
        'nome' => $nome,
        'quantidade' => $quantidade,
        'descricao' => $info['food_description'] ?? 'Sem descrição',
        'id' => $food_id,
        'marca' => $info['brand_name'] ?? 'Genérico',
        'nutrientes' => [
            'calorias' => $calorias,
            'proteinas' => $proteinas,
            'carboidratos' => $carboidratos,
            'gorduras' => $gorduras
        ]
    ];
    
}

echo json_encode($resultados);

// Função auxiliar
function getAccessToken($client_id, $client_secret) {
    $url = "https://oauth.fatsecret.com/connect/token";

    $post_fields = http_build_query([
        'grant_type' => 'client_credentials',
        'scope' => 'basic',
    ]);

    $headers = [
        'Authorization: Basic ' . base64_encode("$client_id:$client_secret"),
        'Content-Type: application/x-www-form-urlencoded'
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    if (isset($data['access_token'])) {
        return $data['access_token'];
    } else {
        die(json_encode(['error' => 'Falha ao obter access_token', 'resposta' => $response]));
    }
}

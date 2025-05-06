<?
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
        die(json_encode(['error' => 'Falha ao obter access_token', 'detalhes' => $data]));
    }
}
?>
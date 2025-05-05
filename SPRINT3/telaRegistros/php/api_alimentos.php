<?php
if (isset($_POST['alimento'])) {
    $alimento = $_POST['alimento'];
    $url = "https://world.openfoodfacts.org/cgi/search.pl?search_terms=" . urlencode($alimento) . "&search_simple=1&action=process&json=1&page_size=1";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $resposta = curl_exec($ch);
    curl_close($ch);

    $dados = json_decode($resposta, true);

    if (isset($dados['products'][0])) {
        $produto = $dados['products'][0];
        echo "<h2>Resultado para: " . htmlspecialchars($alimento) . "</h2>";
        echo "Nome: " . ($produto['product_name'] ?? 'Sem nome') . "<br>";
        echo "Marca: " . ($produto['brands'] ?? 'Desconhecida') . "<br>";
        echo "Calorias (por 100g): " . ($produto['nutriments']['energy-kcal_100g'] ?? 'N/A') . " kcal<br>";
    } else {
        echo "Nenhum resultado encontrado para '$alimento'.";
    }
} else {
    echo "Selecione um alimento primeiro.";
}
?>

<?php
error_reporting(0);
header("Content-Type: application/json; charset=UTF-8");

if (!isset($_GET['url'])) {
    echo json_encode(["error" => "URL parametresi eksik."]);
    exit;
}

$url = $_GET['url'];
$api_url = "https://instagram-reels-downloader-api.p.rapidapi.com/download?url=" . urlencode($url);

$headers = [
    "X-RapidAPI-Key: 568a4dcf75msh2dbbe61c1bf6f51p143d88jsn15ee0d879b6e", // senin key'in
    "X-RapidAPI-Host: instagram-reels-downloader-api.p.rapidapi.com"
];

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $api_url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTPHEADER => $headers,
]);
$response = curl_exec($ch);
curl_close($ch);

if (!$response) {
    echo json_encode(["error" => "API'den yanıt alınamadı."]);
    exit;
}

echo $response;
?>

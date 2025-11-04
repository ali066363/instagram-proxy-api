<?php
// Instagram Reels Downloader API köprü dosyası
// PHP 8+ / cURL aktif olmalı
error_reporting(0);
header("Content-Type: application/json; charset=UTF-8");

// 1️⃣ URL parametresi kontrolü
if (!isset($_GET['url']) || empty($_GET['url'])) {
    echo json_encode(["error" => "URL parametresi eksik."]);
    exit;
}

// 2️⃣ Hedef API ve parametre
$url = $_GET['url'];
$api_url = "https://instagram-reels-downloader-api.p.rapidapi.com/download?url=" . urlencode($url);

// 3️⃣ RapidAPI kimlik bilgileri
$headers = [
    "X-RapidAPI-Key: 568a4dcf75msh2dbbe61c1bf6f51p143d88jsn15ee0d879b6e",
    "X-RapidAPI-Host: instagram-reels-downloader-api.p.rapidapi.com"
];

// 4️⃣ cURL isteği oluştur
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $api_url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTPHEADER => $headers,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_TIMEOUT => 30
]);

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

// 5️⃣ Hata yönetimi
if ($error) {
    echo json_encode(["error" => "cURL hatası: " . $error]);
    exit;
}

if (!$response) {
    echo json_encode(["error" => "RapidAPI'den yanıt alınamadı."]);
    exit;
}

// 6️⃣ RapidAPI yanıtını döndür
echo $response;
?>

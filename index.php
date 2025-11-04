<?php
error_reporting(0);
header("Content-Type: application/json; charset=UTF-8");

// URL parametresi kontrolü
if (!isset($_GET['url']) || empty($_GET['url'])) {
    echo json_encode(["error" => "URL parametresi eksik."]);
    exit;
}

$url = $_GET['url'];
$api_url = "https://instagram-reels-downloader-api.p.rapidapi.com/download?url=" . urlencode($url);

// RapidAPI bilgileri
$headers = [
    "X-RapidAPI-Key: 568a4dcf75msh2dbbe61c1bf6f51p143d88jsn15ee0d879b6e",
    "X-RapidAPI-Host: instagram-reels-downloader-api.p.rapidapi.com"
];

// cURL başlat
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

// Hata veya boş yanıt kontrolü
if ($error) {
    echo json_encode(["error" => "cURL hatası: " . $error]);
    exit;
}

if (!$response) {
    echo json_encode(["error" => "API'den yanıt alınamadı veya boş döndü."]);
    exit;
}

// API yanıtını çözümle
$data = json_decode($response, true);

// Yanıt geçerli mi?
if (!$data || isset($data["message"])) {
    echo json_encode(["error" => "Instagram API yanıtı geçersiz veya erişim reddedildi."]);
    exit;
}

// Başarılı yanıtı döndür
echo json_encode([
    "status" => "ok",
    "requested_url" => $url,
    "result" => $data
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>

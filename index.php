<?php
error_reporting(0);
header("Content-Type: application/json; charset=UTF-8");

if (!isset($_GET['url'])) {
    echo json_encode(["error" => "URL parametresi eksik."]);
    exit;
}

$url = $_GET['url'];

// Instagram embed API kullanımı
$embedUrl = "https://www.instagram.com/oembed/?url=" . urlencode($url) . "&omitscript=true";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $embedUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64)");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
curl_close($ch);

if (!$response) {
    echo json_encode(["error" => "Instagram'dan veri alınamadı."]);
    exit;
}

$data = json_decode($response, true);

if (!$data || isset($data['error'])) {
    echo json_encode(["error" => "İçerik bulunamadı veya özel hesap."]);
    exit;
}

// Çıktıyı düzenle
$result = [
    "type" => "post",
    "author_name" => $data["author_name"] ?? null,
    "author_url" => $data["author_url"] ?? null,
    "title" => $data["title"] ?? null,
    "thumbnail_url" => $data["thumbnail_url"] ?? null,
    "media_url" => $data["thumbnail_url"] ?? null,
    "html" => $data["html"] ?? null
];

echo json_encode($result, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>

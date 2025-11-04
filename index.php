<?php
error_reporting(0);
header("Content-Type: application/json; charset=UTF-8");

if (!isset($_GET['url'])) {
    echo json_encode(["error" => "URL parametresi eksik."]);
    exit;
}

$url = $_GET['url'];

// Instagram sayfasını çek
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0 Safari/537.36");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$html = curl_exec($ch);
curl_close($ch);

if (!$html) {
    echo json_encode(["error" => "Instagram içeriği alınamadı."]);
    exit;
}

// meta tag'leri parse et
preg_match('/"video_url":"([^"]+)"/', $html, $video);
preg_match('/"display_url":"([^"]+)"/', $html, $image);
preg_match('/"accessibility_caption":"([^"]+)"/', $html, $caption);

$response = [];

if (!empty($video[1])) {
    $response["type"] = "video";
    $response["video_url"] = stripslashes($video[1]);
    $response["thumbnail"] = stripslashes($image[1] ?? '');
    $response["caption"] = stripslashes($caption[1] ?? '');
} elseif (!empty($image[1])) {
    $response["type"] = "image";
    $response["image_url"] = stripslashes($image[1]);
    $response["caption"] = stripslashes($caption[1] ?? '');
} else {
    $response["error"] = "İçerik bulunamadı veya özel hesap.";
}

echo json_encode($response, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>

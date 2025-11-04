<?php
error_reporting(0);
header("Content-Type: application/json; charset=UTF-8");

if (!isset($_GET['url'])) {
    echo json_encode(["error" => "URL parametresi eksik."]);
    exit;
}

$url = $_GET['url'];

// 1️⃣ Önce oEmbed ile dene
$embedUrl = "https://www.instagram.com/oembed/?url=" . urlencode($url) . "&omitscript=true";

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $embedUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 10.0; Win64; x64)"
]);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

// 2️⃣ Eğer oEmbed veri döndürmezse HTML kaynağını al
if (!$data || isset($data['error'])) {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 10.0; Win64; x64)"
    ]);
    $html = curl_exec($ch);
    curl_close($ch);

    if (!$html) {
        echo json_encode(["error" => "Instagram'dan veri alınamadı."]);
        exit;
    }

    // Sayfa içinde video veya görsel URL'si ara
    if (preg_match('/"video_url":"([^"]+)"/', $html, $m)) {
        $mediaUrl = stripslashes($m[1]);
        $type = "video";
    } elseif (preg_match('/"display_url":"([^"]+)"/', $html, $m)) {
        $mediaUrl = stripslashes($m[1]);
        $type = "image";
    } else {
        echo json_encode(["error" => "İçerik bulunamadı veya özel hesap."]);
        exit;
    }

    echo json_encode([
        "status" => "ok",
        "type" => $type,
        "media_url" => $mediaUrl,
        "requested_url" => $url
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

// 3️⃣ oEmbed başarılıysa formatla
$result = [
    "status" => "ok",
    "type" => "post",
    "author_name" => $data["author_name"] ?? null,
    "title" => $data["title"] ?? null,
    "thumbnail_url" => $data["thumbnail_url"] ?? null,
    "media_url" => $data["thumbnail_url"] ?? null,
];

echo json_encode($result, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>

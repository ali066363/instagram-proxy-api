<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_GET['url'])) {
  echo json_encode(["error" => "URL parametresi eksik."]);
  exit;
}

$url = $_GET['url'];

// Basit test çıktısı (geliştirme için)
echo json_encode([
  "status" => "ok",
  "requested_url" => $url,
  "message" => "Proxy API çalışıyor 🚀"
]);
?>

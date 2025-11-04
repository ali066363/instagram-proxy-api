<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

if (empty($_GET['url'])) {
    echo json_encode(['error' => 'Eksik parametre']);
    exit;
}

$igUrl = trim($_GET['url']);
$clean = strtok($igUrl, '?');
if (!str_ends_with($clean, '/')) $clean .= '/';
$apiUrl = $clean . '?__a=1&__d=dis';

$ch = curl_init($apiUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36',
    CURLOPT_TIMEOUT => 20,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_ENCODING => 'gzip, deflate, br'
]);

$response = curl_exec($ch);
$http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http >= 200 && $http < 400 && $response && str_contains($response, '.mp4')) {
    echo json_encode(['status' => 'ok', 'data' => $response]);
} else {
    echo json_encode(['status' => 'fail', 'http' => $http, 'raw' => substr($response, 0, 300)]);
}

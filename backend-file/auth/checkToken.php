<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$config = include(__DIR__ . '/../configs/config.php');
$secret = $config['jwt_secret'];

// 嘗試從 Authorization header 或 Cookie 拿 token
$token = null;

// 1. Authorization: Bearer ...
$headers = apache_request_headers();
if (isset($headers['Authorization']) && preg_match('/^Bearer\s(\S+)$/', $headers['Authorization'], $matches)) {
    $token = $matches[1];
}
// 2. 或從 Cookie 拿 jwt_token
// elseif (isset($_COOKIE['jwt_token'])) {
//     $token = $_COOKIE['jwt_token'];
// }

if (!$token) {
    http_response_code(401);
    echo json_encode(['error' => 'Token not provided']);
    exit;
}

// 驗證 token
try {
    $decoded = JWT::decode($token, new Key($secret, 'HS256'));
    // 全域變數：使用者資料
    $currentUser = $decoded->data;
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid token', 'message' => $e->getMessage()]);
    exit;
}

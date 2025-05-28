<?php
require_once '../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

include './cors.php';
include '../configs/db_connect.php';
$config = include('../configs/config.php');

$input = json_decode(file_get_contents("php://input"), true);
$email = $input['email'] ?? '';
$password = $input['password'] ?? '';
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// 檢查帳密（範例用法，建議用 hash 比對）
$sql = "SELECT * FROM Users WHERE Email = ?";
$stmt = sqlsrv_query($conn, $sql, [$email]);
$user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if ($user && password_verify($password, $user['Password'])) {
    $payload = [
        "iss" => "finalProj",
        "aud" => "finalProj",
        "iat" => time(),
        "exp" => time() + 3600,
        "data" => [
            "id" => $user['ID'],
            "email" => $user['Email'],
            "userName" => $user['Name'], //取得username再回傳
        ]
    ];
    $jwt = JWT::encode($payload, $config['jwt_secret'], 'HS256');
    echo json_encode(["token" => $jwt]);
} else {
    // 錯誤
    http_response_code(401);
    echo json_encode(["error" => "Invalid credentials"]);
}
?>

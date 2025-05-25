<?php
include './cors.php';
include "../configs/db_connect.php";

// 取得 POST JSON 資料
$input = json_decode(file_get_contents('php://input'), true);

// 檢查必要欄位
if (!isset($input['Name']) || !isset($input['Creator_ID'])) {
    http_response_code(400);
    echo json_encode(['error' => '缺少必要欄位 Name 或 Creator_ID']);
    exit;
}

// 準備欄位（使用 null coalescing 處理可選欄位）
$name = $input['Name'];
$color = $input['Color'] ?? '#FFFFFF';
$status = $input['Status'] ?? 'none';
$creator_id = $input['Creator_ID'];
$start_date = $input['Start_date'] ?? null;
$due_date = $input['Due_date'] ?? null;
$create_at = date('Y-m-d H:i:s'); // 自動填入目前時間

$sql = "INSERT INTO Tasks (Name, Color, Status, Creator_ID, Start_date, Due_date, Create_at)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$params = [$name, $color, $status, $creator_id, $start_date, $due_date, $create_at];
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    http_response_code(500);
    echo json_encode(['error' => '新增失敗', 'details' => sqlsrv_errors()]);
    exit;
}

echo json_encode(['success' => true, 'message' => '任務新增成功']);
?>

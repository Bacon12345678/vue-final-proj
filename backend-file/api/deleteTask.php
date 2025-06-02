<?php
include './cors.php';
include "../configs/db_connect.php";

$input = json_decode(file_get_contents('php://input'), true);

if(!isset($input['ID'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Messing task ID']);
    exit;
}

$taskID = $input['ID'];

$sql = "DELETE FROM Tasks WHERE ID = ?";
$stmt = sqlsrv_prepare($conn, $sql, [$taskID]);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'SQL prepare failed']);
    exit;
}

if (sqlsrv_execute($stmt)) {
    echo json_encode([
        'success' => true, 'message' => 'Task deleted!'
    ]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Task deletion failed']);
}

?>
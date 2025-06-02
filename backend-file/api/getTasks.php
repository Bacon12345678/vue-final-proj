<?php
    header('Content-Type: application/json'); // JSON 回應格式
    
    include './cors.php';
    include "../configs/db_connect.php";

    $sql = "SELECT * FROM Tasks";
    $query = sqlsrv_query($conn, $sql);

    $data = [];
    while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        $data[] = $row;
    }

    echo json_encode($data);
?>

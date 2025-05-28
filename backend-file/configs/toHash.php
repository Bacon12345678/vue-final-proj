<?php

include './db_connect.php';

$sql = "SELECT ID, password FROM Users";
$stmt = sqlsrv_query($conn, $sql);

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $id = $row['ID'];
    $plain = $row['password'];

    $hashed = password_hash($plain, PASSWORD_DEFAULT);

    $update = "UPDATE Users SET password = ? WHERE ID = ?";
    sqlsrv_query($conn, $update, [$hashed, $id]);
}

?>
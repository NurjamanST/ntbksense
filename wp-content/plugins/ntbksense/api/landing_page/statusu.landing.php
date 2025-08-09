<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT, PATCH, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/config.php';

// 1. AMBIL ID DARI URL, BUKAN DARI BODY
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

$ambil = mysqli_query($connection, "SELECT status FROM wp_acp_landings WHERE id = $id");
if (!$ambil) {
    http_response_code(404); // Not Found
    echo json_encode(["message" => "Data tidak ditemukan."]);
    exit;
}else {
    $data = mysqli_fetch_assoc($ambil);
    if (!$data) {
        http_response_code(404); // Not Found
        echo json_encode(["message" => "Data tidak ditemukan."]);
        exit;
    }else{
        // if the data is found successfully then update the status if the status data is the same 1 becomes 0 if the status data 0 becomes 1
        $status = $data['status'] == 1 ? 0 : 1;
        $update = mysqli_query($connection, "UPDATE wp_acp_landings SET status = $status WHERE id = $id");
        if ($update) {
            http_response_code(200); // OK
            echo json_encode(["message" => "Status berhasil diubah.", "status" => $status]);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(["message" => "Gagal mengubah status."]);
        }
    }
}



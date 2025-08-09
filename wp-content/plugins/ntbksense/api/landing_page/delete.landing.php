<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Pastikan file config.php di-include
include_once '../../config/config.php'; // $conn dari sini

// Ambil ID dari query parameter di URL (misal: .../delete.php?id=123)
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

if (!$id) {
    http_response_code(400); // Bad Request
    echo json_encode(["message" => "ID tidak ditemukan di URL."]);
    exit;
}

// Query menggunakan placeholder '?' untuk MySQLi
$query = "DELETE FROM wp_acp_landings WHERE id = ?";

// Prepare statement menggunakan fungsi MySQLi
$stmt = mysqli_prepare($connection, $query);

if ($stmt) {
    // Bind parameter 'i' untuk integer
    mysqli_stmt_bind_param($stmt, 'i', $id);

    // Eksekusi statement
    if (mysqli_stmt_execute($stmt)) {
        http_response_code(200); // OK
        echo json_encode(["message" => "Data dengan ID {$id} berhasil dihapus."]);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(["message" => "Gagal menghapus data."]);
    }

    // Tutup statement
    mysqli_stmt_close($stmt);
} else {
    http_response_code(500);
    // Tambahkan log error untuk debugging jika perlu
    // error_log("MySQLi prepare failed: " . mysqli_error($conn));
    echo json_encode(["message" => "Gagal menyiapkan query."]);
}
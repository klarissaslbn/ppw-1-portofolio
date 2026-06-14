<?php
session_start();

$host = "localhost";
$username = "root";
$password = "";
$database = "praktikum_crud";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

function uploadFile($file, $target_dir = "uploads/mahasiswa/") {
    $imageFileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

    if ($file["size"] > 5000000)
        return ['success' => false, 'message' => 'File terlalu besar. Maks 5MB.'];

    $allowed = ["jpg","jpeg","png","gif"];
    if (!in_array($imageFileType, $allowed))
        return ['success' => false, 'message' => 'Format tidak didukung.'];

    $new_filename = uniqid() . "." . $imageFileType;
    $target_file = $target_dir . $new_filename;

    if (move_uploaded_file($file["tmp_name"], $target_file))
        return ['success' => true, 'filename' => $new_filename];

    return ['success' => false, 'message' => 'Gagal upload file.'];
}

function deleteFile($filename, $dir = "uploads/mahasiswa/") {
    if ($filename && file_exists($dir . $filename))
        unlink($dir . $filename);
}
?>
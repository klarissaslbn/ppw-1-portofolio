<?php
include_once("config.php");
requireLogin();

if (!isset($_GET['id'])) { header('Location: index.php'); exit(); }
$id = (int)$_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE id=$id");
if (mysqli_num_rows($result) == 0) { header('Location: index.php'); exit(); }
$row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Detail Mahasiswa</title>
    <style>
        body { font-family: Arial; background: #f0f2f5; margin: 0; }
        .container { max-width: 550px; margin: 40px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; }
        .foto-besar { width: 200px; height: 200px; object-fit: cover; border-radius: 50%; border: 4px solid #4CAF50; margin-bottom: 20px; }
        .no-foto { width: 200px; height: 200px; border-radius: 50%; background: #ddd; display: flex; align-items: center; justify-content: center; font-size: 16px; color: #777; margin: 0 auto 20px; }
        table { width: 100%; border-collapse: collapse; text-align: left; margin-top: 10px; }
        td { padding: 10px 8px; border-bottom: 1px solid #eee; font-size: 14px; }
        td:first-child { font-weight: bold; color: #555; width: 40%; }
        .btn { display: inline-block; padding: 9px 18px; border-radius: 4px; text-decoration: none; color: white; font-size: 14px; margin: 5px; }
        .btn-secondary { background: #9E9E9E; }
        .btn-warning   { background: #FF9800; }
        h2 { margin-top: 0; }
    </style>
</head>
<body>
<div class="container">
    <h2>Detail Mahasiswa</h2>

    <?php if ($row['foto']): ?>
        <img src="uploads/mahasiswa/<?= $row['foto'] ?>" class="foto-besar" alt="Foto Mahasiswa">
    <?php else: ?>
        <div class="no-foto">Tidak ada foto</div>
    <?php endif; ?>

    <table>
        <tr><td>NIM</td><td><?= htmlspecialchars($row['nim']) ?></td></tr>
        <tr><td>Nama</td><td><?= htmlspecialchars($row['nama']) ?></td></tr>
        <tr><td>Jurusan</td><td><?= htmlspecialchars($row['jurusan']) ?></td></tr>
        <tr><td>Email</td><td><?= htmlspecialchars($row['email']) ?></td></tr>
        <tr><td>Alamat</td><td><?= htmlspecialchars($row['alamat'] ?: '-') ?></td></tr>
        <tr><td>Tanggal Daftar</td><td><?= date('d F Y, H:i', strtotime($row['created_at'])) ?></td></tr>
    </table>

    <div style="margin-top: 20px;">
        <a href="index.php" class="btn btn-secondary">← Kembali</a>
        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning">Edit Data</a>
    </div>
</div>
</body>
</html>
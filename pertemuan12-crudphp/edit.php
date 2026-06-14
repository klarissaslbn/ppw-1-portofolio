<?php
include_once("config.php");
requireLogin();

if (!isset($_GET['id'])) { header('Location: index.php'); exit(); }
$id = (int)$_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE id=$id");
if (mysqli_num_rows($result) == 0) { header('Location: index.php'); exit(); }
$row = mysqli_fetch_assoc($result);
$current_foto = $row['foto'];

$errors = [];

if (isset($_POST['update'])) {
    $nim     = mysqli_real_escape_string($conn, $_POST['nim']);
    $nama    = mysqli_real_escape_string($conn, $_POST['nama']);
    $jurusan = mysqli_real_escape_string($conn, $_POST['jurusan']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $alamat  = mysqli_real_escape_string($conn, $_POST['alamat']);
    $foto_filename = $current_foto;

    // ── VALIDASI TUGAS: NIM hanya angka, 8-12 karakter ──
    if (empty($nim)) {
        $errors[] = 'NIM tidak boleh kosong';
    } elseif (!preg_match('/^[0-9]{8,12}$/', $nim)) {
        if (!preg_match('/^[0-9]+$/', $nim))
            $errors[] = 'NIM hanya boleh berisi angka, tidak boleh mengandung huruf';
        else
            $errors[] = 'Panjang NIM harus 8 sampai 12 digit';
    }

    if (empty($nama))    $errors[] = 'Nama tidak boleh kosong';
    if (empty($jurusan)) $errors[] = 'Jurusan tidak boleh kosong';
    if (empty($email))   $errors[] = 'Email tidak boleh kosong';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid';

    // Cek NIM duplikat (kecuali data diri sendiri)
    $chk = mysqli_query($conn, "SELECT nim FROM mahasiswa WHERE nim='$nim' AND id != $id");
    if (mysqli_num_rows($chk) > 0) $errors[] = 'NIM sudah dipakai mahasiswa lain';

    // Upload foto baru jika ada
    if (!empty($_FILES['foto']['name'])) {
        $upload = uploadFile($_FILES['foto']);
        if ($upload['success']) {
            if ($current_foto) deleteFile($current_foto);
            $foto_filename = $upload['filename'];
        } else {
            $errors[] = $upload['message'];
        }
    }

    // Hapus foto jika dicentang
    if (isset($_POST['hapus_foto']) && $_POST['hapus_foto'] == '1') {
        if ($current_foto) deleteFile($current_foto);
        $foto_filename = null;
    }

    if (empty($errors)) {
        $foto_sql = $foto_filename ? "'$foto_filename'" : 'NULL';
        $sql = "UPDATE mahasiswa SET nim='$nim', nama='$nama', jurusan='$jurusan', email='$email', alamat='$alamat', foto=$foto_sql WHERE id=$id";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = 'Data berhasil diperbarui!';
            header('Location: index.php');
            exit();
        } else {
            $errors[] = 'Error: ' . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Data</title>
    <style>
        body { font-family: Arial; background: #f0f2f5; margin: 0; }
        .container { max-width: 600px; margin: 30px auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { margin-top: 0; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; font-size: 14px; }
        input[type=text], input[type=email], textarea, input[type=file] { width: 100%; padding: 9px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; font-size: 14px; }
        textarea { height: 80px; resize: vertical; }
        .required { color: red; }
        .error { color: red; background: #fff3f3; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .btn { padding: 9px 18px; border-radius: 4px; text-decoration: none; color: white; border: none; cursor: pointer; font-size: 14px; margin-right: 8px; }
        .btn-primary   { background: #FF9800; }
        .btn-secondary { background: #9E9E9E; }
        .current-photo { display: flex; align-items: center; gap: 15px; background: #f9f9f9; padding: 10px; border-radius: 6px; margin-bottom: 10px; }
        .current-photo img { width: 70px; height: 70px; object-fit: cover; border-radius: 50%; }
        small { color: #999; font-size: 12px; }
    </style>
</head>
<body>
<div class="container">
    <h2>Edit Data Mahasiswa</h2>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $e): ?><p style="margin:4px 0">⚠️ <?= $e ?></p><?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Foto Profil</label>
            <?php if ($current_foto): ?>
            <div class="current-photo">
                <img src="uploads/mahasiswa/<?= $current_foto ?>" alt="Foto saat ini">
                <div>
                    <p style="margin:0; font-size:13px;">Foto saat ini</p>
                    <label style="font-weight:normal; font-size:13px;">
                        <input type="checkbox" name="hapus_foto" value="1"> Hapus foto ini
                    </label>
                </div>
            </div>
            <?php endif; ?>
            <input type="file" name="foto" accept="image/*">
            <small>Biarkan kosong jika tidak ingin ganti foto</small>
        </div>
        <div class="form-group">
            <label>NIM <span class="required">*</span></label>
            <input type="text" name="nim" required value="<?= isset($_POST['nim']) ? htmlspecialchars($_POST['nim']) : htmlspecialchars($row['nim']) ?>">
            <small>Hanya angka, 8-12 digit</small>
        </div>
        <div class="form-group">
            <label>Nama Lengkap <span class="required">*</span></label>
            <input type="text" name="nama" required value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : htmlspecialchars($row['nama']) ?>">
        </div>
        <div class="form-group">
            <label>Jurusan <span class="required">*</span></label>
            <input type="text" name="jurusan" required value="<?= isset($_POST['jurusan']) ? htmlspecialchars($_POST['jurusan']) : htmlspecialchars($row['jurusan']) ?>">
        </div>
        <div class="form-group">
            <label>Email <span class="required">*</span></label>
            <input type="email" name="email" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : htmlspecialchars($row['email']) ?>">
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat"><?= isset($_POST['alamat']) ? htmlspecialchars($_POST['alamat']) : htmlspecialchars($row['alamat']) ?></textarea>
        </div>
        <button type="submit" name="update" class="btn btn-primary">Update Data</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>
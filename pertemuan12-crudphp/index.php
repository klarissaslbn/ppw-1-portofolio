<?php
include_once("config.php");
requireLogin();

$limit  = 5;
$page   = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
$offset = ($page - 1) * $limit;
$search = isset($_GET["search"]) ? mysqli_real_escape_string($conn, $_GET["search"]) : "";
$where  = "";

if (!empty($search)) {
    $where = "WHERE nim LIKE '%$search%' OR nama LIKE '%$search%' OR jurusan LIKE '%$search%' OR email LIKE '%$search%'";
}

$count_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM mahasiswa $where");
$total_data   = mysqli_fetch_assoc($count_result)["total"];
$total_pages  = ceil($total_data / $limit);

$result = mysqli_query($conn, "SELECT * FROM mahasiswa $where ORDER BY id DESC LIMIT $limit OFFSET $offset");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Mahasiswa</title>
    <style>
        body { font-family: Arial; margin: 0; background: #f0f2f5; }
        .container { max-width: 1000px; margin: 30px auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { margin-top: 0; }
        .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 10px; }
        input[type=text] { padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 220px; }
        .btn { padding: 8px 14px; border-radius: 4px; text-decoration: none; color: white; border: none; cursor: pointer; font-size: 14px; }
        .btn-primary  { background: #4CAF50; }
        .btn-warning  { background: #FF9800; }
        .btn-danger   { background: #f44336; }
        .btn-info     { background: #2196F3; }
        .btn-secondary{ background: #9E9E9E; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; font-size: 14px; }
        th { background: #f5f5f5; }
        .photo { width: 50px; height: 50px; object-fit: cover; border-radius: 50%; }
        .no-photo { width: 50px; height: 50px; background: #ddd; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; color: #777; }
        .pagination { margin-top: 15px; display: flex; gap: 5px; }
        .pagination a { padding: 7px 12px; border: 1px solid #ddd; border-radius: 4px; text-decoration: none; color: #333; }
        .pagination a.current { background: #4CAF50; color: white; border-color: #4CAF50; }
        .alert { padding: 10px 15px; border-radius: 4px; margin-bottom: 15px; }
        .alert-success { background: #dff0d8; color: #3c763d; }
        .alert-danger  { background: #f2dede; color: #a94442; }
        .user-info { font-size: 14px; color: #555; }
    </style>
</head>
<body>
<div class="container">
    <div class="topbar">
        <h2>Data Mahasiswa</h2>
        <span class="user-info">Halo, <?= htmlspecialchars($_SESSION['full_name']) ?> | <a href="logout.php">Logout</a></span>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="topbar">
        <a href="tambah.php" class="btn btn-primary">+ Tambah Data</a>
        <form method="GET" style="display:flex; gap:5px;">
            <input type="text" name="search" placeholder="Cari NIM, nama, jurusan..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-info">Cari</button>
            <?php if ($search): ?><a href="index.php" class="btn btn-secondary">Reset</a><?php endif; ?>
        </form>
    </div>

    <table>
        <thead>
            <tr><th>Foto</th><th>NIM</th><th>Nama</th><th>Jurusan</th><th>Email</th><th>Aksi</th></tr>
        </thead>
        <tbody>
        <?php if (mysqli_num_rows($result) == 0): ?>
            <tr><td colspan="6" style="text-align:center; color:#999;">Tidak ada data</td></tr>
        <?php else: ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td>
                    <?php if ($row['foto']): ?>
                        <img src="uploads/mahasiswa/<?= $row['foto'] ?>" class="photo" alt="Foto">
                    <?php else: ?>
                        <div class="no-photo">N/A</div>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($row['nim']) ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['jurusan']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td style="display:flex; gap:5px;">
                    <a href="detail.php?id=<?= $row['id'] ?>" class="btn btn-info">Detail</a>
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning">Edit</a>
                    <a href="hapus.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus data ini?')" class="btn btn-danger">Hapus</a>
                </td>
            </tr>
        <?php endwhile; ?>
        <?php endif; ?>
        </tbody>
    </table>

    <?php if ($total_pages > 1): ?>
    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>" class="<?= $i == $page ? 'current' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>
</body>
</html>
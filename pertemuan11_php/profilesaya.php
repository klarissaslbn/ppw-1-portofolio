<?php
$nama = "Klarissa Dyta Silaban";
$nim = "25/562109/SV/26741";
$prodi = "Teknologi Rekayasa Perangkat Lunak";
$kota = "Medan";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Mahasiswa</title>
</head>
<body>

<h2>Profil Saya</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>Data</th>
        <th>Informasi</th>
    </tr>
    <tr>
        <td>Nama</td>
        <td><?php echo $nama; ?></td>
    </tr>
    <tr>
        <td>NIM</td>
        <td><?php echo $nim; ?></td>
    </tr>
    <tr>
        <td>Prodi</td>
        <td><?php echo $prodi; ?></td>
    </tr>
    <tr>
        <td>Asal Kota</td>
        <td><?php echo $kota; ?></td>
    </tr>
</table>

</body>
</html>
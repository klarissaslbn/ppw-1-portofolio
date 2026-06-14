<?php
$bulanSekarang = date("F"); 
$hariIni = date("j");       
$totalHari = date("t");  

$sisaHari = $totalHari - $hariIni;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Info Bulan</title>
</head>
<body>

<h2>Informasi Bulan Saat Ini</h2>

<p>Bulan saat ini: <?php echo $bulanSekarang; ?></p>
<p>Tanggal hari ini: <?php echo $hariIni; ?></p>
<p>Total hari dalam bulan: <?php echo $totalHari; ?></p>
<p>Sisa hari di bulan ini: <?php echo $sisaHari; ?> hari</p>

</body>
</html>
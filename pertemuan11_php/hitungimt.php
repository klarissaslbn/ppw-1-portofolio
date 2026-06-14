<?php
function hitungIMT($berat, $tinggi) {
    $imt = $berat / ($tinggi * $tinggi);

    if ($imt < 18.5) {
        $kategori = "Kurus";
    } elseif ($imt >= 18.5 && $imt < 25) {
        $kategori = "Normal";
    } elseif ($imt >= 25 && $imt < 30) {
        $kategori = "Gemuk";
    } else {
        $kategori = "Obesitas";
    }

    return array($imt, $kategori);
}

$berat = 60; 
$tinggi = 1.53; 

list($nilaiIMT, $kategori) = hitungIMT($berat, $tinggi);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hitung IMT</title>
</head>
<body>

<h2>Hasil IMT</h2>

<p>Berat: <?php echo $berat; ?> kg</p>
<p>Tinggi: <?php echo $tinggi; ?> m</p>
<p>Nilai IMT: <?php echo number_format($nilaiIMT, 2); ?></p>
<p>Kategori: <b><?php echo $kategori; ?></b></p>

</body>
</html>
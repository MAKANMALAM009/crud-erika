<?php
session_start();

// membatasi halaman login
if (!isset($_SESSION["login"])) {
    echo "<script>
        alert('Login Dulu Bah!!');
        document.location.href='login.php';
    </script>";
    exit;
}

// membatasi halaman sesuai user login
if ($_SESSION["level"] != 1 && $_SESSION["level"] != 3) {
    echo "<script>
        alert('Perhatian Anda Tidak Punya Hak Akses!!');
        document.location.href='akun.php';
    </script>";
    exit;
}

require __DIR__ . '/vendor/autoload.php';
require 'config/app.php';

use Spipu\Html2Pdf\Html2Pdf;

// Ambil data mahasiswa
$data_mahasiswa = select("SELECT * FROM mahasiswa");

// Mulai isi HTML
$content = '<style type="text/css">
    .gambar {
        width: 50px;
        height: auto;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 6px;
        text-align: center;
    }
</style>';

$content .= '<page>
    <h3 align="center">LAPORAN DATA MAHASISWA</h3>
    <table border="1" align="center">
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Program Studi</th>
            <th>Jenis Kelamin</th>
            <th>Telepon</th>
            <th>Email</th>
            <th>Foto</th>
        </tr>';

$no = 1;
foreach ($data_mahasiswa as $mhs) {
    $foto = isset($mhs['foto']) ? trim($mhs['foto']) : '';
    $imgPath = 'assets/img/' . $foto;

    // Cek validitas file
    $imgTag = 'Tidak Ada Gambar';
    $ext = strtolower(pathinfo($imgPath, PATHINFO_EXTENSION));
    $allowedExt = ['jpg', 'jpeg', 'gif', 'bmp'];

    if (!empty($foto) && file_exists($imgPath) && in_array($ext, $allowedExt)) {
        $imgTag = '<img src="' . $imgPath . '" class="gambar">';
    } elseif (file_exists('assets/img/default.jpg')) {
        $imgTag = '<img src="assets/img/default.jpg" class="gambar">';
    }

    $content .= '
        <tr>
            <td>' . $no++ . '</td>
            <td>' . htmlspecialchars($mhs['nama']) . '</td>
            <td>' . htmlspecialchars($mhs['prodi']) . '</td>
            <td>' . htmlspecialchars($mhs['jk']) . '</td>
            <td>' . htmlspecialchars($mhs['telepon']) . '</td>
            <td>' . htmlspecialchars($mhs['email']) . '</td>
            <td>' . $imgTag . '</td>
        </tr>';
}

$content .= '
    </table>
</page>';

// Generate PDF
$html2pdf = new Html2Pdf();
$html2pdf->writeHTML($content);
$html2pdf->output('Laporan-mahasiswa.pdf');
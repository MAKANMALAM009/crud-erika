<?php 
session_start();
// membatasi halaman sebelum login
if (!isset($_SESSION["login"])) {
    echo "<script>
            alert('login dulu');
            document.location.href = 'login.php';
          </script>";
    exit;
}

include 'config/app.php';

// menerima id mahasiswa yang dipilih pengguna
$id_mahasiswa =(int)$_GET['id_mahasiswa'];

if (delete_mahasiswa($id_mahasiswa) > 0) {
    echo "<script>
            alert('Data mahasiswa berhasil dihapus!');
            document.location.href = 'mahasiswa.php';
            </script>";
} else {
    echo "<script>
            alert('Data mahasiswa gagal dihapus!');
            document.location.href = 'mahasiswa.php';
            </script>";
}
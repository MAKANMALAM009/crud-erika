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

// menerima id barang yang dipilih pengguna
$id_akun =(int)$_GET['id_akun'];

if (delete_akun($id_akun) > 0) {
    echo "<script>
            alert('Data barang berhasil dihapus!');
            document.location.href = 'crud-modal.php';
            </script>";
} else {
    echo "<script>
            alert('Data barang gagal dihapus!');
            document.location.href = 'crud-modal.php';
            </script>";
}
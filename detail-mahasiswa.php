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

$title = 'Detail Mahasiswa';
include 'layout/header.php';

// mengambil id mahasiswa dari URL
$id_mahasiswa = (int)$_GET['id_mahasiswa'];

// mengambil data mahasiswa dari database
$mahasiswa = select("SELECT * FROM mahasiswa WHERE id_mahasiswa = $id_mahasiswa")[0];

?>

<div class="container mt-5">
    <h1>Data <?= htmlspecialchars($mahasiswa['nama']); ?></h1>
    <hr>

    <table class="table table-bordered table-striped mt-3" style="max-width: 600px;">
        <tr>
            <th>Nama</th>
            <td><?= htmlspecialchars($mahasiswa['nama']); ?></td>
        </tr>
        <tr>
            <th>Program Studi</th>
            <td><?= htmlspecialchars($mahasiswa['prodi']); ?></td>
        </tr>
        <tr>
            <th>Jenis Kelamin</th>
            <td><?= htmlspecialchars($mahasiswa['jk']); ?></td>
        </tr>
        <tr>
            <th>Telepon</th>
            <td><?= htmlspecialchars($mahasiswa['telepon']); ?></td>
        </tr>

        <tr>
            <th>Alamat</th>
            <td><?= $mahasiswa['alamat']; ?></td>
        </tr>

        <tr>
            <th>Email</th>
            <td><?= htmlspecialchars($mahasiswa['email']); ?></td>
        </tr>
        
        <tr>
            <th>Foto</th>
            <td>
                <a href="assets/img/<?= htmlspecialchars($mahasiswa['foto']); ?>" target="_blank">
                    <img src="assets/img/<?= htmlspecialchars($mahasiswa['foto']); ?>" alt="foto" width="150">
                </a>
            </td>
        </tr>
    </table>

    <a href="mahasiswa.php" class="btn btn-secondary btn-sm float-end">Kembali</a>
</div>

<?php include 'layout/footer.php'; ?>
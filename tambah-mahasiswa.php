<?php 
session_start();

if (!isset($_SESSION["login"])) {
    echo "<script>
            alert('Login Dulu Bah!!');
            document.location.href='login.php';
        </script>";
    exit;
}

$title = 'Tambah Mahasiswa';

include 'layout/header.php'; 

if (isset($_POST['tambah'])) {
    if (create_mahasiswa($_POST) > 0) {
        echo "<script>
                alert('Data Mahasiswa Berhasil Ditambahkan');
                document.location.href = 'mahasiswa.php';
              </script>";
    } else {
        echo "<script>
                alert('Data Mahasiswa Gagal Ditambahkan');
                document.location.href = 'mahasiswa.php';
              </script>"; 
    }
}
?>

<div class="container mt-5">
    <h1>Tambah Mahasiswa</h1>
    <hr>

    <form action="" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Mahasiswa</label>
            <input type="text" class="form-control" id="nama" name="nama" required>        
        </div>

        <div class="mb-3">
            <label for="prodi" class="form-label">Program Studi</label>
            <select name="prodi" id="prodi" class="form-control" required>
                <option value="Teknik Informatika">Teknik Informatika</option>
                <option value="Teknik Mesin">Teknik Mesin</option>
                <option value="Teknik Kimia">Teknik Kimia</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="jk" class="form-label">Jenis Kelamin</label>
            <select name="jk" id="jk" class="form-control" required>
                <option value="Laki-Laki">Laki-Laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="telepon" class="form-label">Telepon</label>
            <input type="number" class="form-control" id="telepon" name="telepon" required>
        </div>

        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea name="alamat" id="alamat"></textarea>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>        
        </div>

        <div class="mb-3">
            <label for="foto" class="form-label">Foto</label>
            <input type="file" class="form-control" id="foto" name="foto" required>
        </div>

        <input type="submit" class="btn btn-primary" name="tambah" value="Tambah">
    </form>
</div>

<!-- CKEditor + CKFinder -->
<!-- Load CKFinder dari folder assets -->
<script src="assets/ckfinder/ckfinder.js"></script>
<!-- Load CKEditor -->
<script src="https://cdn.ckeditor.com/4.16.2/full/ckeditor.js"></script>
<script>
    var editor = CKEDITOR.replace('alamat', {
        filebrowserBrowseUrl: 'assets/ckfinder/ckfinder.html',
        filebrowserImageBrowseUrl: 'assets/ckfinder/ckfinder.html?type=Images',
        filebrowserUploadUrl: 'assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl: 'assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images'
    });
    CKFinder.setupCKEditor(editor, 'assets/ckfinder/');
</script>

<?php include 'layout/footer.php'; ?>
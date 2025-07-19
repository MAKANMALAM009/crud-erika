<?php
ob_start();
session_start();

if (!isset($_SESSION["login"])) {
  header("Location: login.php");
  exit;
}

if ($_SESSION["level"] != 1 and $_SESSION["level"] != 2) {
  header("Location: crud-modal.php");
  exit;
}

$title = 'Daftar Barang';
include 'layout/header.php';

$grafik_data = select("SELECT nama, jumlah, harga FROM barang ORDER BY id_barang ASC");

$nama_barang = $jumlah_barang = $harga_barang = [];
foreach ($grafik_data as $data) {
  $nama_barang[] = $data['nama'];
  $jumlah_barang[] = (int)$data['jumlah'];
  $harga_barang[] = (int)$data['harga'];
}

$jumlahDataPerhalaman = 1;
$halamanAktif = isset($_GET["halaman"]) ? (int)$_GET['halaman'] : 1;
$awalData = ($jumlahDataPerhalaman * $halamanAktif) - $jumlahDataPerhalaman;

$data_barang = [];
$jumlahHalaman = 1;

if (isset($_POST['filter'])) {
  $tgl_awal = strip_tags($_POST['tgl_awal'] . " 00:00:00");
  $tgl_akhir = strip_tags($_POST['tgl_akhir'] . " 23:59:59");
  $data_barang = select("SELECT * FROM barang WHERE tanggal BETWEEN '$tgl_awal' AND '$tgl_akhir' ORDER BY id_barang DESC");
} else {
  $jumlahData = count(select("SELECT * FROM barang"));
  $jumlahHalaman = ceil($jumlahData / $jumlahDataPerhalaman);
  $data_barang = select("SELECT * FROM barang ORDER BY id_barang DESC LIMIT $awalData, $jumlahDataPerhalaman");
}
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Dashboard</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <section class="content">
    <div class="container-fluid">

      <!-- Grafik -->
      <div class="row mb-4">
        <div class="col-md-6">
          <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
              <h3 class="card-title"><i class="fas fa-chart-bar"></i> Grafik Jumlah Barang</h3>
            </div>
            <div class="card-body">
              <canvas id="grafikJumlah" height="200"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
              <h3 class="card-title"><i class="fas fa-chart-line"></i> Grafik Harga Barang</h3>
            </div>
            <div class="card-body">
              <canvas id="grafikHarga" height="200"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabel -->
      <div class="row">
        <div class="col-12">
          <div class="card shadow-sm rounded">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
              <h3 class="card-title m-0"><i class="nav-icon fas fa-box"></i> Data Barang</h3>
              <div>
                <a href="tambah-barang.php" class="btn btn-info btn-sm rounded-pill text-white">Tambah</a>
                <button type="button" class="btn btn-success btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#modalFilter">
                  <i class="fas fa-search"></i> Filter
                </button>
                <a href="index.php" class="btn btn-danger btn-sm rounded-pill">
                  <i class="fas fa-undo"></i> Reset Filter
                </a>
              </div>
            </div>

            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                  <thead class="thead-dark">
                    <tr>
                      <th>No</th>
                      <th>Nama</th>
                      <th>Jumlah</th>
                      <th>Harga</th>
                      <th>Barcode</th>
                      <th>Tanggal</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $no = 1;
                    foreach ($data_barang as $barang): ?>
                      <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($barang['nama']); ?></td>
                        <td><?= $barang['jumlah']; ?></td>
                        <td>Rp. <?= number_format($barang['harga'], 0, ',', '.'); ?></td>
                        <td class="text-center">
                          <img src="barcode.php?codetype=Code128&size=15&text=<?= htmlspecialchars($barang['barcode']); ?>&print=true" alt="barcode">
                           <td>
                        <?php if (!empty($barang['tanggal'])): ?>
                            <?= date("d/m/Y | H:i:s", strtotime($barang['tanggal'])) ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td width="15%" class="text-center">
                        <a href="ubah-barang.php?id_barang=<?= $barang['id_barang']; ?>" class="btn btn-success btn-sm">Ubah</a>
                        <a href="hapus-barang.php?id_barang=<?= $barang['id_barang']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin?')">Hapus</a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>

              <!-- Pagination -->
              <?php if (!isset($_POST['filter'])): ?>
                <nav class="mt-3">
                  <ul class="pagination justify-content-center">
                    <?php if ($halamanAktif > 1): ?>
                      <li class="page-item"><a class="page-link" href="?halaman=<?= $halamanAktif - 1 ?>">&laquo;</a></li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $jumlahHalaman; $i++): ?>
                      <li class="page-item <?= $i == $halamanAktif ? 'active' : '' ?>">
                        <a class="page-link" href="?halaman=<?= $i ?>"><?= $i ?></a>
                      </li>
                    <?php endfor; ?>
                    <?php if ($halamanAktif < $jumlahHalaman): ?>
                      <li class="page-item"><a class="page-link" href="?halaman=<?= $halamanAktif + 1 ?>">&raquo;</a></li>
                    <?php endif; ?>
                  </ul>
                </nav>
              <?php endif; ?>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Modal Filter -->
  <div class="modal fade" id="modalFilter" tabindex="-1" aria-labelledby="modalFilterLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form action="" method="post" class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title"><i class="fas fa-search"></i> Filter Tanggal</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Tanggal Awal</label>
            <input type="date" name="tgl_awal" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Tanggal Akhir</label>
            <input type="date" name="tgl_akhir" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="filter" class="btn btn-primary">Filter</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include 'layout/footer.php'; ?>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const namaBarang = <?= json_encode($nama_barang); ?>;
  const jumlahBarang = <?= json_encode($jumlah_barang); ?>;
  const hargaBarang = <?= json_encode($harga_barang); ?>;

  new Chart(document.getElementById('grafikJumlah'), {
    type: 'bar',
    data: {
      labels: namaBarang,
      datasets: [{
        label: 'Jumlah',
        data: jumlahBarang,
        backgroundColor: 'rgba(255, 159, 64, 0.7)',
        borderColor: 'rgba(255, 159, 64, 1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: 'Jumlah'
          }
        }
      }
    }
  });

  new Chart(document.getElementById('grafikHarga'), {
    type: 'line',
    data: {
      labels: namaBarang,
      datasets: [{
        label: 'Harga (Rp)',
        data: hargaBarang,
        backgroundColor: 'rgba(75, 192, 192, 0.3)',
        borderColor: 'rgba(75, 192, 192, 1)',
        fill: true,
        tension: 0.4
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: val => 'Rp ' + val.toLocaleString('id-ID')
          }
        }
      },
      plugins: {
        tooltip: {
          callbacks: {
            label: ctx => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID')
          }
        }
      }
    }
  });
</script>
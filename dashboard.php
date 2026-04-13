<?php
include 'config.php';
session_start();

// Proteksi halaman
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// 1. Query Transaksi Hari Ini
$today = date('Y-m-d');
$query_transaksi_hari_ini = mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi WHERE DATE(tanggal_transaksi) = '$today'");
$data_t_hari_ini = mysqli_fetch_assoc($query_transaksi_hari_ini);
$total_hari_ini = $data_t_hari_ini['total'];

// 2. Query Total Semua Produk
$query_produk = mysqli_query($conn, "SELECT COUNT(*) as total FROM barang");
$data_produk = mysqli_fetch_assoc($query_produk);
$total_produk = $data_produk['total'];

// 3. Query Stok Hampir Habis (dibawah 5)
$query_stok_low = mysqli_query($conn, "SELECT * FROM barang WHERE jumlah_barang < 5");
$jumlah_stok_low = mysqli_num_rows($query_stok_low);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Aplikasi Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .cursor-pointer { cursor: pointer; transition: 0.3s; }
        .cursor-pointer:hover { transform: translateY(-3px); }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="content" id="content">
    <nav class="navbar navbar-expand-lg navbar-light bg-white mb-4 shadow-sm rounded p-3">
        <div class="container-fluid">
            <button class="btn btn-primary d-md-none" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <div class="ms-auto d-flex align-items-center">
                <span class="me-3 text-muted">User: <strong><?php echo $username; ?></strong></span>
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <?php echo strtoupper(substr($username, 0, 1)); ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <h3 class="fw-bold text-dark mb-4">Ringkasan Penjualan</h3>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card p-3 bg-white border-0 border-start border-primary border-5 shadow-sm">
                    <div class="row align-items-center">
                        <div class="col">
                            <p class="text-uppercase text-primary fw-bold small mb-1">Transaksi Hari Ini</p>
                            <h4 class="fw-bold mb-0"><?php echo $total_hari_ini; ?></h4>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-calendar2-check text-secondary fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-3 bg-white border-0 border-start border-success border-5 shadow-sm">
                    <div class="row align-items-center">
                        <div class="col">
                            <p class="text-uppercase text-success fw-bold small mb-1">Total Produk</p>
                            <h4 class="fw-bold mb-0"><?php echo $total_produk; ?></h4>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-box text-secondary fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card p-3 bg-white border-0 border-start border-warning border-5 shadow-sm cursor-pointer" 
                     data-bs-toggle="modal" data-bs-target="#modalStokRendah">
                    <div class="row align-items-center">
                        <div class="col">
                            <p class="text-uppercase text-warning fw-bold small mb-1">Stok Hampir Habis</p>
                            <h4 class="fw-bold mb-0"><?php echo $jumlah_stok_low; ?> Item</h4>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-exclamation-triangle text-secondary fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-5 shadow-sm border-0">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="m-0 fw-bold text-primary">Riwayat Transaksi Terbaru (10 Terakhir)</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>ID Transaksi</th>
                                <th>Waktu</th>
                                <th>Total</th>
                                <th>Kasir</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $query_history = mysqli_query($conn, "SELECT * FROM transaksi ORDER BY id_transaksi DESC LIMIT 10");
                            while($row = mysqli_fetch_assoc($query_history)) {
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td class="fw-bold">#<?php echo $row['id_transaksi']; ?></td>
                                <td><?php echo date('H:i', strtotime($row['tanggal_transaksi'])); ?></td>
                                <td>Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                <td><?php echo $row['id_user']; ?></td>
                                <td><span class="badge bg-success text-white">Selesai</span></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalStokRendah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Peringatan Stok Rendah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">Produk berikut memiliki stok kurang dari 5 unit:</p>
                <ul class="list-group list-group-flush">
                    <?php 
                    // Reset pointer query stok low atau query ulang
                    mysqli_data_seek($query_stok_low, 0); 
                    if(mysqli_num_rows($query_stok_low) > 0) {
                        while($s = mysqli_fetch_assoc($query_stok_low)) {
                            echo '<li class="list-group-item d-flex justify-content-between align-items-center">
                                    '.$s['nama_barang'].'
                                    <span class="badge bg-danger rounded-pill">Sisa: '.$s['jumlah_barang'].'</span>
                                  </li>';
                        }
                    } else {
                        echo '<li class="list-group-item text-center text-muted italic">Tidak ada stok yang kritis.</li>';
                    }
                    ?>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                <a href="data_barang.php" class="btn btn-primary btn-sm">Update Stok</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
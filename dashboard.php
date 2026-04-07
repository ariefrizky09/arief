<?php
include 'config.php';
session_start();

// Proteksi halaman
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Query Transaksi Hari Ini (Versi PHP 5 menggunakan isset() sebagai pengganti ??)
$today = date('Y-m-d');
$query_transaksi = mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi WHERE DATE(tanggal_transaksi) = '$today'");
$data_transaksi = mysqli_fetch_assoc($query_transaksi);

// Perbaikan untuk PHP 5: Menggunakan ternary operator biasa
$total_hari_ini = (isset($data_transaksi['total'])) ? $data_transaksi['total'] : 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Penjualan | PHP 5 Version</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --primary-color: #4e73df; --sidebar-width: 250px; }
        body { background-color: #f8f9fc; font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            background: var(--primary-color);
            transition: all 0.3s;
            z-index: 1000;
        }
        .sidebar a {
            color: rgba(255,255,255,0.8);
            padding: 15px 25px;
            display: block;
            text-decoration: none;
        }
        .sidebar a:hover, .sidebar a.active {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }
        
        .content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            min-height: 100vh;
            transition: all 0.3s;
        }

        /* Responsive Mobile */
        @media (max-width: 768px) {
            .sidebar { margin-left: -250px; }
            .sidebar.show { margin-left: 0; }
            .content { margin-left: 0; }
        }

        .card-custom {
            border: none;
            border-left: 5px solid var(--primary-color);
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

<div class="sidebar" id="sidebar">
    <div class="text-white text-center py-4">
        <h4 class="fw-bold">Aplikasi Penjualan</h4>
        <small>v1.0 (Legacy Mode)</small>
        <hr class="mx-3">
    </div>
    <a href="dashboard.php" class="active"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
    <a href="data_barang.php"><i class="bi bi-box-seam me-2"></i> Data Barang</a>
    <a href="data_pengguna.php"><i class="bi bi-people me-2"></i> Data Pengguna</a>
    <a href="transaksi.php"><i class="bi bi-cart-check me-2"></i> Transaksi</a>
    <hr class="mx-3 text-white">
    <a href="logout.php" class="text-warning"><i class="bi bi-box-arrow-left me-2"></i> Logout</a>
</div>

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
                <div class="card card-custom p-3 bg-white">
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
                <div class="card card-custom p-3 bg-white" style="border-left-color: #1cc88a;">
                    <div class="row align-items-center">
                        <div class="col">
                            <p class="text-uppercase text-success fw-bold small mb-1">Total Produk</p>
                            <h4 class="fw-bold mb-0">124</h4>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-box text-secondary fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card card-custom p-3 bg-white" style="border-left-color: #f6c23e;">
                    <div class="row align-items-center">
                        <div class="col">
                            <p class="text-uppercase text-warning fw-bold small mb-1">Stok Hampir Habis</p>
                            <h4 class="fw-bold mb-0">5 Item</h4>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-exclamation-triangle text-secondary fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-5 shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-primary">Riwayat Transaksi Terbaru</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>ID Transaksi</th>
                                <th>Jam</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>TRX-2026-001</td>
                                <td><?php echo date('H:i'); ?></td>
                                <td>Rp 150.000</td>
                                <td><span class="badge bg-success">Selesai</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('show');
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
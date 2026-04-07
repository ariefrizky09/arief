<?php
session_start();

// Proteksi halaman: Cek apakah session username sudah ada
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Modern Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7f6;
        }
        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .sidebar {
            min-height: 100vh;
            background-color: #4e73df;
            color: white;
            padding-top: 20px;
        }
        .sidebar a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            transition: 0.3s;
        }
        .sidebar a:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        .sidebar a.active {
            background: rgba(255,255,255,0.2);
            color: white;
            font-weight: 600;
        }
        .card-stat {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }
        .card-stat:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
            <div class="position-sticky">
                <h4 class="text-center mb-4 fw-bold">My App</h4>
                <a href="#" class="active">🏠 Dashboard</a>
                <a href="#">📊 Statistik</a>
                <a href="#">👤 Profil</a>
                <a href="#">⚙️ Pengaturan</a>
                <hr>
                <a href="logout.php" class="text-warning">Logout</a>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <nav class="navbar navbar-expand-lg navbar-light mb-4 mt-2 rounded">
                <div class="container-fluid">
                    <span class="navbar-brand mb-0 h1">Dashboard Overview</span>
                    <div class="d-flex align-items-center">
                        <span class="me-3">Halo, <strong><?= htmlspecialchars($username); ?></strong></span>
                        <img src="https://ui-avatars.com/api/?name=<?= $username; ?>&background=4e73df&color=fff" alt="avatar" class="rounded-circle" width="35">
                    </div>
                </div>
            </nav>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card card-stat p-4 bg-white text-dark">
                        <h6 class="text-muted">Total Pengunjung</h6>
                        <h2 class="fw-bold">1,250</h2>
                        <span class="text-success small">▲ 12% Bulan ini</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-stat p-4 bg-white text-dark">
                        <h6 class="text-muted">Penjualan</h6>
                        <h2 class="fw-bold">Rp 4.5M</h2>
                        <span class="text-success small">▲ 5% dari target</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-stat p-4 bg-white text-dark">
                        <h6 class="text-muted">Pesan Masuk</h6>
                        <h2 class="fw-bold">18</h2>
                        <span class="text-danger small">▼ 2 belum dibaca</span>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <div class="card border-none shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-4">Aktivitas Terakhir</h5>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Aktivitas</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>10:00 AM</td>
                                    <td>User Login</td>
                                    <td><span class="badge bg-success">Berhasil</span></td>
                                </tr>
                                <tr>
                                    <td>09:45 AM</td>
                                    <td>Update Profil</td>
                                    <td><span class="badge bg-primary">Selesai</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
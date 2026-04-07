<?php
include 'config.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// PROSES TAMBAH BARANG
if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $jumlah = $_POST['jumlah_barang'];
    $harga = $_POST['harga_barang'];
    
    $query = mysqli_query($conn, "INSERT INTO barang (nama_barang, jumlah_barang, harga_barang) VALUES ('$nama', '$jumlah', '$harga')");
    header("Location: data_barang.php");
}

// PROSES EDIT BARANG
if (isset($_POST['update'])) {
    $id = $_POST['kode_barang'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $jumlah = $_POST['jumlah_barang'];
    $harga = $_POST['harga_barang'];
    
    $query = mysqli_query($conn, "UPDATE barang SET nama_barang='$nama', jumlah_barang='$jumlah', harga_barang='$harga' WHERE kode_barang='$id'");
    header("Location: data_barang.php");
}

// PROSES HAPUS BARANG
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM barang WHERE kode_barang='$id'");
    header("Location: data_barang.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Barang | POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --primary-color: #4e73df; --sidebar-width: 250px; }
        body { background-color: #f8f9fc; font-family: 'Segoe UI', sans-serif; }
        .sidebar { width: var(--sidebar-width); height: 100vh; position: fixed; background: var(--primary-color); z-index: 1000; }
        .sidebar a { color: rgba(255,255,255,0.8); padding: 15px 25px; display: block; text-decoration: none; }
        .sidebar a:hover { background: rgba(255,255,255,0.1); color: #fff; }
        .content { margin-left: var(--sidebar-width); padding: 20px; }
        @media (max-width: 768px) { .sidebar { display: none; } .content { margin-left: 0; } }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="text-white text-center py-4"><h4 class="fw-bold">POS SYSTEM</h4><hr></div>
    <a href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
    <a href="data_barang.php" class="bg-white text-primary rounded-start ms-2"><i class="bi bi-box-seam me-2"></i> Data Barang</a>
    <a href="#"><i class="bi bi-people me-2"></i> Data Pengguna</a>
    <a href="#"><i class="bi bi-cart-check me-2"></i> Transaksi</a>
    <a href="logout.php" class="text-warning"><i class="bi bi-box-arrow-left me-2"></i> Logout</a>
</div>

<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Manajemen Data Barang</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-circle me-2"></i>Tambah Barang
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Stok</th>
                            <th>Harga</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $res = mysqli_query($conn, "SELECT * FROM barang ORDER BY kode_barang DESC");
                        while($row = mysqli_fetch_assoc($res)) {
                        ?>
                        <tr>
                            <td>#<?php echo $row['kode_barang']; ?></td>
                            <td><?php echo $row['nama_barang']; ?></td>
                            <td><?php echo $row['jumlah_barang']; ?></td>
                            <td>Rp <?php echo number_format($row['harga_barang'], 0, ',', '.'); ?></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?php echo $row['kode_barang']; ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <a href="data_barang.php?hapus=<?php echo $row['kode_barang']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus barang ini?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalEdit<?php echo $row['kode_barang']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <form class="modal-content" method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Barang</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="kode_barang" value="<?php echo $row['kode_barang']; ?>">
                                        <div class="mb-3">
                                            <label>Nama Barang</label>
                                            <input type="text" name="nama_barang" class="form-control" value="<?php echo $row['nama_barang']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Jumlah</label>
                                            <input type="number" name="jumlah_barang" class="form-control" value="<?php echo $row['jumlah_barang']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Harga</label>
                                            <input type="number" name="harga_barang" class="form-control" value="<?php echo $row['harga_barang']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" method="POST">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Barang Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Nama Barang</label>
                    <input type="text" name="nama_barang" class="form-control" placeholder="Contoh: Sabun Mandi" required>
                </div>
                <div class="mb-3">
                    <label>Jumlah Stok</label>
                    <input type="number" name="jumlah_barang" class="form-control" placeholder="0" required>
                </div>
                <div class="mb-3">
                    <label>Harga Barang (Rp)</label>
                    <input type="number" name="harga_barang" class="form-control" placeholder="15000" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" name="tambah" class="btn btn-primary">Simpan Barang</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
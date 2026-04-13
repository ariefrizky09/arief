<?php
include 'config.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// 1. PROSES TAMBAH BARANG
if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $jumlah = $_POST['jumlah_barang'];
    $harga = $_POST['harga_barang'];
    
    $query = mysqli_query($conn, "INSERT INTO barang (nama_barang, jumlah_barang, harga_barang) VALUES ('$nama', '$jumlah', '$harga')");
    header("Location: data_barang.php");
    exit();
}

// 2. PROSES UPDATE BARANG
if (isset($_POST['update'])) {
    $id = $_POST['kode_barang'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $jumlah = $_POST['jumlah_barang'];
    $harga = $_POST['harga_barang'];
    
    $query = mysqli_query($conn, "UPDATE barang SET nama_barang='$nama', jumlah_barang='$jumlah', harga_barang='$harga' WHERE kode_barang='$id'");
    header("Location: data_barang.php");
    exit();
}

// 3. PROSES HAPUS BARANG
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM barang WHERE kode_barang='$id'");
    header("Location: data_barang.php");
    exit();
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
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="content">
    <nav class="navbar navbar-expand-lg navbar-light bg-white mb-4 shadow-sm rounded p-3">
        <div class="container-fluid">
            <button class="btn btn-primary d-md-none" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <div class="ms-auto">
                <span class="text-muted small">Halaman Manajemen Barang</span>
            </div>
        </div>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark">📦 Data Barang</h3>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg me-2"></i>Tambah Barang
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Kode</th>
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
                            $id_brg = $row['kode_barang'];
                        ?>
                        <tr>
                            <td class="ps-4 fw-bold text-primary">#<?php echo $id_brg; ?></td>
                            <td><?php echo $row['nama_barang']; ?></td>
                            <td><?php echo $row['jumlah_barang']; ?></td>
                            <td>Rp <?php echo number_format($row['harga_barang'], 0, ',', '.'); ?></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-warning me-1" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $id_brg; ?>">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <a href="data_barang.php?hapus=<?php echo $id_brg; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus barang ini?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>

                        <div class="modal fade" id="editModal<?php echo $id_brg; ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="data_barang.php">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Data Barang #<?php echo $id_brg; ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="kode_barang" value="<?php echo $id_brg; ?>">
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Nama Barang</label>
                                                <input type="text" name="nama_barang" class="form-control" value="<?php echo $row['nama_barang']; ?>" required>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label small fw-bold">Jumlah Stok</label>
                                                    <input type="number" name="jumlah_barang" class="form-control" value="<?php echo $row['jumlah_barang']; ?>" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label small fw-bold">Harga (Rp)</label>
                                                    <input type="number" name="harga_barang" class="form-control" value="<?php echo (int)$row['harga_barang']; ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
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
        <div class="modal-content">
            <form method="POST" action="data_barang.php">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Barang Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" placeholder="Masukan nama barang..." required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Stok</label>
                            <input type="number" name="jumlah_barang" class="form-control" placeholder="0" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Harga</label>
                            <input type="number" name="harga_barang" class="form-control" placeholder="10000" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah" class="btn btn-primary">Tambah Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
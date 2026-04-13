<?php
include 'config.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// PROSES SIMPAN TRANSAKSI
if (isset($_POST['simpan_transaksi'])) {
    $id_user = $_SESSION['username'];
    $kode_barang = $_POST['kode_barang'];
    $jumlah = $_POST['jumlah'];
    
    // Ambil harga barang dari database
    $query_harga = mysqli_query($conn, "SELECT harga_barang, jumlah_barang FROM barang WHERE kode_barang = '$kode_barang'");
    $data_barang = mysqli_fetch_assoc($query_harga);
    
    if ($data_barang && $data_barang['jumlah_barang'] >= $jumlah) {
        $total_harga = $data_barang['harga_barang'] * $jumlah;

        // 1. Masukkan ke tabel transaksi
        mysqli_query($conn, "INSERT INTO transaksi (total_harga, id_user) VALUES ('$total_harga', '$id_user')");
        $id_terakhir = mysqli_insert_id($conn);

        // 2. Masukkan ke detail transaksi
        mysqli_query($conn, "INSERT INTO detail_transaksi (id_transaksi, kode_barang, jumlah, subtotal) 
                            VALUES ('$id_terakhir', '$kode_barang', '$jumlah', '$total_harga')");

        // 3. Potong stok barang
        mysqli_query($conn, "UPDATE barang SET jumlah_barang = jumlah_barang - $jumlah WHERE kode_barang = '$kode_barang'");

        header("Location: transaksi.php?status=sukses");
    } else {
        header("Location: transaksi.php?status=gagal");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi | POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="content">
    <nav class="navbar navbar-expand-lg navbar-light bg-white mb-4 shadow-sm rounded p-3">
        <div class="container-fluid">
            <button class="btn btn-primary d-md-none" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>
            <div class="ms-auto"><span class="text-muted small">Input Transaksi Penjualan</span></div>
        </div>
    </nav>

    <div class="row">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary">Input Penjualan</h6>
                </div>
                <div class="card-body">
                    <?php if(isset($_GET['status']) && $_GET['status'] == 'sukses'): ?>
                        <div class="alert alert-success small">Transaksi Berhasil!</div>
                    <?php elseif(isset($_GET['status']) && $_GET['status'] == 'gagal'): ?>
                        <div class="alert alert-danger small">Stok tidak cukup!</div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Pilih Barang</label>
                            <select name="kode_barang" class="form-select" required>
                                <option value="">-- Pilih Barang --</option>
                                <?php
                                $barang = mysqli_query($conn, "SELECT * FROM barang WHERE jumlah_barang > 0");
                                while($b = mysqli_fetch_assoc($barang)) {
                                    echo "<option value='".$b['kode_barang']."'>".$b['nama_barang']." (Stok: ".$b['jumlah_barang'].")</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Jumlah Beli</label>
                            <input type="number" name="jumlah" class="form-control" min="1" required>
                        </div>
                        <button type="submit" name="simpan_transaksi" class="btn btn-primary w-100">
                            <i class="bi bi-cart-plus me-2"></i>Simpan Transaksi
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary">Transaksi Terakhir</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">ID</th>
                                    <th>Waktu</th>
                                    <th>Total Bayar</th>
                                    <th>Kasir</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM transaksi ORDER BY id_transaksi DESC LIMIT 10";
                                $res = mysqli_query($conn, $sql);
                                while($row = mysqli_fetch_assoc($res)) {
                                ?>
                                <tr>
                                    <td class="ps-4">#<?php echo $row['id_transaksi']; ?></td>
                                    <td><?php echo date('d/m H:i', strtotime($row['tanggal_transaksi'])); ?></td>
                                    <td class="fw-bold">Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                    <td><span class="badge bg-info text-dark"><?php echo $row['id_user']; ?></span></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
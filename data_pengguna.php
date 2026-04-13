<?php
include 'config.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// 1. PROSES TAMBAH PENGGUNA
if (isset($_POST['tambah'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = md5($_POST['password']); // Menggunakan MD5 sesuai standar PHP 5 lama
    $nama = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $role = $_POST['role'];
    
    $query = mysqli_query($conn, "INSERT INTO users (username, password, nama_lengkap, role) VALUES ('$user', '$pass', '$nama', '$role')");
    header("Location: data_pengguna.php");
    exit();
}

// 2. PROSES UPDATE PENGGUNA
if (isset($_POST['update'])) {
    $id = $_POST['id_user'];
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $role = $_POST['role'];

    // Jika password diisi, maka update password juga
    if (!empty($_POST['password'])) {
        $pass = md5($_POST['password']);
        mysqli_query($conn, "UPDATE users SET username='$user', password='$pass', nama_lengkap='$nama', role='$role' WHERE id_user='$id'");
    } else {
        mysqli_query($conn, "UPDATE users SET username='$user', nama_lengkap='$nama', role='$role' WHERE id_user='$id'");
    }
    header("Location: data_pengguna.php");
    exit();
}

// 3. PROSES HAPUS PENGGUNA
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM users WHERE id_user='$id'");
    header("Location: data_pengguna.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pengguna | POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="content">
    <nav class="navbar navbar-expand-lg navbar-light bg-white mb-4 shadow-sm rounded p-3">
        <div class="container-fluid">
            <button class="btn btn-primary d-md-none" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>
            <div class="ms-auto"><span class="text-muted small">Manajemen Akses Pengguna</span></div>
        </div>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark">👤 Data Pengguna</h3>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-person-plus me-2"></i>Tambah User
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Username</th>
                            <th>Nama Lengkap</th>
                            <th>Role</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $res = mysqli_query($conn, "SELECT * FROM users ORDER BY id_user DESC");
                        while($row = mysqli_fetch_assoc($res)) {
                            $id_u = $row['id_user'];
                        ?>
                        <tr>
                            <td class="ps-4"><?php echo $no++; ?></td>
                            <td class="fw-bold text-primary"><?php echo $row['username']; ?></td>
                            <td><?php echo $row['nama_lengkap']; ?></td>
                            <td><span class="badge bg-secondary"><?php echo $row['role']; ?></span></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-warning me-1" data-bs-toggle="modal" data-bs-target="#editUser<?php echo $id_u; ?>">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <a href="data_pengguna.php?hapus=<?php echo $id_u; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus user ini?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>

                        <div class="modal fade" id="editUser<?php echo $id_u; ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Pengguna</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id_user" value="<?php echo $id_u; ?>">
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Username</label>
                                                <input type="text" name="username" class="form-control" value="<?php echo $row['username']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Nama Lengkap</label>
                                                <input type="text" name="nama_lengkap" class="form-control" value="<?php echo $row['nama_lengkap']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Role</label>
                                                <select name="role" class="form-select">
                                                    <option value="Admin" <?php if($row['role']=='Admin') echo 'selected'; ?>>Admin</option>
                                                    <option value="Kasir" <?php if($row['role']=='Kasir') echo 'selected'; ?>>Kasir</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Password Baru (Kosongkan jika tidak ganti)</label>
                                                <input type="password" name="password" class="form-control">
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
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pengguna Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Role</label>
                        <select name="role" class="form-select">
                            <option value="Admin">Admin</option>
                            <option value="Kasir">Kasir</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah" class="btn btn-primary">Tambah User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
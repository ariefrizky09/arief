<?php
include 'config.php';
session_start();

$error = "";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']); // Menggunakan MD5 sesuai permintaan

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .login-container { margin-top: 100px; max-width: 400px; }
        .card { border: none; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .btn-primary { border-radius: 10px; padding: 10px; background: #4e73df; }
    </style>
</head>
<body>

<div class="container login-container">
    <div class="card p-4">
        <div class="card-body">
            <h3 class="text-center mb-4 fw-bold">Selamat Datang</h3>
            
            <?php if($error): ?>
                <div class="alert alert-danger text-center"><?= $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="********" required>
                </div>
                <div class="d-grid gap-2 mt-4">
                    <button type="submit" name="login" class="btn btn-primary">Login Sekarang</button>
                </div>
            </form>
        </div>
    </div>
    <p class="text-center mt-3 text-muted">&copy; 2026 Your Brand</p>
</div>

</body>
</html>
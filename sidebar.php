<style>
    :root { 
        --primary-color: #4e73df; 
        --sidebar-width: 250px; 
    }
    
    body { 
        background-color: #f8f9fc; 
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        margin: 0;
    }
    
    /* Sidebar Styling */
    .sidebar { 
        width: var(--sidebar-width); 
        height: 100vh; 
        position: fixed; 
        background: var(--primary-color); 
        z-index: 1000; 
        transition: all 0.3s;
        color: white;
    }
    
    .sidebar-header {
        padding: 20px;
        text-align: center;
    }

    .sidebar a { 
        color: rgba(255,255,255,0.8); 
        padding: 15px 25px; 
        display: block; 
        text-decoration: none; 
        transition: 0.2s;
        border-left: 4px solid transparent;
    }
    
    .sidebar a:hover { 
        background: rgba(255,255,255,0.1); 
        color: #fff; 
    }
    
    /* Class untuk menandai halaman yang sedang dibuka */
    .sidebar a.active { 
        background: rgba(255,255,255,0.2); 
        color: #fff; 
        border-left: 4px solid #fff;
        font-weight: bold;
    }
    
    /* Layout Content agar tidak tertutup sidebar */
    .content { 
        margin-left: var(--sidebar-width); 
        padding: 30px; 
        min-height: 100vh;
        transition: all 0.3s;
    }
    
    /* Responsif untuk layar HP */
    @media (max-width: 768px) { 
        .sidebar { margin-left: -250px; } 
        .sidebar.show { margin-left: 0; }
        .content { margin-left: 0; } 
    }

    /* Styling Card & Table agar selaras */
    .card { border: none; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    .btn-primary { background-color: var(--primary-color); border: none; }
</style>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h4 class="fw-bold mb-0">POS SYSTEM</h4>
        <small class="text-white-50">Management App</small>
        <hr class="mx-2 opacity-25">
    </div>
    
    <?php 
    // Mengambil nama file yang sedang aktif (misal: dashboard.php)
    $current_page = basename($_SERVER['PHP_SELF']); 
    ?>

    <a href="dashboard.php" class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
        <i class="bi bi-speedometer2 me-2"></i> Dashboard
    </a>
    
    <a href="data_barang.php" class="<?php echo ($current_page == 'data_barang.php') ? 'active' : ''; ?>">
        <i class="bi bi-box-seam me-2"></i> Data Barang
    </a>
    
    <a href="data_pengguna.php" class="<?php echo ($current_page == 'data_pengguna.php') ? 'active' : ''; ?>">
        <i class="bi bi-people me-2"></i> Data Pengguna
    </a>
    
    <a href="transaksi.php" class="<?php echo ($current_page == 'transaksi.php') ? 'active' : ''; ?>">
        <i class="bi bi-cart-check me-2"></i> Transaksi
    </a>
    
    <div style="position: absolute; bottom: 20px; width: 100%;">
        <hr class="mx-3 opacity-25 text-white">
        <a href="logout.php" class="text-warning">
            <i class="bi bi-box-arrow-left me-2"></i> Logout
        </a>
    </div>
</div>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('show');
    }
</script>
<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Admin Panel Berita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #00d4ff 100%);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #fff !important;
        }
        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            font-weight: 500;
            transition: color 0.3s ease;
            margin: 0 0.5rem;
        }
        .nav-link:hover {
            color: #fff !important;
            transform: translateY(-2px);
        }
        .nav-link.active {
            color: #fff !important;
            font-weight: 600;
            position: relative;
        }
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 2px;
            background: #fff;
            border-radius: 1px;
        }
        .container-main {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #00d4ff 100%);
            color: white;
            padding: 3rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
        }
        .page-header h1 {
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-size: 2.8rem;
        }
        .page-header .welcome-role {
            font-size: 1.3rem;
            opacity: 0.9;
            margin-bottom: 1rem;
        }
        .page-header p {
            opacity: 0.9;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }
        .dashboard-stats {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            flex: 1;
            min-width: 250px;
            text-align: center;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: none;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
        }
        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #00d4ff 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin: 0 auto 1rem;
        }
        .stat-card h3 {
            font-weight: 700;
            font-size: 2rem;
            color: #333;
            margin-bottom: 0.25rem;
        }
        .stat-card p {
            color: #666;
            font-size: 1rem;
            margin-bottom: 1rem;
        }
        .stat-card a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .stat-card a:hover {
            color: #00d4ff;
        }
        .quick-actions {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        }
        .quick-actions h4 {
            font-weight: 600;
            color: #333;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        .action-item {
            text-align: center;
            padding: 1.5rem;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 12px;
            transition: all 0.3s ease;
            text-decoration: none;
            color: #333;
        }
        .action-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            color: #667eea;
        }
        .action-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            display: block;
        }
        .action-title {
            font-weight: 500;
            font-size: 0.95rem;
        }
        @media (max-width: 768px) {
            .page-header {
                padding: 2rem 1.5rem;
                margin-bottom: 1rem;
            }
            .page-header h1 {
                font-size: 2.2rem;
            }
            .welcome-role {
                font-size: 1.1rem;
            }
            .dashboard-stats {
                flex-direction: column;
            }
            .action-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand active" href="index.php">
                <i class="fas fa-newspaper me-2"></i>Admin Panel Berita
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a href="berita.php" class="nav-link"><i class="fas fa-file-alt me-1"></i>Berita</a></li>
                    <li class="nav-item"><a href="kategori.php" class="nav-link"><i class="fas fa-tags me-1"></i>Kategori</a></li>
                    <li class="nav-item"><a href="users.php" class="nav-link"><i class="fas fa-users me-1"></i>User</a></li>
                    <li class="nav-item"><a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt me-1"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-main">
        <div class="page-header">
            <h1><i class="fas fa-tachometer-alt me-3"></i>Selamat Datang!</h1>
            <p class="welcome-role"><i class="fas fa-user-shield me-2"></i><?= ucfirst($_SESSION['role']) ?> Admin</p>
            <p>Kelola platform berita Anda dengan mudah. Gunakan menu navigasi di atas untuk mengakses berbagai fitur manajemen konten dan pengguna.</p>
        </div>

        <!-- Quick Stats Cards (Placeholder - Sesuaikan dengan data real jika ada) -->
        <div class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h3>0</h3>
                <p>Total Berita</p>
                <a href="berita.php"><i class="fas fa-arrow-right me-1"></i>Lihat Semua</a>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-tags"></i>
                </div>
                <h3>0</h3>
                <p>Total Kategori</p>
                <a href="kategori.php"><i class="fas fa-arrow-right me-1"></i>Lihat Semua</a>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>0</h3>
                <p>Total User</p>
                <a href="users.php"><i class="fas fa-arrow-right me-1"></i>Lihat Semua</a>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h4 class="mb-4"><i class="fas fa-rocket me-2"></i>Aksi Cepat</h4>
            <div class="action-grid">
                <a href="berita.php" class="action-item">
                    <i class="fas fa-plus-circle action-icon"></i>
                    <span class="action-title">Tambah Berita</span>
                </a>
                <a href="kategori.php" class="action-item">
                    <i class="fas fa-plus-circle action-icon"></i>
                    <span class="action-title">Tambah Kategori</span>
                </a>
                <a href="users.php" class="action-item">
                    <i class="fas fa-user-plus action-icon"></i>
                    <span class="action-title">Tambah User</span>
                </a>
                <a href="berita.php" class="action-item">
                    <i class="fas fa-eye action-icon"></i>
                    <span class="action-title">Lihat Berita</span>
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Tambahkan animasi fade-in untuk elemen dashboard
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.stat-card, .quick-actions');
            elements.forEach((el, index) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    el.style.transition = 'all 0.6s ease';
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, index * 200);
            });
        });
    </script>
</body>
</html>

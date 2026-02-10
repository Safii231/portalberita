<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../classes/berita.php';
$berita = new Berita();

if ($_SESSION['role'] == "admin") {
    $data = $berita->getAll();
} else {
    $data = $berita->getAllByUser ($_SESSION['id_user']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Berita - Admin Panel Berita</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
        }
        .page-header h1 {
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-size: 2.5rem;
        }
        .page-header p {
            opacity: 0.9;
            font-size: 1.1rem;
        }
        .btn-modern {
            border-radius: 50px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .btn-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            border: none;
        }
        .btn-success:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(17, 153, 142, 0.4);
        }
        .berita-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }
        .berita-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .berita-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
        }
        .berita-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            margin-right: 1rem;
            flex-shrink: 0;
        }
        .berita-content h5 {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
            line-height: 1.3;
        }
        .berita-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            color: #666;
        }
        .kategori-badge {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .penulis-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
        }
        .btn-action {
            border-radius: 25px;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            border: none;
        }
        .btn-warning {
            background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            color: #333;
        }
        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(246, 211, 101, 0.4);
        }
        .btn-danger {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
            color: #333;
        }
        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 154, 158, 0.4);
        }
        .no-actions {
            color: #999;
            font-style: italic;
        }
        .no-data {
            text-align: center;
            color: #666;
            padding: 3rem;
            font-size: 1.1rem;
        }
        @media (max-width: 768px) {
            .page-header {
                padding: 1.5rem;
                margin-bottom: 1rem;
            }
            .page-header h1 {
                font-size: 2rem;
            }
            .berita-card {
                flex-direction: column;
                text-align: center;
            }
            .berita-icon {
                margin: 0 auto 1rem;
            }
            .action-buttons {
                flex-direction: column;
                width: 100%;
            }
            .berita-meta {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-newspaper me-2"></i>Admin Panel Berita
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a href="berita.php" class="nav-link active"><i class="fas fa-file-alt me-1"></i>Berita</a></li>
                    <li class="nav-item"><a href="kategori.php" class="nav-link"><i class="fas fa-tags me-1"></i>Kategori</a></li>
                    <li class="nav-item"><a href="users.php" class="nav-link"><i class="fas fa-users me-1"></i>User</a></li>
                    <li class="nav-item"><a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt me-1"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-main">
        <div class="page-header">
            <h1><i class="fas fa-file-alt me-3"></i>Manajemen Berita</h1>
            <p>Kelola artikel berita dengan mudah. Admin dapat mengelola semua, sementara penulis hanya berita miliknya.</p>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0 fw-bold text-dark"><i class="fas fa-list me-2"></i>Data Berita</h3>
            <a href="tambah_berita.php" class="btn btn-success btn-modern">
                <i class="fas fa-plus me-2"></i>+ Tambah Berita
            </a>
        </div>

        <div class="row">
            <?php 
            $no = 1; 
            if ($data->num_rows > 0) {
                while ($row = $data->fetch_assoc()) {
                    // fallback jika key berbeda
                    $judul = $row['judul'] ?? $row['title'] ?? '';
                    $kategori = $row['kategori'] ?? $row['nama_kategori'] ?? '';
                    $penulis = $row['penulis'] ?? $row['username'] ?? '';
                    
                    // cek author (user id)
                    $authorId = isset($row['user_id']) ? (int)$row['user_id'] : (isset($row['id_user']) ? (int)$row['id_user'] : null);
                    $canEdit = ($_SESSION['role'] == "admin" || $authorId === (int)$_SESSION['id_user']);
            ?>
            <div class="col-lg-6 col-xl-4">
                <div class="card berita-card">
                    <div class="d-flex align-items-start">
                        <div class="berita-icon">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        <div class="berita-content flex-grow-1">
                            <h5><?= htmlspecialchars($judul) ?></h5>
                            <div class="berita-meta">
                                <span class="kategori-badge"><?= htmlspecialchars($kategori) ?></span>
                                <span class="penulis-info">
                                    <i class="fas fa-user me-1"></i><?= htmlspecialchars($penulis) ?>
                                </span>
                                <span><i class="fas fa-hashtag me-1"></i>#<?= $no ?></span>
                            </div>
                        </div>
                        <div class="action-buttons ms-3">
                            <?php if ($canEdit) { ?>
                                <a href="edit_berita.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-action">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                                <a href="hapus_berita.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus berita ini?')" class="btn btn-danger btn-action">
                                    <i class="fas fa-trash me-1"></i>Hapus
                                </a>
                            <?php } else { ?>
                                <span class="no-actions">-</span>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                $no++;
                } 
            } else { 
            ?>
            <div class="col-12">
                <div class="no-data">
                    <i class="fas fa-file-alt fa-3x mb-3 text-muted"></i>
                    <p>Tidak ada data berita saat ini. Tambahkan berita baru untuk memulai!</p>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Tambahkan animasi fade-in untuk cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.berita-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>

<?php
require_once "../classes/Berita.php";
require_once "../classes/Kategori.php";

$berita = new Berita();
$kategori = new Kategori();

$data_kategori = $kategori->getAll();

// Cek apakah ada parameter id
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);
$data_berita = $berita->getById($id);

if (!$data_berita) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($data_berita['judul']) ?> - Portal Berita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding-top: 80px;
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
        .navbar-brand i {
            margin-right: 0.5rem;
        }
        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            font-weight: 500;
            transition: all 0.3s ease;
            margin: 0 0.5rem;
            position: relative;
        }
        .nav-link:hover {
            color: #fff !important;
            transform: translateY(-2px);
        }
        .nav-link.active {
            color: #fff !important;
            font-weight: 600;
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
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .news-detail {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .news-header {
            position: relative;
        }
        .news-header img {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }
        .news-meta {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.8));
            color: white;
            padding: 2rem 2rem 1rem;
        }
        .news-meta .category {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 0.5rem;
        }
        .news-meta .date {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        .news-content {
            padding: 2rem;
        }
        .news-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 1rem;
            line-height: 1.2;
        }
        .news-author {
            color: #666;
            font-style: italic;
            margin-bottom: 2rem;
        }
        .news-text {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #444;
            margin-bottom: 2rem;
        }
        .btn-back {
            background: linear-gradient(135deg, #667eea 0%, #00d4ff 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            color: white;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }
        @media (max-width: 768px) {
            .news-title {
                font-size: 2rem;
            }
            .news-header img {
                height: 250px;
            }
            .news-meta {
                padding: 1.5rem 1.5rem 1rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-newspaper me-2"></i>Portal Berita
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <?php
                    $data_kategori->data_seek(0);
                    while($kat = $data_kategori->fetch_assoc()) {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?id=<?= $kat['id'] ?>">
                                <i class="fas fa-tag me-1"></i><?= htmlspecialchars($kat['nama_kategori']) ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
                <form class="d-flex me-3" role="search">
                    <input class="form-control me-2" type="search" placeholder="Cari berita..." aria-label="Search">
                    <button class="btn btn-outline-light" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">
                            <i class="fas fa-user-plus me-1"></i>Register
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-main">
        <div class="news-detail">
            <div class="news-header">
                <img src="../assets/upload/<?= htmlspecialchars($data_berita['foto']) ?>" alt="<?= htmlspecialchars($data_berita['judul']) ?>">
                <div class="news-meta">
                    <div class="category">
                        <i class="fas fa-tag me-1"></i><?= htmlspecialchars($data_berita['kategori']) ?>
                    </div>
                    <div class="date">
                        <i class="fas fa-calendar me-1"></i><?= date('d M Y', strtotime($data_berita['tanggal'])) ?>
                    </div>
                </div>
            </div>
            <div class="news-content">
                <h1 class="news-title"><?= htmlspecialchars($data_berita['judul']) ?></h1>
                <div class="news-author">
                    <i class="fas fa-user me-1"></i>Oleh: <?= htmlspecialchars($data_berita['penulis']) ?>
                </div>
                <div class="news-text">
                    <?= nl2br(htmlspecialchars($data_berita['konten'])) ?>
                </div>
                <a href="index.php" class="btn-back">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    <footer style="background: linear-gradient(135deg, #667eea 0%, #00d4ff 100%); color: white; padding: 2rem 0; margin-top: 4rem;">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-newspaper me-2"></i>Portal Berita</h5>
                    <p>Sumber informasi terpercaya untuk berita terkini dan inspiratif.</p>
                </div>
                <div class="col-md-4">
                    <h5>Link Cepat</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="text-white text-decoration-none">Beranda</a></li>
                        <li><a href="register.php" class="text-white text-decoration-none">Daftar</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Kontak</h5>
                    <p><i class="fas fa-envelope me-2"></i>info@portalberita.com</p>
                    <p><i class="fas fa-phone me-2"></i>+62 123 456 789</p>
                </div>
            </div>
            <hr class="my-3">
            <div class="text-center">
                <p>&copy; 2024 Portal Berita. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

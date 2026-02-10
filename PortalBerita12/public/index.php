<?php
require_once "../classes/Berita.php";
require_once "../classes/Kategori.php";

$berita = new Berita();
$kategori = new Kategori();

$data_kategori = $kategori->getAll();

// Cek apakah ada parameter kategori
if (isset($_GET['id'])) {
    $id_kategori = intval($_GET['id']);
    $data_berita = $berita->getByKategori($id_kategori);
} else {
    $data_berita = $berita->getAll();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Berita</title>
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
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #00d4ff 100%);
            color: white;
            padding: 3rem 2rem;
            border-radius: 20px;
            margin-bottom: 3rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
        }
        .page-header h1 {
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-size: 3rem;
        }
        .page-header p {
            opacity: 0.9;
            font-size: 1.2rem;
            max-width: 600px;
            margin: 0 auto;
        }
        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        .news-card-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }
        .news-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
        }
        .news-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        }
        .news-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .news-card:hover img {
            transform: scale(1.05);
        }
        .card-body {
            padding: 1.5rem;
        }
        .card-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 1rem;
            font-size: 1.25rem;
            line-height: 1.4;
        }
        .card-text {
            color: #666;
            margin-bottom: 1.5rem;
            line-height: 1.6;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .btn-baca {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        .btn-baca:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(17, 153, 142, 0.4);
            color: white;
            text-decoration: none;
        }
        .no-news {
            text-align: center;
            padding: 4rem 2rem;
            color: #666;
            font-size: 1.1rem;
        }
        .no-news i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 1rem;
        }
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2.5rem;
            }
            .page-header p {
                font-size: 1rem;
            }
            .news-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            .news-card img {
                height: 180px;
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
                    $data_kategori->data_seek(0); // Reset pointer jika diperlukan
                    while($kat = $data_kategori->fetch_assoc()) {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link <?= (isset($_GET['id']) && $_GET['id'] == $kat['id']) ? 'active' : '' ?>" href="index.php?id=<?= $kat['id'] ?>">
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
        <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px; margin-bottom: 2rem;">
                <i class="fas fa-check-circle me-2"></i>Registrasi berhasil! Anda sekarang dapat login sebagai penulis.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="page-header">
            <h1><i class="fas fa-home me-3"></i>Selamat Datang di Portal Berita</h1>
            <p>Temukan berita terkini dan inspiratif dari berbagai kategori. Kami menyajikan informasi terpercaya untuk Anda.</p>
        </div>

        <div class="news-grid">
            <?php
            $hasNews = false;
            while($row = $data_berita->fetch_assoc()) {
                $hasNews = true;
            ?>
                <a href="detail.php?id=<?= $row['id'] ?>" class="news-card-link">
                    <div class="news-card">
                        <img src="../assets/upload/<?= htmlspecialchars($row['foto']) ?>" alt="<?= htmlspecialchars($row['judul']) ?>" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($row['judul']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars(substr($row['konten'], 0, 150)) ?>...</p>
                            <span class="btn btn-baca">
                                <i class="fas fa-arrow-right me-2"></i>Baca Selengkapnya
                            </span>
                        </div>
                    </div>
                </a>
            <?php } ?>
            <?php if (!$hasNews) { ?>
                <div class="no-news col-12">
                    <i class="fas fa-newspaper"></i>
                    <p>Belum ada berita tersedia pada kategori ini. Silakan cek lagi nanti!</p>
                </div>
            <?php } ?>
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
    <script>
        // Animasi fade-in untuk news cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.news-card');
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

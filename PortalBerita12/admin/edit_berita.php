<?php
session_start();
if (!isset($_SESSION['id_user'])) header("Location: login.php");

require_once __DIR__ . '/../classes/berita.php';
require_once __DIR__ . '/../classes/kategori.php';
$berita = new Berita();
$kategori = new Kategori();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$data = $berita->getDetail($id);
if (!$data) { 
    header("Location: berita.php"); 
    exit; 
}

if (isset($_POST['update'])) {
    $judul = trim($_POST['judul']);
    $konten = trim($_POST['konten']);
    $kategori_id = (int)$_POST['kategori_id'];
    $foto = $data['foto'];

    if (!empty($_FILES['foto']['name'])) {
        $newName = time() . '_' . basename($_FILES['foto']['name']);
        move_uploaded_file($_FILES['foto']['tmp_name'], "../assets/upload/" . $newName);
        
        // hapus file lama jika ada
        if (!empty($foto) && file_exists(__DIR__ . '/../assets/upload/' . $foto)) {
            @unlink(__DIR__ . '/../assets/upload/' . $foto);
        }
        $foto = $newName;
    }

    if ($berita->update($id, $judul, $konten, $kategori_id, $foto)) {
        header("Location: berita.php");
        exit;
    } else {
        $error = "Gagal update berita.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Berita - Admin Panel Berita</title>
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
        .form-container {
            max-width: 700px;
            margin: 0 auto;
        }
        .form-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }
        .form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .form-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        }
        .form-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .form-header i {
            font-size: 3rem;
            background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }
        .form-header h2 {
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
            font-size: 1.8rem;
        }
        .form-floating {
            margin-bottom: 1.5rem;
            position: relative;
        }
        .form-floating input,
        .form-floating select {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 1rem 1rem 1rem 3rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
            height: calc(3.5rem + 2px);
        }
        .form-floating input:focus,
        .form-floating select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background: white;
        }
        .form-floating textarea {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 1rem 1rem 1rem 3rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
            min-height: 150px;
            resize: vertical;
        }
        .form-floating textarea:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background: white;
        }
        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            z-index: 10;
        }
        .file-section {
            margin-bottom: 1.5rem;
        }
        .current-image {
            max-width: 150px;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
            display: block;
        }
        .file-input-wrapper {
            position: relative;
        }
        .file-input-wrapper input[type="file"] {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
            width: 100%;
        }
        .file-input-wrapper input[type="file"]:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background: white;
        }
        .file-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            z-index: 10;
            pointer-events: none;
        }
        .alert-error {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
            border: none;
            border-radius: 12px;
            color: #333;
            font-weight: 500;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(255, 154, 158, 0.2);
        }
        .alert-error i {
            margin-right: 0.5rem;
        }
        .btn-update {
            background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            border: none;
            border-radius: 12px;
            padding: 1rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            color: #333;
            box-shadow: 0 4px 15px rgba(246, 211, 101, 0.3);
            width: 100%;
            margin-bottom: 1rem;
        }
        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(246, 211, 101, 0.4);
            color: #333;
        }
        .btn-kembali {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            border: none;
            border-radius: 12px;
            padding: 1rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            color: white;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
            width: 100%;
            text-decoration: none;
            display: block;
            text-align: center;
        }
        .btn-kembali:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4);
            color: white;
        }
        @media (max-width: 576px) {
            .form-card {
                padding: 2rem;
                margin: 1rem;
            }
            .form-header h2 {
                font-size: 1.5rem;
            }
            .form-header i {
                font-size: 2.5rem;
            }
            .form-floating textarea {
                min-height: 120px;
            }
            .current-image {
                max-width: 100px;
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
            <h1><i class="fas fa-edit me-3"></i>Edit Berita</h1>
            <p>Perbarui artikel berita untuk menjaga konten tetap segar dan relevan.</p>
        </div>

        <div class="form-container">
            <div class="card form-card">
                <div class="form-header">
                    <i class="fas fa-edit"></i>
                    <h2>Form Edit Berita</h2>
                </div>
                
                <?php if (isset($error)) { ?>
                    <div class="alert alert-error d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php } ?>
                
                <form method="post" enctype="multipart/form-data">
                    <div class="form-floating position-relative mb-3">
                        <i class="fas fa-heading input-icon"></i>
                        <input type="text" name="judul" class="form-control" id="judul" 
                               value="<?= htmlspecialchars($data['judul']) ?>" placeholder="Judul Berita" required>
                        <label for="judul">Judul Berita</label>
                    </div>
                    
                    <div class="form-floating position-relative mb-3">
                        <i class="fas fa-align-left input-icon"></i>
                        <textarea name="konten" class="form-control" id="konten" placeholder="Konten Berita" required><?= htmlspecialchars($data['konten']) ?></textarea>
                        <label for="konten">Konten Berita</label>
                    </div>
                    
                    <div class="form-floating position-relative mb-3">
                        <i class="fas fa-tags input-icon"></i>
                        <select name="kategori_id" class="form-control" id="kategori_id" required>
                            <option value="" disabled>Pilih Kategori</option>
                            <?php 
                            $kat = $kategori->getAll(); 
                            while($k = $kat->fetch_assoc()) { 
                            ?>
                                <option value="<?= $k['id'] ?>" <?= ($data['kategori_id'] == $k['id']) ? "selected" : "" ?>>
                                    <?= htmlspecialchars($k['nama_kategori']) ?>
                                </option>
                            <?php } ?>
                        </select>
                        <label for="kategori_id">Kategori</label>
                    </div>
                    
                    <div class="file-section">
                        <label class="form-label fw-bold">Foto Saat Ini</label>
                        <?php if (!empty($data['foto'])) { ?>
                            <img src="../assets/upload/<?= htmlspecialchars($data['foto']) ?>" alt="Foto Saat Ini" class="current-image" style="max-width: 150px;">
                            <small class="text-muted d-block mt-1">Ganti foto dengan memilih file baru di bawah.</small>
                        <?php } else { ?>
                            <p class="text-muted">Tidak ada foto saat ini.</p>
                        <?php } ?>
                        
                        <div class="file-input-wrapper position-relative mt-3">
                            <i class="fas fa-image file-icon"></i>
                            <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
                            <label for="foto" class="form-label">Foto Baru (Opsional)</label>
                        </div>
                    </div>
                    
                    <button type="submit" name="update" class="btn btn-update">
                        <i class="fas fa-save me-2"></i>Update Berita
                    </button>
                    
                    <a href="berita.php" class="btn-kembali">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Berita
                    </a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animasi fade-in untuk form card
        document.addEventListener('DOMContentLoaded', function() {
            const formCard = document.querySelector('.form-card');
            formCard.style.opacity = '0';
            formCard.style.transform = 'translateY(20px)';
            setTimeout(() => {
                formCard.style.transition = 'all 0.6s ease';
                formCard.style.opacity = '1';
                formCard.style.transform = 'translateY(0)';
            }, 200);
        });

        // Fokus efek pada input, select, dan textarea
        document.querySelectorAll('input, select, textarea').forEach(input => {
            input.addEventListener('focus', function() {
                const icon = this.parentElement.querySelector('.input-icon') || this.parentElement.querySelector('.file-icon');
                if (icon) icon.style.color = '#667eea';
            });
            input.addEventListener('blur', function() {
                const icon = this.parentElement.querySelector('.input-icon') || this.parentElement.querySelector('.file-icon');
                if (icon) icon.style.color = '#666';
            });
        });

        // Preview gambar saat upload (opsional enhancement)
        document.getElementById('foto').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const currentImg = document.querySelector('.current-image');
                    if (currentImg) {
                        currentImg.src = e.target.result;
                        currentImg.style.maxWidth = '150px';
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>

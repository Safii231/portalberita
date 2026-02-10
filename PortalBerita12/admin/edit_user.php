<?php
session_start();
if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../classes/user.php';
$user = new User();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$data = $user->getById($id);
if (!$data) {
    header("Location: users.php"); exit;
}

if (isset($_POST['update'])) {
    $username = $_POST['username'];
    $password = $_POST['password']; // boleh kosong (biar password lama tetap)
    $role = $_POST['role'];

    $user->update($id, $username, $password, $role);
    header("Location: users.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Admin Panel Berita</title>
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
            max-width: 500px;
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
        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            z-index: 10;
        }
        .password-note {
            font-size: 0.9rem;
            color: #666;
            font-style: italic;
            margin-top: -0.5rem;
            margin-bottom: 1rem;
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
                    <li class="nav-item"><a href="berita.php" class="nav-link"><i class="fas fa-file-alt me-1"></i>Berita</a></li>
                    <li class="nav-item"><a href="kategori.php" class="nav-link"><i class="fas fa-tags me-1"></i>Kategori</a></li>
                    <li class="nav-item"><a href="users.php" class="nav-link active"><i class="fas fa-users me-1"></i>User</a></li>
                    <li class="nav-item"><a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt me-1"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-main">
        <div class="page-header">
            <h1><i class="fas fa-user-edit me-3"></i>Edit User</h1>
            <p>Perbarui informasi pengguna untuk mengelola akses platform berita Anda.</p>
        </div>

        <div class="form-container">
            <div class="card form-card">
                <div class="form-header">
                    <i class="fas fa-edit"></i>
                    <h2>Form Edit User</h2>
                </div>
                
                <form method="post">
                    <div class="form-floating position-relative mb-3">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" name="username" class="form-control" id="username" 
                               value="<?= htmlspecialchars($data['username']) ?>" placeholder="Username" required>
                        <label for="username">Username</label>
                    </div>
                    
                    <div class="form-floating position-relative mb-3">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="password" class="form-control" id="password" 
                               placeholder="Password Baru (Opsional)">
                        <label for="password">Password</label>
                    </div>
                    <div class="password-note">
                        <i class="fas fa-info-circle me-1"></i>Kosongkan jika tidak ingin mengubah password.
                    </div>
                    
                    <div class="form-floating position-relative mb-4">
                        <i class="fas fa-user-tag input-icon"></i>
                        <select name="role" class="form-control" id="role" required>
                            <option value="admin" <?= $data['role'] == "admin" ? "selected" : "" ?>>Admin</option>
                            <option value="penulis" <?= $data['role'] == "penulis" ? "selected" : "" ?>>Penulis</option>
                        </select>
                        <label for="role">Role</label>
                    </div>
                    
                    <button type="submit" name="update" class="btn btn-update">
                        <i class="fas fa-save me-2"></i>Update User
                    </button>
                    
                    <a href="users.php" class="btn-kembali">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar User
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

        // Fokus efek pada input dan select
        document.querySelectorAll('input, select').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.querySelector('.input-icon').style.color = '#667eea';
            });
            input.addEventListener('blur', function() {
                this.parentElement.querySelector('.input-icon').style.color = '#666';
            });
        });
    </script>
</body>
</html>

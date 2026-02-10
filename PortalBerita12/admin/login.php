<?php
session_start();
require_once __DIR__ . '/../classes/user.php';
$user = new User();

if (isset($_POST['login'])) {
    $data = $user->login($_POST['username'], $_POST['password']);
    if ($data) {
        $_SESSION['id_user'] = $data['id'];
        $_SESSION['role'] = $data['role'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Admin Panel Berita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .login-container {
            max-width: 400px;
            width: 100%;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-header i {
            font-size: 3rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }
        .login-header h2 {
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
            font-size: 1.8rem;
        }
        .login-header p {
            color: #666;
            font-size: 1rem;
            opacity: 0.8;
        }
        .form-floating {
            margin-bottom: 1.5rem;
        }
        .form-floating input {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 1rem 1rem 1rem 3rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        .form-floating input:focus {
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
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 1rem;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            width: 100%;
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            color: white;
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
        @media (max-width: 576px) {
            .login-card {
                padding: 2rem;
                margin: 1rem;
            }
            .login-header h2 {
                font-size: 1.5rem;
            }
            .login-header i {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="card login-card">
            <div class="login-header">
                <i class="fas fa-newspaper"></i>
                <h2>Admin Panel Berita</h2>
                <p>Masuk untuk mengelola konten berita Anda</p>
            </div>
            
            <?php if (isset($error)) { ?>
                <div class="alert alert-error d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php } ?>
            
            <form method="POST">
                <div class="form-floating position-relative mb-3">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" name="username" class="form-control" id="username" placeholder="Username" required>
                    <label for="username">Username</label>
                </div>
                <div class="form-floating position-relative mb-4">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                    <label for="password">Password</label>
                </div>
                <button type="submit" name="login" class="btn btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animasi fade-in untuk login card
        document.addEventListener('DOMContentLoaded', function() {
            const loginCard = document.querySelector('.login-card');
            loginCard.style.opacity = '0';
            loginCard.style.transform = 'translateY(20px)';
            setTimeout(() => {
                loginCard.style.transition = 'all 0.6s ease';
                loginCard.style.opacity = '1';
                loginCard.style.transform = 'translateY(0)';
            }, 200);
        });

        // Fokus efek pada input
        document.querySelectorAll('input').forEach(input => {
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

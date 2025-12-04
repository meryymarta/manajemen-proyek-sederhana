<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Manajemen Proyek</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #EEF1F6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-card {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-card h2 { margin-bottom: 10px; color: #2d3436; }
        .login-card p { color: #636e72; margin-bottom: 30px; font-size: 14px; }
        
        .form-group { margin-bottom: 20px; text-align: left; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: #2d3436; }
        .form-control {
            width: 100%; padding: 12px; border: 1px solid #dfe6e9;
            border-radius: 8px; font-family: inherit; box-sizing: border-box;
        }
        .btn-login {
            width: 100%; padding: 12px; background: linear-gradient(135deg, #4A8BFF, #3A6FE0);
            color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;
            transition: 0.3s;
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(74, 139, 255, 0.3); }
        .error-msg { background: #ffe6e6; color: red; padding: 10px; border-radius: 6px; font-size: 13px; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="login-card">
    <h2>Welcome Back</h2>
    <p>Silakan login untuk mengelola proyek.</p>

    <?php if (isset($_GET['error'])): ?>
        <div class="error-msg"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <form action="index.php?page=auth_verify" method="POST">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required autofocus>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn-login">Masuk</button>
    </form>
    
    <!-- Link rahasia buat generate admin pertama kali (Hapus nanti kalau sudah live) -->
    <div style="margin-top: 20px; font-size: 12px;">
        <a href="index.php?page=seed_admin" style="color: #ccc; text-decoration: none;">Generate Admin</a>
    </div>
</div>

</body>
</html>
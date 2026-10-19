<?php
require 'config/koneksi.php';
$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password']) || $password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama']    = $user['nama'];
            $_SESSION['role']    = $user['role'];
            header("Location: dashboard.php");
            exit;
        } else $error = "Password salah!";
    } else $error = "Username tidak ditemukan!";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Portal Warga RT/RW & Karang Taruna</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            background: #f0f4f8;
        }

        /* ===== KIRI: BANNER ===== */
        .banner {
            flex: 1.2;
            background: linear-gradient(135deg, #0f766e 0%, #16a085 50%, #3498db 100%);
            color: white;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }
        .banner::before {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
            top: -200px; right: -200px;
        }
        .banner::after {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            background: rgba(255,255,255,0.06);
            border-radius: 50%;
            bottom: -100px; left: -100px;
        }
        .banner-logo {
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.5px;
            z-index: 2;
        }
        .banner-content {
            z-index: 2;
        }
        .banner-content h1 {
            font-size: 44px;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 20px;
            letter-spacing: -1px;
        }
        .banner-content p {
            font-size: 17px;
            line-height: 1.6;
            opacity: 0.9;
            max-width: 420px;
        }
        .banner-features {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-top: 40px;
            z-index: 2;
        }
        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 15px;
            font-weight: 500;
        }
        .feature-item .dot {
            width: 8px; height: 8px;
            background: #fff;
            border-radius: 50%;
            box-shadow: 0 0 0 4px rgba(255,255,255,0.2);
        }
        .banner-footer {
            font-size: 13px;
            opacity: 0.7;
            z-index: 2;
        }

        /* ===== KANAN: FORM ===== */
        .form-side {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: #f0f4f8;
        }
        .form-box {
            width: 100%;
            max-width: 400px;
        }
        .form-box .welcome {
            font-size: 14px;
            color: #16a085;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 10px;
        }
        .form-box h2 {
            font-size: 32px;
            color: #0f172a;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
        }
        .form-box .subtitle {
            color: #64748b;
            font-size: 15px;
            margin-bottom: 35px;
        }

        .input-group {
            margin-bottom: 20px;
        }
        .input-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
        }
        .input-group input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 15px;
            font-family: inherit;
            transition: all 0.2s;
            background: white;
        }
        .input-group input:focus {
            outline: none;
            border-color: #16a085;
            box-shadow: 0 0 0 4px rgba(22,160,133,0.12);
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #16a085, #0f766e);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 8px;
            box-shadow: 0 4px 14px rgba(22,160,133,0.3);
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(22,160,133,0.4);
        }
        .btn-login:active { transform: translateY(0); }

        .error-box {
            background: #fee2e2;
            color: #b91c1c;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 20px;
            border-left: 4px solid #dc2626;
        }

        .demo-box {
            margin-top: 30px;
            padding: 16px;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 10px;
        }
        .demo-box .title {
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        .demo-item {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            padding: 5px 0;
            color: #475569;
        }
        .demo-item span:last-child {
            font-family: 'Courier New', monospace;
            background: #e2e8f0;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
            color: #0f172a;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 900px) {
            .banner { display: none; }
            .form-side { flex: 1; }
        }
    </style>
</head>
<body>
    <!-- BANNER KIRI -->
    <div class="banner">
        <div class="banner-logo">🏘️ Portal Warga</div>

        <div class="banner-content">
            <h1>Satu Portal untuk<br>Warga yang Solid</h1>
            <p>Akses informasi RT/RW, kegiatan Karang Taruna, dan layanan warga dalam satu tempat.</p>

            <div class="banner-features">
                <div class="feature-item"><span class="dot"></span> Berita & Pengumuman RT</div>
                <div class="feature-item"><span class="dot"></span> Kegiatan Karang Taruna</div>
                <div class="feature-item"><span class="dot"></span> Lapor Masalah Lingkungan</div>
                <div class="feature-item"><span class="dot"></span> Transparansi Kas RT & KT</div>
            </div>
        </div>

        <div class="banner-footer">© <?= date('Y') ?> Portal Warga RT/RW & Karang Taruna</div>
    </div>

    <!-- FORM KANAN -->
    <div class="form-side">
        <div class="form-box">
            <div class="welcome">Selamat Datang</div>
            <h2>Masuk ke Akun</h2>
            <p class="subtitle">Silakan login untuk melanjutkan</p>

            <?php if ($error): ?>
                <div class="error-box">⚠️ <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="input-group">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="Masukkan username" required autofocus>
                </div>
                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Masukkan password" required>
                </div>
                <button type="submit" class="btn-login">Masuk →</button>
            </form>

            <div class="demo-box">
                <div class="title">🔑 Akun Demo</div>
                <div class="demo-item"><span>Admin RT</span><span>admin / admin123</span></div>
                <div class="demo-item"><span>Warga</span><span>warga / warga123</span></div>
                <div class="demo-item"><span>Karang Taruna</span><span>kt / kt123</span></div>
            </div>
        </div>
    </div>
</body>
</html>
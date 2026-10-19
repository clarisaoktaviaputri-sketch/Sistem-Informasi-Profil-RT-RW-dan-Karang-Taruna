<?php
require 'config/koneksi.php';
cek_login();
$inisial = strtoupper(substr($_SESSION['nama'], 0, 1));

$pesan = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = trim($_POST['judul']);
    $deskripsi = trim($_POST['deskripsi']);
    $lokasi = trim($_POST['lokasi']);
    $foto = null;

    if (!empty($_FILES['foto']['name'])) {
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png']) && $_FILES['foto']['size'] < 2000000) {
            $foto = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/" . $foto);
        }
    }

    $stmt = $conn->prepare("INSERT INTO laporan (user_id, judul, deskripsi, lokasi, foto) VALUES (?,?,?,?,?)");
    $stmt->bind_param("issss", $_SESSION['user_id'], $judul, $deskripsi, $lokasi, $foto);
    $stmt->execute();
    $pesan = "✅ Laporan berhasil dikirim!";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Laporan - Portal Warga</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="app">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="logo">🏘️</div>
            <div class="text"><h3>Portal Warga</h3><span>RT/RW & Karang Taruna</span></div>
        </div>
        <div class="sidebar-section">Menu Utama</div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php"><span class="icon">📊</span> Dashboard</a></li>
            <li><a href="profil.php"><span class="icon">🏛️</span> Profil RT/RW</a></li>
            <li><a href="berita.php"><span class="icon">📰</span> Berita RT</a></li>
            <li><a href="agenda.php"><span class="icon">📅</span> Agenda</a></li>
            <li><a href="laporan_list.php" class="active"><span class="icon">🚨</span> Laporan Warga</a></li>
        </ul>
        <div class="sidebar-section">Karang Taruna</div>
        <ul class="sidebar-menu">
            <li><a href="karangtaruna.php"><span class="icon">🎉</span> Halaman KT</a></li>
            <li><a href="kt_anggota.php"><span class="icon">👥</span> Anggota KT</a></li>
            <li><a href="kt_kas.php"><span class="icon">💰</span> Kas KT</a></li>
            <li><a href="daftar_kt.php"><span class="icon">📝</span> Daftar Baru</a></li>
        </ul>
    </aside>

    <div class="main">
        <div class="topbar">
            <div class="search-box"><input type="text" placeholder="Cari..."></div>
            <div class="topbar-right">
                <div class="user-chip"><div class="avatar"><?= $inisial ?></div><span class="name"><?= htmlspecialchars($_SESSION['nama']) ?></span></div>
                <a href="logout.php" class="icon-btn" style="text-decoration:none">🚪</a>
            </div>
        </div>

        <div class="content">
            <div class="page-header">
                <a href="laporan_list.php" class="back-link">← Kembali ke Daftar Laporan</a>
                <h1 style="margin-top:15px">🚨 Buat Laporan Baru</h1>
                <p>Laporkan masalah lingkungan di sekitar Anda.</p>
            </div>

            <?php if ($pesan): ?>
                <div class="alert-success"><?= $pesan ?></div>
            <?php endif; ?>

            <div class="panel" style="max-width:700px">
                <div class="panel-body" style="padding:25px">
                    <form method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Judul Laporan</label>
                            <input type="text" name="judul" placeholder="Contoh: Lampu jalan mati di Blok C" required>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi Kejadian</label>
                            <textarea name="deskripsi" rows="4" placeholder="Jelaskan detail masalahnya..." required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Lokasi</label>
                            <input type="text" name="lokasi" placeholder="Contoh: Depan Blok C No. 5" required>
                        </div>
                        <div class="form-group">
                            <label>Foto Bukti (opsional)</label>
                            <input type="file" name="foto" accept="image/*">
                            <small style="color:#94a3b8">Format: JPG/PNG, maks 2MB</small>
                        </div>
                        <button type="submit" class="btn-primary" style="width:100%">📤 Kirim Laporan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
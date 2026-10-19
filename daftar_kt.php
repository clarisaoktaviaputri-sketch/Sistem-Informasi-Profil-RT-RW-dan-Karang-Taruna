<?php
require 'config/koneksi.php';
$pesan = '';
$sukses = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama   = trim($_POST['nama']);
    $alamat = trim($_POST['alamat']);
    $hp     = trim($_POST['no_hp']);
    $divisi = $_POST['divisi'];

    $stmt = $conn->prepare("INSERT INTO anggota_kt (nama, alamat, no_hp, divisi, jabatan, status) VALUES (?,?,?,?,'Anggota Baru','aktif')");
    $stmt->bind_param("ssss", $nama, $alamat, $hp, $divisi);
    if ($stmt->execute()) {
        $pesan = "✅ Pendaftaran berhasil! Silakan hubungi pengurus Karang Taruna untuk konfirmasi.";
        $sukses = true;
    } else {
        $pesan = "❌ Gagal mendaftar. Coba lagi.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Anggota KT</title>
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
            <li><a href="laporan_list.php"><span class="icon">🚨</span> Laporan Warga</a></li>
        </ul>
        <div class="sidebar-section">Karang Taruna</div>
        <ul class="sidebar-menu">
            <li><a href="karangtaruna.php"><span class="icon">🎉</span> Halaman KT</a></li>
            <li><a href="kt_anggota.php"><span class="icon">👥</span> Anggota KT</a></li>
            <li><a href="kt_kas.php"><span class="icon">💰</span> Kas KT</a></li>
            <li><a href="daftar_kt.php" class="active"><span class="icon">📝</span> Daftar Baru</a></li>
        </ul>
    </aside>

    <div class="main">
        <div class="topbar">
            <div class="search-box"><input type="text" placeholder="Cari..."></div>
            <div class="topbar-right">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="user-chip"><div class="avatar"><?= strtoupper(substr($_SESSION['nama'],0,1)) ?></div><span class="name"><?= htmlspecialchars($_SESSION['nama']) ?></span></div>
                    <a href="logout.php" class="icon-btn" style="text-decoration:none">🚪</a>
                <?php else: ?>
                    <a href="index.php" class="btn-primary">Login</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="content">
            <div class="page-header">
                <h1>📝 Pendaftaran Anggota Baru</h1>
                <p>Isi formulir di bawah untuk bergabung dengan Karang Taruna.</p>
            </div>

            <?php if ($pesan): ?>
                <div class="<?= $sukses ? 'alert-success' : 'alert-success' ?>" style="<?= $sukses ? '' : 'background:#fee2e2;color:#b91c1c;border-left-color:#dc2626' ?>">
                    <?= $pesan ?>
                </div>
            <?php endif; ?>

            <div class="panel" style="max-width:700px">
                <div class="panel-body" style="padding:25px">
                    <form method="post">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama" placeholder="Nama lengkap Anda" required>
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea name="alamat" rows="2" placeholder="Alamat lengkap" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>No HP / WhatsApp</label>
                            <input type="text" name="no_hp" placeholder="08xxxxxxxxxx" required>
                        </div>
                        <div class="form-group">
                            <label>Pilih Divisi</label>
                            <select name="divisi" required>
                                <option value="">-- Pilih Divisi --</option>
                                <option value="Olahraga">Olahraga</option>
                                <option value="Seni">Seni</option>
                                <option value="Sosial">Sosial</option>
                                <option value="Keagamaan">Keagamaan</option>
                                <option value="Lingkungan">Lingkungan</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-primary" style="width:100%">🎉 Daftar Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php
require 'config/koneksi.php';
cek_login();
$inisial = strtoupper(substr($_SESSION['nama'], 0, 1));
$result = $conn->query("SELECT * FROM berita WHERE kategori='rt' ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita RT - Portal Warga</title>
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
            <li><a href="berita.php" class="active"><span class="icon">📰</span> Berita RT</a></li>
            <li><a href="agenda.php"><span class="icon">📅</span> Agenda</a></li>
            <li><a href="laporan_list.php"><span class="icon">🚨</span> Laporan Warga</a></li>
        </ul>
        <div class="sidebar-section">Karang Taruna</div>
        <ul class="sidebar-menu">
            <li><a href="karangtaruna.php"><span class="icon">🎉</span> Halaman KT</a></li>
            <li><a href="kt_anggota.php"><span class="icon">👥</span> Anggota KT</a></li>
            <li><a href="kt_kas.php"><span class="icon">💰</span> Kas KT</a></li>
            <li><a href="daftar_kt.php"><span class="icon">📝</span> Daftar Baru</a></li>
        </ul>
        <?php if ($_SESSION['role'] == 'admin'): ?>
        <div class="sidebar-section">Pengaturan</div>
        <ul class="sidebar-menu"><li><a href="admin.php"><span class="icon">⚙️</span> Panel Admin</a></li></ul>
        <?php endif; ?>
    </aside>

    <div class="main">
        <div class="topbar">
            <div class="search-box"><input type="text" placeholder="Cari berita..."></div>
            <div class="topbar-right">
                <button class="icon-btn">🔔<span class="badge-dot"></span></button>
                <div class="user-chip"><div class="avatar"><?= $inisial ?></div><span class="name"><?= htmlspecialchars($_SESSION['nama']) ?></span></div>
                <a href="logout.php" class="icon-btn" style="text-decoration:none">🚪</a>
            </div>
        </div>

        <div class="content">
            <div class="page-header">
                <h1>📰 Berita & Pengumuman RT</h1>
                <p>Informasi terbaru seputar kegiatan dan pengumuman lingkungan.</p>
            </div>

            <?php if ($result->num_rows > 0): ?>
                <div class="news-grid">
                <?php while($b = $result->fetch_assoc()): ?>
                    <div class="news-card">
                        <?php if($b['gambar']): ?>
                            <img src="uploads/berita/<?= $b['gambar'] ?>" alt="Gambar">
                        <?php else: ?>
                            <div class="news-placeholder">📰</div>
                        <?php endif; ?>
                        <div class="news-body">
                            <h3><?= htmlspecialchars($b['judul']) ?></h3>
                            <div class="news-meta">
                                ✍️ <?= htmlspecialchars($b['penulis']) ?> • <?= date('d M Y', strtotime($b['created_at'])) ?>
                            </div>
                            <p><?= htmlspecialchars(substr(strip_tags($b['isi']), 0, 120)) ?>...</p>
                            <a href="berita_detail.php?id=<?= $b['id'] ?>" class="read-more">Baca Selengkapnya →</a>
                        </div>
                    </div>
                <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="panel"><div class="empty-state"><span class="emoji">📭</span>Belum ada berita</div></div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
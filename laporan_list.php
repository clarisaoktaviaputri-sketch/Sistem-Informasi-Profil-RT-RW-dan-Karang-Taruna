<?php
require 'config/koneksi.php';
cek_login();
$inisial = strtoupper(substr($_SESSION['nama'], 0, 1));
$result = $conn->query("SELECT l.*, u.nama FROM laporan l LEFT JOIN users u ON l.user_id = u.id ORDER BY l.created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Warga - Portal Warga</title>
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
            <div class="search-box"><input type="text" placeholder="Cari laporan..."></div>
            <div class="topbar-right">
                <div class="user-chip"><div class="avatar"><?= $inisial ?></div><span class="name"><?= htmlspecialchars($_SESSION['nama']) ?></span></div>
                <a href="logout.php" class="icon-btn" style="text-decoration:none">🚪</a>
            </div>
        </div>

        <div class="content">
            <div class="page-header" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px">
                <div>
                    <h1>🚨 Laporan Warga</h1>
                    <p>Daftar laporan masalah lingkungan dari warga.</p>
                </div>
                <a href="laporan_warga.php" class="btn-primary">+ Buat Laporan</a>
            </div>

            <?php if ($result->num_rows > 0): ?>
                <div class="report-grid">
                <?php while($r = $result->fetch_assoc()): ?>
                    <div class="report-card">
                        <div class="report-header">
                            <span class="badge <?= $r['status'] ?>"><?= $r['status'] ?></span>
                            <span class="report-date"><?= date('d/m/y H:i', strtotime($r['created_at'])) ?></span>
                        </div>
                        <h3><?= htmlspecialchars($r['judul']) ?></h3>
                        <p><?= htmlspecialchars($r['deskripsi']) ?></p>
                        <div class="report-footer">
                            <span>📍 <?= htmlspecialchars($r['lokasi']) ?></span>
                            <span>👤 <?= htmlspecialchars($r['nama'] ?? 'Anonim') ?></span>
                        </div>
                        <?php if($r['foto']): ?>
                            <img src="uploads/<?= $r['foto'] ?>" class="report-img" alt="Foto">
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="panel"><div class="empty-state"><span class="emoji">📭</span>Belum ada laporan</div></div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
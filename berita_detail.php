<?php
require 'config/koneksi.php';
cek_login();
$inisial = strtoupper(substr($_SESSION['nama'], 0, 1));
$id = intval($_GET['id'] ?? 0);
$b = $conn->query("SELECT * FROM berita WHERE id=$id")->fetch_assoc();
if (!$b) die("Berita tidak ditemukan.");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($b['judul']) ?></title>
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
    </aside>

    <div class="main">
        <div class="topbar">
            <div class="search-box"><input type="text" placeholder="Cari berita..."></div>
            <div class="topbar-right">
                <div class="user-chip"><div class="avatar"><?= $inisial ?></div><span class="name"><?= htmlspecialchars($_SESSION['nama']) ?></span></div>
                <a href="logout.php" class="icon-btn" style="text-decoration:none">🚪</a>
            </div>
        </div>

        <div class="content">
            <div class="article">
                <a href="berita.php" class="back-link">← Kembali ke Berita</a>
                <span class="badge rt" style="margin-top:15px">BERITA RT</span>
                <h1><?= htmlspecialchars($b['judul']) ?></h1>
                <div class="article-meta">
                    ✍️ <?= htmlspecialchars($b['penulis']) ?> • 
                    📅 <?= date('d F Y, H:i', strtotime($b['created_at'])) ?>
                </div>
                <?php if($b['gambar']): ?>
                    <img src="uploads/berita/<?= $b['gambar'] ?>" class="article-img" alt="Gambar">
                <?php endif; ?>
                <div class="article-body">
                    <?= nl2br(htmlspecialchars($b['isi'])) ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php
require 'config/koneksi.php';
cek_login();
$inisial = strtoupper(substr($_SESSION['nama'], 0, 1));

$jml_anggota = $conn->query("SELECT COUNT(*) t FROM anggota_kt WHERE status='aktif'")->fetch_assoc()['t'];
$kas_masuk  = $conn->query("SELECT COALESCE(SUM(jumlah),0) t FROM kas WHERE jenis='masuk' AND kategori='kt'")->fetch_assoc()['t'];
$kas_keluar = $conn->query("SELECT COALESCE(SUM(jumlah),0) t FROM kas WHERE jenis='keluar' AND kategori='kt'")->fetch_assoc()['t'];
$saldo_kt = $kas_masuk - $kas_keluar;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Karang Taruna - Portal Warga</title>
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
            <li><a href="karangtaruna.php" class="active"><span class="icon">🎉</span> Halaman KT</a></li>
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
            <div class="hero-card" style="background: linear-gradient(135deg, #e67e22 0%, #f39c12 100%);">
                <h2>🎉 Karang Taruna</h2>
                <p>Organisasi kepemudaan yang aktif dalam kegiatan sosial, olahraga, dan seni di lingkungan kita.</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon orange">👥</div>
                    <div class="stat-label">Anggota Aktif</div>
                    <div class="stat-value"><?= $jml_anggota ?></div>
                    <div class="stat-trend">Karang Taruna</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon purple">💰</div>
                    <div class="stat-label">Saldo Kas KT</div>
                    <div class="stat-value" style="font-size:20px"><?= rupiah($saldo_kt) ?></div>
                    <div class="stat-trend">Keuangan organisasi</div>
                </div>
            </div>

            <div class="panels">
                <div class="panel">
                    <div class="panel-header"><h3>👥 Anggota Karang Taruna</h3><a href="kt_anggota.php">Lihat →</a></div>
                    <div class="panel-body" style="padding:20px 22px">
                        <p style="color:#64748b">Lihat daftar lengkap anggota Karang Taruna, lengkap dengan divisi dan jabatannya.</p>
                    </div>
                </div>
                <div class="panel">
                    <div class="panel-header"><h3>💰 Kas Karang Taruna</h3><a href="kt_kas.php">Lihat →</a></div>
                    <div class="panel-body" style="padding:20px 22px">
                        <p style="color:#64748b">Transparansi keuangan Karang Taruna — pemasukan & pengeluaran.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
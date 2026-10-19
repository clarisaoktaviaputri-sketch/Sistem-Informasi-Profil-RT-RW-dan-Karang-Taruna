<?php
require 'config/koneksi.php';
cek_login();

// Statistik
$jml_anggota = $conn->query("SELECT COUNT(*) t FROM anggota_kt WHERE status='aktif'")->fetch_assoc()['t'];
$jml_berita  = $conn->query("SELECT COUNT(*) t FROM berita")->fetch_assoc()['t'];
$jml_agenda  = $conn->query("SELECT COUNT(*) t FROM agenda WHERE status='akan datang'")->fetch_assoc()['t'];
$jml_laporan = $conn->query("SELECT COUNT(*) t FROM laporan WHERE status='baru'")->fetch_assoc()['t'];

$kas_rt_masuk  = $conn->query("SELECT COALESCE(SUM(jumlah),0) t FROM kas WHERE jenis='masuk' AND kategori='rt'")->fetch_assoc()['t'];
$kas_rt_keluar = $conn->query("SELECT COALESCE(SUM(jumlah),0) t FROM kas WHERE jenis='keluar' AND kategori='rt'")->fetch_assoc()['t'];
$saldo_rt = $kas_rt_masuk - $kas_rt_keluar;

// Data terbaru
$laporan_terbaru = $conn->query("SELECT l.*, u.nama FROM laporan l LEFT JOIN users u ON l.user_id = u.id ORDER BY l.created_at DESC LIMIT 4");
$agenda_terdekat = $conn->query("SELECT * FROM agenda WHERE status='akan datang' ORDER BY tanggal ASC LIMIT 4");

$inisial = strtoupper(substr($_SESSION['nama'], 0, 1));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Portal Warga</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="app">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="logo">🏘️</div>
            <div class="text">
                <h3>Portal Warga</h3>
                <span>RT/RW & Karang Taruna</span>
            </div>
        </div>

        <div class="sidebar-section">Menu Utama</div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php" class="active"><span class="icon">📊</span> Dashboard</a></li>
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
            <li><a href="daftar_kt.php"><span class="icon">📝</span> Daftar Baru</a></li>
        </ul>

        <?php if ($_SESSION['role'] == 'admin'): ?>
        <div class="sidebar-section">Pengaturan</div>
        <ul class="sidebar-menu">
            <li><a href="admin.php"><span class="icon">⚙️</span> Panel Admin</a></li>
        </ul>
        <?php endif; ?>
    </aside>

    <!-- MAIN -->
    <div class="main">

        <!-- TOPBAR -->
        <div class="topbar">
            <div class="search-box">
                <input type="text" placeholder="Cari berita, agenda, laporan...">
            </div>
            <div class="topbar-right">
                <button class="icon-btn">🔔<span class="badge-dot"></span></button>
                <div class="user-chip">
                    <div class="avatar"><?= $inisial ?></div>
                    <span class="name"><?= htmlspecialchars($_SESSION['nama']) ?></span>
                </div>
                <a href="logout.php" class="icon-btn" title="Logout" style="text-decoration:none">🚪</a>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="content">
            <div class="page-header">
                <h1>Selamat datang, <?= htmlspecialchars($_SESSION['nama']) ?>! 👋</h1>
                <p>Berikut ringkasan Portal Warga hari ini, <?= date('d F Y') ?>.</p>
            </div>

            <!-- STATS -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon green">👥</div>
                    <div class="stat-label">Anggota KT Aktif</div>
                    <div class="stat-value"><?= $jml_anggota ?></div>
                    <div class="stat-trend">Karang Taruna</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon blue">📰</div>
                    <div class="stat-label">Total Berita</div>
                    <div class="stat-value"><?= $jml_berita ?></div>
                    <div class="stat-trend">Dipublikasikan</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon orange">📅</div>
                    <div class="stat-label">Agenda Aktif</div>
                    <div class="stat-value"><?= $jml_agenda ?></div>
                    <div class="stat-trend">Akan datang</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon red">🚨</div>
                    <div class="stat-label">Laporan Baru</div>
                    <div class="stat-value"><?= $jml_laporan ?></div>
                    <div class="stat-trend">Perlu ditindak</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon purple">💰</div>
                    <div class="stat-label">Saldo Kas RT</div>
                    <div class="stat-value" style="font-size:20px"><?= rupiah($saldo_rt) ?></div>
                    <div class="stat-trend">Transparansi keuangan</div>
                </div>
            </div>

            <!-- PANELS -->
            <div class="panels">

                <!-- Laporan Terbaru -->
                <div class="panel">
                    <div class="panel-header">
                        <h3>🚨 Laporan Terbaru</h3>
                        <a href="laporan_list.php">Lihat Semua →</a>
                    </div>
                    <div class="panel-body">
                        <?php if ($laporan_terbaru->num_rows > 0): ?>
                            <?php while($l = $laporan_terbaru->fetch_assoc()): ?>
                            <div class="list-item">
                                <div class="item-icon">📌</div>
                                <div class="item-content">
                                    <div class="item-title"><?= htmlspecialchars($l['judul']) ?></div>
                                    <div class="item-meta">
                                        <?= htmlspecialchars($l['nama'] ?? 'Anonim') ?> • 
                                        <?= htmlspecialchars($l['lokasi']) ?> • 
                                        <?= date('d/m H:i', strtotime($l['created_at'])) ?>
                                    </div>
                                </div>
                                <span class="badge <?= $l['status'] ?>"><?= $l['status'] ?></span>
                            </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <span class="emoji">📭</span>
                                Belum ada laporan warga
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Agenda Terdekat -->
                <div class="panel">
                    <div class="panel-header">
                        <h3>📅 Agenda Terdekat</h3>
                        <a href="agenda.php">Lihat Semua →</a>
                    </div>
                    <div class="panel-body">
                        <?php if ($agenda_terdekat->num_rows > 0): ?>
                            <?php while($a = $agenda_terdekat->fetch_assoc()): ?>
                            <div class="list-item">
                                <div class="item-icon"><?= $a['kategori'] == 'rt' ? '🏛️' : '🎉' ?></div>
                                <div class="item-content">
                                    <div class="item-title"><?= htmlspecialchars($a['nama_kegiatan']) ?></div>
                                    <div class="item-meta">
                                        <?= date('d M Y', strtotime($a['tanggal'])) ?> • 
                                        <?= htmlspecialchars($a['tempat']) ?>
                                    </div>
                                </div>
                                <span class="badge <?= $a['kategori'] ?>"><?= strtoupper($a['kategori']) ?></span>
                            </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <span class="emoji">📅</span>
                                Belum ada agenda
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
</body>
</html>
<?php
require 'config/koneksi.php';
cek_login();
$inisial = strtoupper(substr($_SESSION['nama'], 0, 1));

$masuk  = $conn->query("SELECT COALESCE(SUM(jumlah),0) t FROM kas WHERE jenis='masuk' AND kategori='rt'")->fetch_assoc()['t'];
$keluar = $conn->query("SELECT COALESCE(SUM(jumlah),0) t FROM kas WHERE jenis='keluar' AND kategori='rt'")->fetch_assoc()['t'];
$saldo = $masuk - $keluar;

$result = $conn->query("SELECT * FROM kas WHERE kategori='rt' ORDER BY tanggal DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kas RT - Portal Warga</title>
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
            <li><a href="kas_rt.php" class="active"><span class="icon">💰</span> Kas RT</a></li>
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
            <div class="search-box"><input type="text" placeholder="Cari transaksi..."></div>
            <div class="topbar-right">
                <div class="user-chip"><div class="avatar"><?= $inisial ?></div><span class="name"><?= htmlspecialchars($_SESSION['nama']) ?></span></div>
                <a href="logout.php" class="icon-btn" style="text-decoration:none">🚪</a>
            </div>
        </div>

        <div class="content">
            <div class="page-header">
                <h1>💰 Kas RT</h1>
                <p>Transparansi keuangan RT — pemasukan & pengeluaran.</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon green">📥</div>
                    <div class="stat-label">Total Pemasukan</div>
                    <div class="stat-value" style="font-size:20px;color:#16a085"><?= rupiah($masuk) ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon red">📤</div>
                    <div class="stat-label">Total Pengeluaran</div>
                    <div class="stat-value" style="font-size:20px;color:#e74c3c"><?= rupiah($keluar) ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon purple">💎</div>
                    <div class="stat-label">Saldo Saat Ini</div>
                    <div class="stat-value" style="font-size:20px;color:#8e44ad"><?= rupiah($saldo) ?></div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header"><h3>📋 Riwayat Transaksi Kas RT</h3></div>
                <table class="data-table">
                    <thead>
                        <tr><th>Tanggal</th><th>Keterangan</th><th>Jenis</th><th style="text-align:right">Jumlah</th></tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($k = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= date('d M Y', strtotime($k['tanggal'])) ?></td>
                                <td><?= htmlspecialchars($k['keterangan']) ?></td>
                                <td><span class="badge <?= $k['jenis']=='masuk' ? 'selesai' : 'baru' ?>"><?= strtoupper($k['jenis']) ?></span></td>
                                <td style="text-align:right;font-weight:700;color:<?= $k['jenis']=='masuk' ? '#16a085' : '#e74c3c' ?>">
                                    <?= $k['jenis']=='masuk' ? '+' : '-' ?> <?= rupiah($k['jumlah']) ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="4" style="text-align:center;padding:40px;color:#94a3b8">Belum ada transaksi</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
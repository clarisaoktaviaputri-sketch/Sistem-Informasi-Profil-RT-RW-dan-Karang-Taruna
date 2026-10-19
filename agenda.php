<?php
require 'config/koneksi.php';
cek_login();
$inisial = strtoupper(substr($_SESSION['nama'], 0, 1));
$result = $conn->query("SELECT * FROM agenda ORDER BY tanggal ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Agenda - Portal Warga</title>
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
            <li><a href="agenda.php" class="active"><span class="icon">📅</span> Agenda</a></li>
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
            <div class="search-box"><input type="text" placeholder="Cari agenda..."></div>
            <div class="topbar-right">
                <div class="user-chip"><div class="avatar"><?= $inisial ?></div><span class="name"><?= htmlspecialchars($_SESSION['nama']) ?></span></div>
                <a href="logout.php" class="icon-btn" style="text-decoration:none">🚪</a>
            </div>
        </div>

        <div class="content">
            <div class="page-header">
                <h1>📅 Agenda Kegiatan</h1>
                <p>Jadwal kegiatan RT/RW dan Karang Taruna.</p>
            </div>

            <div class="panel">
                <table class="data-table">
                    <thead>
                        <tr><th>Tanggal</th><th>Waktu</th><th>Kegiatan</th><th>Tempat</th><th>Kategori</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($a = $result->fetch_assoc()): ?>
                            <tr>
                                <td><b><?= date('d M Y', strtotime($a['tanggal'])) ?></b></td>
                                <td><?= $a['waktu'] ? substr($a['waktu'],0,5) : '-' ?></td>
                                <td>
                                    <b><?= htmlspecialchars($a['nama_kegiatan']) ?></b>
                                    <?php if($a['deskripsi']): ?>
                                        <br><small style="color:#94a3b8"><?= htmlspecialchars($a['deskripsi']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($a['tempat']) ?></td>
                                <td><span class="badge <?= $a['kategori'] ?>"><?= strtoupper($a['kategori']) ?></span></td>
                                <td><span class="badge <?= $a['status']=='akan datang' ? 'diproses' : 'selesai' ?>"><?= $a['status'] ?></span></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" style="text-align:center;padding:40px;color:#94a3b8">Belum ada agenda</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
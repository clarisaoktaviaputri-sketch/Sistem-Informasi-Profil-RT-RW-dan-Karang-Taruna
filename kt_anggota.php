<?php
require 'config/koneksi.php';
cek_login();
$inisial = strtoupper(substr($_SESSION['nama'], 0, 1));

$divisi_filter = $_GET['divisi'] ?? '';
if ($divisi_filter) {
    $stmt = $conn->prepare("SELECT * FROM anggota_kt WHERE divisi = ? ORDER BY jabatan, nama");
    $stmt->bind_param("s", $divisi_filter);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM anggota_kt ORDER BY divisi, jabatan, nama");
}
$divisi_list = $conn->query("SELECT DISTINCT divisi FROM anggota_kt ORDER BY divisi");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Anggota KT - Portal Warga</title>
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
            <li><a href="kt_anggota.php" class="active"><span class="icon">👥</span> Anggota KT</a></li>
            <li><a href="kt_kas.php"><span class="icon">💰</span> Kas KT</a></li>
            <li><a href="daftar_kt.php"><span class="icon">📝</span> Daftar Baru</a></li>
        </ul>
    </aside>

    <div class="main">
        <div class="topbar">
            <div class="search-box"><input type="text" placeholder="Cari anggota..."></div>
            <div class="topbar-right">
                <div class="user-chip"><div class="avatar"><?= $inisial ?></div><span class="name"><?= htmlspecialchars($_SESSION['nama']) ?></span></div>
                <a href="logout.php" class="icon-btn" style="text-decoration:none">🚪</a>
            </div>
        </div>

        <div class="content">
            <div class="page-header">
                <h1>👥 Anggota Karang Taruna</h1>
                <p>Daftar anggota aktif Karang Taruna beserta divisi dan jabatannya.</p>
            </div>

            <div style="margin-bottom:20px;display:flex;gap:8px;flex-wrap:wrap">
                <a href="kt_anggota.php" class="btn-primary" style="<?= $divisi_filter ? 'background:#64748b;box-shadow:none' : '' ?>">Semua</a>
                <?php while($d = $divisi_list->fetch_assoc()): ?>
                    <a href="?divisi=<?= urlencode($d['divisi']) ?>" class="btn-primary" style="<?= $divisi_filter == $d['divisi'] ? '' : 'background:#64748b;box-shadow:none' ?>"><?= htmlspecialchars($d['divisi']) ?></a>
                <?php endwhile; ?>
            </div>

            <div class="panel">
                <table class="data-table">
                    <thead>
                        <tr><th>Nama</th><th>Divisi</th><th>Jabatan</th><th>No HP</th><th>Alamat</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($a = $result->fetch_assoc()): ?>
                            <tr>
                                <td><b><?= htmlspecialchars($a['nama']) ?></b></td>
                                <td><?= htmlspecialchars($a['divisi']) ?></td>
                                <td><?= htmlspecialchars($a['jabatan']) ?></td>
                                <td><?= htmlspecialchars($a['no_hp']) ?></td>
                                <td><?= htmlspecialchars($a['alamat']) ?></td>
                                <td><span class="badge <?= $a['status']=='aktif' ? 'selesai' : 'baru' ?>"><?= $a['status'] ?></span></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" style="text-align:center;padding:40px;color:#94a3b8">Belum ada anggota</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
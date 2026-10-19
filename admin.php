<?php
require 'config/koneksi.php';
cek_admin();
$inisial = strtoupper(substr($_SESSION['nama'], 0, 1));
$pesan = '';

// ==== TAMBAH BERITA ====
if (isset($_POST['tambah_berita'])) {
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $kategori = $_POST['kategori'];
    $gambar = null;

    if (!empty($_FILES['gambar']['name'])) {
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png'])) {
            $gambar = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['gambar']['tmp_name'], "uploads/berita/" . $gambar);
        }
    }

    $stmt = $conn->prepare("INSERT INTO berita (judul, isi, gambar, kategori, penulis) VALUES (?,?,?,?,?)");
    $stmt->bind_param("sssss", $judul, $isi, $gambar, $kategori, $_SESSION['nama']);
    $stmt->execute();
    $pesan = "✅ Berita berhasil ditambahkan!";
}

// ==== HAPUS BERITA ====
if (isset($_GET['hapus_berita'])) {
    $id = intval($_GET['hapus_berita']);
    $g = $conn->query("SELECT gambar FROM berita WHERE id=$id")->fetch_assoc();
    if ($g && $g['gambar'] && file_exists("uploads/berita/" . $g['gambar'])) {
        unlink("uploads/berita/" . $g['gambar']);
    }
    $conn->query("DELETE FROM berita WHERE id=$id");
    header("Location: admin.php?tab=berita");
    exit;
}

// ==== TAMBAH AGENDA ====
if (isset($_POST['tambah_agenda'])) {
    $stmt = $conn->prepare("INSERT INTO agenda (nama_kegiatan, tanggal, waktu, tempat, deskripsi, kategori) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("ssssss", $_POST['nama_kegiatan'], $_POST['tanggal'], $_POST['waktu'], $_POST['tempat'], $_POST['deskripsi'], $_POST['kategori']);
    $stmt->execute();
    $pesan = "✅ Agenda berhasil ditambahkan!";
}

// ==== HAPUS AGENDA ====
if (isset($_GET['hapus_agenda'])) {
    $conn->query("DELETE FROM agenda WHERE id=" . intval($_GET['hapus_agenda']));
    header("Location: admin.php?tab=agenda");
    exit;
}

// ==== UPDATE STATUS LAPORAN ====
if (isset($_POST['update_laporan'])) {
    $id = intval($_POST['laporan_id']);
    $st = $_POST['status'];
    $conn->query("UPDATE laporan SET status='$st' WHERE id=$id");
    $pesan = "✅ Status laporan diperbarui!";
}

// ==== TAMBAH KAS ====
if (isset($_POST['tambah_kas'])) {
    $stmt = $conn->prepare("INSERT INTO kas (tanggal, keterangan, jenis, jumlah, kategori) VALUES (?,?,?,?,?)");
    $stmt->bind_param("sssds", $_POST['tanggal'], $_POST['keterangan'], $_POST['jenis'], $_POST['jumlah'], $_POST['kategori']);
    $stmt->execute();
    $pesan = "✅ Transaksi kas berhasil ditambahkan!";
}

$tab = $_GET['tab'] ?? 'berita';
$berita_list  = $conn->query("SELECT * FROM berita ORDER BY created_at DESC");
$agenda_list  = $conn->query("SELECT * FROM agenda ORDER BY tanggal DESC");
$laporan_list = $conn->query("SELECT l.*, u.nama FROM laporan l LEFT JOIN users u ON l.user_id = u.id ORDER BY l.created_at DESC");
$kas_list     = $conn->query("SELECT * FROM kas ORDER BY tanggal DESC LIMIT 20");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Panel Admin - Portal Warga</title>
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
            <li><a href="daftar_kt.php"><span class="icon">📝</span> Daftar Baru</a></li>
        </ul>
        <div class="sidebar-section">Pengaturan</div>
        <ul class="sidebar-menu">
            <li><a href="admin.php" class="active"><span class="icon">⚙️</span> Panel Admin</a></li>
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
                <h1>⚙️ Panel Admin RT</h1>
                <p>Kelola semua konten Portal Warga dari sini.</p>
            </div>

            <?php if ($pesan): ?>
                <div class="alert-success"><?= $pesan ?></div>
            <?php endif; ?>

            <!-- TAB MENU -->
            <div class="admin-tabs">
                <a href="?tab=berita" class="admin-tab <?= $tab=='berita'?'active':'' ?>">📰 Berita</a>
                <a href="?tab=agenda" class="admin-tab <?= $tab=='agenda'?'active':'' ?>">📅 Agenda</a>
                <a href="?tab=laporan" class="admin-tab <?= $tab=='laporan'?'active':'' ?>">🚨 Laporan</a>
                <a href="?tab=kas" class="admin-tab <?= $tab=='kas'?'active':'' ?>">💰 Kas</a>
            </div>

            <!-- TAB BERITA -->
            <?php if ($tab == 'berita'): ?>
            <div class="panels">
                <div class="panel">
                    <div class="panel-header"><h3>➕ Tambah Berita</h3></div>
                    <div class="panel-body" style="padding:22px">
                        <form method="post" enctype="multipart/form-data">
                            <div class="form-group"><label>Judul</label><input type="text" name="judul" required></div>
                            <div class="form-group"><label>Kategori</label>
                                <select name="kategori" required>
                                    <option value="rt">RT</option>
                                    <option value="kt">Karang Taruna</option>
                                </select>
                            </div>
                            <div class="form-group"><label>Isi</label><textarea name="isi" rows="5" required></textarea></div>
                            <div class="form-group"><label>Gambar (opsional)</label><input type="file" name="gambar" accept="image/*"></div>
                            <button type="submit" name="tambah_berita" class="btn-primary" style="width:100%">Simpan Berita</button>
                        </form>
                    </div>
                </div>
                <div class="panel">
                    <div class="panel-header"><h3>📋 Daftar Berita</h3></div>
                    <div class="panel-body" style="max-height:500px;overflow-y:auto">
                        <?php while($b = $berita_list->fetch_assoc()): ?>
                        <div class="list-item">
                            <div class="item-icon"><?= $b['kategori']=='rt'?'🏛️':'🎉' ?></div>
                            <div class="item-content">
                                <div class="item-title"><?= htmlspecialchars($b['judul']) ?></div>
                                <div class="item-meta"><?= date('d M Y', strtotime($b['created_at'])) ?> • <?= strtoupper($b['kategori']) ?></div>
                            </div>
                            <a href="?hapus_berita=<?= $b['id'] ?>" onclick="return confirm('Hapus berita ini?')" style="color:#e74c3c;text-decoration:none;font-size:18px">🗑️</a>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- TAB AGENDA -->
            <?php if ($tab == 'agenda'): ?>
            <div class="panels">
                <div class="panel">
                    <div class="panel-header"><h3>➕ Tambah Agenda</h3></div>
                    <div class="panel-body" style="padding:22px">
                        <form method="post">
                            <div class="form-group"><label>Nama Kegiatan</label><input type="text" name="nama_kegiatan" required></div>
                            <div class="form-group"><label>Tanggal</label><input type="date" name="tanggal" required></div>
                            <div class="form-group"><label>Waktu</label><input type="time" name="waktu"></div>
                            <div class="form-group"><label>Tempat</label><input type="text" name="tempat" required></div>
                            <div class="form-group"><label>Kategori</label>
                                <select name="kategori" required>
                                    <option value="rt">RT</option>
                                    <option value="kt">Karang Taruna</option>
                                </select>
                            </div>
                            <div class="form-group"><label>Deskripsi</label><textarea name="deskripsi" rows="3"></textarea></div>
                            <button type="submit" name="tambah_agenda" class="btn-primary" style="width:100%">Simpan Agenda</button>
                        </form>
                    </div>
                </div>
                <div class="panel">
                    <div class="panel-header"><h3>📋 Daftar Agenda</h3></div>
                    <div class="panel-body" style="max-height:500px;overflow-y:auto">
                        <?php while($a = $agenda_list->fetch_assoc()): ?>
                        <div class="list-item">
                            <div class="item-icon"><?= $a['kategori']=='rt'?'🏛️':'🎉' ?></div>
                            <div class="item-content">
                                <div class="item-title"><?= htmlspecialchars($a['nama_kegiatan']) ?></div>
                                <div class="item-meta"><?= date('d M Y', strtotime($a['tanggal'])) ?> • <?= htmlspecialchars($a['tempat']) ?></div>
                            </div>
                            <a href="?hapus_agenda=<?= $a['id'] ?>" onclick="return confirm('Hapus agenda ini?')" style="color:#e74c3c;text-decoration:none;font-size:18px">🗑️</a>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- TAB LAPORAN -->
            <?php if ($tab == 'laporan'): ?>
            <div class="panel">
                <div class="panel-header"><h3>🚨 Kelola Laporan Warga</h3></div>
                <table class="data-table">
                    <thead>
                        <tr><th>Pelapor</th><th>Judul</th><th>Lokasi</th><th>Waktu</th><th>Status</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php if ($laporan_list->num_rows > 0): ?>
                            <?php while($l = $laporan_list->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($l['nama'] ?? 'Anonim') ?></td>
                                <td><b><?= htmlspecialchars($l['judul']) ?></b></td>
                                <td><?= htmlspecialchars($l['lokasi']) ?></td>
                                <td><?= date('d/m/y H:i', strtotime($l['created_at'])) ?></td>
                                <td><span class="badge <?= $l['status'] ?>"><?= $l['status'] ?></span></td>
                                <td>
                                    <form method="post" style="display:flex;gap:5px">
                                        <input type="hidden" name="laporan_id" value="<?= $l['id'] ?>">
                                        <select name="status" style="padding:6px 10px;border-radius:6px;border:1px solid #cbd5e1;font-family:inherit">
                                            <option value="baru" <?= $l['status']=='baru'?'selected':'' ?>>Baru</option>
                                            <option value="diproses" <?= $l['status']=='diproses'?'selected':'' ?>>Diproses</option>
                                            <option value="selesai" <?= $l['status']=='selesai'?'selected':'' ?>>Selesai</option>
                                        </select>
                                        <button type="submit" name="update_laporan" class="btn-primary" style="padding:6px 14px;font-size:12px">Simpan</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" style="text-align:center;padding:40px;color:#94a3b8">Belum ada laporan</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>

            <!-- TAB KAS -->
            <?php if ($tab == 'kas'): ?>
            <div class="panels">
                <div class="panel">
                    <div class="panel-header"><h3>➕ Tambah Transaksi</h3></div>
                    <div class="panel-body" style="padding:22px">
                        <form method="post">
                            <div class="form-group"><label>Tanggal</label><input type="date" name="tanggal" required value="<?= date('Y-m-d') ?>"></div>
                            <div class="form-group"><label>Keterangan</label><input type="text" name="keterangan" required></div>
                            <div class="form-group"><label>Jenis</label>
                                <select name="jenis" required>
                                    <option value="masuk">Pemasukan</option>
                                    <option value="keluar">Pengeluaran</option>
                                </select>
                            </div>
                            <div class="form-group"><label>Jumlah (Rp)</label><input type="number" name="jumlah" required min="0"></div>
                            <div class="form-group"><label>Kategori</label>
                                <select name="kategori" required>
                                    <option value="rt">Kas RT</option>
                                    <option value="kt">Kas Karang Taruna</option>
                                </select>
                            </div>
                            <button type="submit" name="tambah_kas" class="btn-primary" style="width:100%">Simpan Transaksi</button>
                        </form>
                    </div>
                </div>
                <div class="panel">
                    <div class="panel-header"><h3>📋 Transaksi Terakhir</h3></div>
                    <div class="panel-body" style="max-height:500px;overflow-y:auto">
                        <?php while($k = $kas_list->fetch_assoc()): ?>
                        <div class="list-item">
                            <div class="item-icon" style="background:<?= $k['jenis']=='masuk'?'#d1fae5':'#fee2e2' ?>"><?= $k['jenis']=='masuk'?'📥':'📤' ?></div>
                            <div class="item-content">
                                <div class="item-title"><?= htmlspecialchars($k['keterangan']) ?></div>
                                <div class="item-meta"><?= date('d M Y', strtotime($k['tanggal'])) ?> • <?= strtoupper($k['kategori']) ?></div>
                            </div>
                            <b style="color:<?= $k['jenis']=='masuk'?'#16a085':'#e74c3c' ?>;font-size:13px">
                                <?= $k['jenis']=='masuk'?'+':'-' ?> <?= rupiah($k['jumlah']) ?>
                            </b>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>
</body>
</html>
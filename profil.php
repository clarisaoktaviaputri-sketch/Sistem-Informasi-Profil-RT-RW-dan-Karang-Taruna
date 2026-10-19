<?php
require 'config/koneksi.php';
cek_login();
$inisial = strtoupper(substr($_SESSION['nama'], 0, 1));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil RT/RW - Portal Warga</title>
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
            <li><a href="profil.php" class="active"><span class="icon">🏛️</span> Profil RT/RW</a></li>
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
                <h1>🏛️ Profil RT/RW</h1>
                <p>Informasi umum tentang lingkungan dan kepengurusan.</p>
            </div>

            <div class="hero-card">
                <h2>Selamat Datang di RT 03 / RW 02</h2>
                <p>Lingkungan yang aman, nyaman, dan harmonis untuk seluruh warga.</p>
            </div>

            <div class="panels">
                <div class="panel">
                    <div class="panel-header"><h3>📖 Sejarah</h3></div>
                    <div class="panel-body" style="padding:20px 22px">
                        <p style="line-height:1.8;color:#475569">
                            RT 03/RW 02 berdiri sejak tahun 1995 dan menjadi wadah bagi warga untuk saling bergotong royong, 
                            menjaga keamanan lingkungan, serta meningkatkan kesejahteraan bersama melalui berbagai kegiatan sosial.
                        </p>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-header"><h3>🎯 Visi & Misi</h3></div>
                    <div class="panel-body" style="padding:20px 22px">
                        <p style="font-weight:700;color:#16a085;margin-bottom:10px">Visi:</p>
                        <p style="color:#475569;margin-bottom:15px">Terwujudnya lingkungan yang aman, nyaman, bersih, dan harmonis.</p>
                        <p style="font-weight:700;color:#16a085;margin-bottom:10px">Misi:</p>
                        <ul style="color:#475569;padding-left:20px;line-height:1.8">
                            <li>Meningkatkan keamanan lingkungan melalui siskamling</li>
                            <li>Mengembangkan kegiatan sosial & keagamaan</li>
                            <li>Mendorong partisipasi pemuda lewat Karang Taruna</li>
                            <li>Menjaga kebersihan dan keindahan lingkungan</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="panel" style="margin-top:20px">
                <div class="panel-header"><h3>👔 Struktur Pengurus RT/RW</h3></div>
                <div class="panel-body" style="padding:20px 22px">
                    <div class="pengurus-grid">
                        <div class="pengurus-card"><div class="pa">👨</div><b>Pak RT</b><span>Ketua RT</span></div>
                        <div class="pengurus-card"><div class="pa">👨</div><b>Pak Andi</b><span>Wakil RT</span></div>
                        <div class="pengurus-card"><div class="pa">👩</div><b>Bu Sari</b><span>Sekretaris</span></div>
                        <div class="pengurus-card"><div class="pa">👩</div><b>Bu Ibu</b><span>Bendahara</span></div>
                        <div class="pengurus-card"><div class="pa">👮</div><b>Pak Hansip</b><span>Keamanan</span></div>
                    </div>
                </div>
            </div>

            <div class="panel" style="margin-top:20px">
                <div class="panel-header"><h3>🎉 Struktur Karang Taruna</h3></div>
                <div class="panel-body" style="padding:20px 22px">
                    <div class="pengurus-grid">
                        <div class="pengurus-card"><div class="pa">👨</div><b>Budi Santoso</b><span>Ketua</span></div>
                        <div class="pengurus-card"><div class="pa">👩</div><b>Citra Dewi</b><span>Sekretaris</span></div>
                        <div class="pengurus-card"><div class="pa">👨</div><b>Andi</b><span>Bendahara</span></div>
                        <div class="pengurus-card"><div class="pa">⚽</div><b>Rudi & Doni</b><span>Divisi Olahraga</span></div>
                        <div class="pengurus-card"><div class="pa">🎨</div><b>Sinta & Maya</b><span>Divisi Seni</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
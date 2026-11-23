<?php
$current = basename($_SERVER['PHP_SELF'], '.php');
$base = '/sicerdas/';
?>
<aside class="sidebar">
  <div class="sidebar-brand"><div class="brand-mark small"><i class="fa-solid fa-leaf"></i></div><div><strong>SICERDAS</strong><small>Rehabilitasi DAS</small></div></div>
  <nav class="sidebar-nav">
    <a href="<?= $base ?>index.php" class="<?= $current === 'index' ? 'active' : '' ?>"><i class="fa-solid fa-house"></i><span>Beranda</span></a>
    <a href="<?= $base ?>modules/permohonan/"><i class="fa-solid fa-file-circle-plus"></i><span>Rekap Permohonan</span></a>
    <a href="<?= $base ?>modules/telaah/"><i class="fa-solid fa-list-check"></i><span>Telaah Permohonan</span></a>
    <div class="nav-label">Data Rehabilitasi</div>
    <a href="<?= $base ?>modules/rehabilitasi/"><i class="fa-solid fa-water"></i><span>Rehabilitasi DAS</span></a>
    <a href="<?= $base ?>modules/kompensasi/"><i class="fa-solid fa-map-location-dot"></i><span>Lahan Kompensasi</span></a>
    <a href="<?= $base ?>modules/reklamasi/"><i class="fa-solid fa-tree"></i><span>Reklamasi Hutan</span></a>
    <a href="<?= $base ?>modules/tarlam/"><i class="fa-solid fa-seedling"></i><span>Tarlam</span></a>
    <a href="<?= $base ?>modules/perusahaan/"><i class="fa-solid fa-building"></i><span>Perusahaan</span></a>
    <div class="nav-label">Dokumen & Laporan</div>
    <a href="<?= $base ?>modules/sk_penetapan/"><i class="fa-solid fa-stamp"></i><span>Daftar SK Penetapan</span></a>
    <a href="<?= $base ?>modules/sk_ppkh/"><i class="fa-solid fa-scroll"></i><span>Daftar SK PPKH</span></a>
    <a href="<?= $base ?>modules/laporan/"><i class="fa-solid fa-chart-column"></i><span>Pelaporan</span></a>
    <a href="<?= $base ?>modules/penilaian/"><i class="fa-solid fa-star"></i><span>Penilaian</span></a>
    <a href="<?= $base ?>modules/tabel_gabungan/"><i class="fa-solid fa-table-list"></i><span>Tabel Gabungan</span></a>
    <div class="nav-label">Sistem</div>
    <a href="<?= $base ?>modules/ebook/"><i class="fa-solid fa-book-open"></i><span>Ebook</span></a>
    <?php if (($_SESSION['role'] ?? '') === 'admin'): ?><a href="<?= $base ?>modules/pengguna/"><i class="fa-solid fa-users"></i><span>Kelola Pengguna</span></a><?php endif; ?>
    <a href="<?= $base ?>modules/bantuan/"><i class="fa-solid fa-circle-question"></i><span>Bantuan</span></a>
  </nav>
  <a class="logout-link" href="<?= $base ?>auth/logout.php"><i class="fa-solid fa-right-from-bracket"></i><span>Keluar</span></a>
</aside>

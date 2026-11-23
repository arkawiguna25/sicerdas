<?php
$current = basename($_SERVER['PHP_SELF'], '.php');
$base = '/sicerdas/';
?>
<header class="topbar">
  <button class="sidebar-toggle" type="button"><i class="fa-solid fa-bars"></i></button>
  <div class="topbar-title">SICERDAS <span>Sistem Informasi Rehabilitasi DAS</span></div>
  <div class="topbar-actions">
    <button class="topbar-icon" title="Bahasa"><i class="fa-solid fa-globe"></i></button>
    <button class="topbar-icon" title="Notifikasi"><i class="fa-regular fa-bell"></i><b>2</b></button>
    <div class="user-menu"><span class="avatar"><?= htmlspecialchars(substr($_SESSION['nama'] ?? 'U',0,1)) ?></span><div><strong><?= htmlspecialchars($_SESSION['nama'] ?? 'Pengguna') ?></strong><small><?= htmlspecialchars(ucfirst($_SESSION['role'] ?? '')) ?></small></div></div>
  </div>
</header>

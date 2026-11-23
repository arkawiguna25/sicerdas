<?php $base='/sicerdas/'; ?>
<header class="topbar">
  <button class="sidebar-toggle" type="button" aria-label="Buka navigasi" aria-expanded="false"><i class="fa-solid fa-bars"></i></button>
  <div class="topbar-title">Ruang kerja <span>›</span> SICERDAS</div>
  <div class="topbar-actions">
    <a class="topbar-icon" href="<?=$base?>modules/bantuan/" title="Panduan dan bantuan" aria-label="Panduan"><i class="fa-regular fa-circle-question"></i></a>
    <div class="user-menu"><span class="avatar"><?=htmlspecialchars(mb_strtoupper(mb_substr($_SESSION['nama']??'U',0,1)))?></span><div><strong><?=htmlspecialchars($_SESSION['nama']??'Pengguna')?></strong><small><?=htmlspecialchars($_SESSION['role']??'')?></small></div></div>
  </div>
</header>

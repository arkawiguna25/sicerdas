<?php
$base='/sicerdas/';
$path=parse_url($_SERVER['REQUEST_URI']??'',PHP_URL_PATH) ?: '';
$items=[
  ['title'=>'Menu utama','links'=>[
    ['index','Beranda','fa-house',$base.'index.php'],
    ['permohonan','Permohonan','fa-file-circle-plus',$base.'modules/permohonan/'],
    ['telaah','Telaah','fa-list-check',$base.'modules/telaah/'],
  ]],
  ['title'=>'Kegiatan','links'=>[
    ['rehabilitasi','Rehabilitasi DAS','fa-water',$base.'modules/rehabilitasi/'],
    ['kompensasi','Lahan Kompensasi','fa-map-location-dot',$base.'modules/kompensasi/'],
    ['reklamasi','Reklamasi Hutan','fa-tree',$base.'modules/reklamasi/'],
    ['tarlam','Tarlam','fa-seedling',$base.'modules/tarlam/'],
    ['perusahaan','Perusahaan','fa-building',$base.'modules/perusahaan/'],
    ['ippkh','IPPKH','fa-map',$base.'modules/ippkh/'],
  ]],
  ['title'=>'Dokumen & evaluasi','links'=>[
    ['sk_penetapan','SK Penetapan','fa-stamp',$base.'modules/sk_penetapan/'],
    ['sk_ppkh','SK PPKH','fa-scroll',$base.'modules/sk_ppkh/'],
    ['laporan','Laporan','fa-chart-column',$base.'modules/laporan/'],
    ['penilaian','Penilaian','fa-star',$base.'modules/penilaian/'],
    ['tabel_gabungan','Rekap data','fa-table-list',$base.'modules/tabel_gabungan/'],
  ]],
  ['title'=>'Lainnya','links'=>[
    ['ebook','Panduan','fa-book-open',$base.'modules/ebook/'],
    ['bantuan','Bantuan','fa-circle-question',$base.'modules/bantuan/'],
  ]],
];
$current=basename($path);
?>
<div class="sidebar-overlay" aria-hidden="true"></div>
<aside class="sidebar">
  <a class="sidebar-brand text-decoration-none text-white" href="<?=$base?>index.php"><div class="brand-mark small"><i class="fa-solid fa-leaf"></i></div><div><strong>SICERDAS</strong><small>Rehabilitasi DAS</small></div></a>
  <nav class="sidebar-nav" aria-label="Navigasi utama">
  <?php foreach($items as $group): ?><div class="nav-label"><?=htmlspecialchars($group['title'])?></div>
    <?php foreach($group['links'] as [$key,$label,$icon,$url]): $active=($key==='index'&&$current==='sicerdas')||str_contains($path,'/modules/'.$key.'/')||($key==='index'&&$path===$base.'index.php'); ?>
      <a href="<?=htmlspecialchars($url)?>" class="<?=$active?'active':''?>"><i class="fa-solid <?=$icon?>"></i><span><?=htmlspecialchars($label)?></span></a>
    <?php endforeach; ?>
  <?php endforeach; ?>
  <?php if(($_SESSION['role']??'')==='admin'): ?><div class="nav-label">Administrator</div><a href="<?=$base?>modules/pengguna/" class="<?=str_contains($path,'/modules/pengguna/')?'active':''?>"><i class="fa-solid fa-users"></i><span>Kelola pengguna</span></a><?php endif; ?>
  </nav>
  <a class="logout-link" href="<?=$base?>auth/logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i><span>Keluar</span></a>
</aside>

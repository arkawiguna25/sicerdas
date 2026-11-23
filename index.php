<?php
require_once __DIR__ . '/auth/auth_check.php';
require_once __DIR__ . '/config/database.php';

$stat = [];
$stat['permohonan'] = $pdo->query('SELECT COUNT(*) FROM permohonan')->fetchColumn();
$stat['sk'] = $pdo->query('SELECT COUNT(*) FROM sk_penetapan')->fetchColumn();
$stat['kegiatan'] = $pdo->query('SELECT COUNT(*) FROM rehabilitasi_das')->fetchColumn();
$stat['luas'] = (float)$pdo->query('SELECT COALESCE(SUM(luas_lahan),0) FROM rehabilitasi_das')->fetchColumn();
$stat['perusahaan'] = $pdo->query('SELECT COUNT(*) FROM perusahaan')->fetchColumn();
$stat['proses'] = $pdo->query("SELECT COUNT(*) FROM permohonan WHERE status='Dalam Pemeriksaan'")->fetchColumn();
$stat['selesai'] = $pdo->query("SELECT COUNT(*) FROM permohonan WHERE status='Selesai'")->fetchColumn();
$stat['ditolak'] = $pdo->query("SELECT COUNT(*) FROM permohonan WHERE status='Ditolak'")->fetchColumn();
$activity = $pdo->query('SELECT * FROM permohonan ORDER BY created_at DESC LIMIT 6')->fetchAll();
?>
<!doctype html>
<html lang="id"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard | SICERDAS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet">
</head><body>
<?php include __DIR__.'/layouts/sidebar.php'; ?>
<div class="main-wrapper">
<?php include __DIR__.'/layouts/navbar.php'; ?>
<main class="content">
  <div class="page-heading"><div><p>Selamat datang kembali</p><h1>Dashboard</h1></div><a class="btn btn-orange" href="modules/permohonan/create.php"><i class="fa-solid fa-plus"></i> Permohonan Baru</a></div>
  <div class="stat-grid">
    <?php
    $cards=[['Permohonan',$stat['permohonan'],'fa-file-lines','teal'],['SK Penetapan',$stat['sk'],'fa-stamp','blue'],['Kegiatan DAS',$stat['kegiatan'],'fa-water','orange'],['Luas Rehabilitasi',number_format($stat['luas'],1,'.','.').' Ha','fa-ruler-combined','green'],['Perusahaan',$stat['perusahaan'],'fa-building','purple'],['Dalam Proses',$stat['proses'],'fa-spinner','yellow'],['Selesai',$stat['selesai'],'fa-circle-check','green'],['Ditolak',$stat['ditolak'],'fa-circle-xmark','red']];
    foreach($cards as $c): ?>
    <div class="stat-card"><div class="stat-icon <?= $c[3] ?>"><i class="fa-solid <?= $c[2] ?>"></i></div><div><span><?= $c[0] ?></span><strong><?= $c[1] ?></strong></div></div>
    <?php endforeach; ?>
  </div>
  <div class="chart-grid">
    <div class="panel"><div class="panel-header"><h2>Status Permohonan</h2><span class="badge bg-light text-dark">Sistem</span></div><div class="chart-wrap"><canvas id="statusChart"></canvas></div></div>
    <div class="panel"><div class="panel-header"><h2>Permohonan Tahunan</h2><span class="badge bg-light text-dark"><?= date('Y') ?></span></div><div class="chart-wrap"><canvas id="yearChart"></canvas></div></div>
  </div>
  <div class="panel table-panel"><div class="panel-header"><h2>Aktivitas Permohonan Terbaru</h2><a href="modules/permohonan/">Lihat Semua</a></div>
    <div class="table-responsive"><table class="table sicerdas-table"><thead><tr><th>Nomor</th><th>Perusahaan</th><th>Jenis Kegiatan</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
    <?php if (!$activity): ?><tr><td colspan="6" class="text-center py-4">Belum ada permohonan.</td></tr><?php endif;
    foreach($activity as $row): ?><tr><td><?= htmlspecialchars($row['nomor_permohonan']) ?></td><td><?= htmlspecialchars($row['nama_pemohon']) ?></td><td><?= htmlspecialchars($row['jenis_permohonan']) ?></td><td><?= date('d/m/Y',strtotime($row['tanggal_permohonan'])) ?></td><td><span class="status status-<?= strtolower(str_replace(' ','-',htmlspecialchars($row['status']))) ?>"><?= htmlspecialchars($row['status']) ?></span></td><td><a class="btn btn-sm btn-detail" href="modules/permohonan/detail.php?id=<?= (int)$row['id'] ?>">Detail</a></td></tr><?php endforeach; ?>
    </tbody></table></div>
  </div>
</main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script src="assets/js/script.js"></script>
<script>
const statusCounts={<?= json_encode($stat['proses']) ?>,<?= json_encode($stat['selesai']) ?>,<?= json_encode($stat['ditolak']) ?>};
</script>
</body></html>

<?php
require_once __DIR__.'/../../auth/auth_check.php';
require_once __DIR__.'/../../config/database.php';

$search=trim($_GET['q'] ?? '');
$status=trim($_GET['status'] ?? '');
$params=[];$sql='SELECT * FROM permohonan WHERE 1=1';
if($search!==''){$sql.=' AND (nomor_permohonan LIKE ? OR nama_pemohon LIKE ? OR nomor_ippkh LIKE ?)';$term='%'.$search.'%';$params=array_fill(0,3,$term);}
if($status!==''){$sql.=' AND status=?';$params[]=$status;}
$sql.=' ORDER BY created_at DESC';
$stmt=$pdo->prepare($sql);$stmt->execute($params);$rows=$stmt->fetchAll();
include __DIR__.'/../../layouts/header.php';
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Rekap Permohonan | SICERDAS</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet"><link href="../../assets/css/style.css" rel="stylesheet"></head><body>
<?php include __DIR__.'/../../layouts/sidebar.php'; ?><div class="main-wrapper"><?php include __DIR__.'/../../layouts/navbar.php'; ?><main class="content">
<div class="page-heading"><div><p>Data Administrasi</p><h1>Rekap Permohonan</h1></div><a class="btn btn-orange" href="create.php"><i class="fa-solid fa-plus"></i> Permohonan Baru</a></div>
<div class="panel table-panel"><div class="panel-header"><h2>Daftar Permohonan</h2></div><div class="p-3"><form class="row g-2 mb-3"><div class="col-md-5"><input class="form-control" name="q" placeholder="Cari nomor, pemohon, atau IPPKH" value="<?=htmlspecialchars($search)"></div><div class="col-md-3"><select class="form-select" name="status"><option value="">Semua status</option><?php foreach(['Draft','Diajukan','Dalam Pemeriksaan','Perlu Perbaikan','Disetujui','Ditolak','Selesai'] as $s):?><option <?=$status==$s?'selected':''?>><?=htmlspecialchars($s)?></option><?php endforeach;?></select></div><div class="col-md-2"><button class="btn btn-detail w-100"><i class="fa-solid fa-magnifying-glass"></i> Cari</button></div></form><div class="table-responsive"><table class="table sicerdas-table"><thead><tr><th>Nomor Permohonan</th><th>Tanggal</th><th>Pemohon</th><th>Jenis</th><th>IPPKH</th><th>Status</th><th>Aksi</th></tr></thead><tbody><?php if(!$rows):?><tr><td colspan="7" class="text-center py-4">Belum ada data permohonan.</td></tr><?php endif;foreach($rows as $r):?><tr><td><?=htmlspecialchars($r['nomor_permohonan'])?></td><td><?=date('d/m/Y',strtotime($r['tanggal_permohonan']))?></td><td><?=htmlspecialchars($r['nama_pemohon'])?></td><td><?=htmlspecialchars($r['jenis_permohonan'])?></td><td><?=htmlspecialchars($r['nomor_ippkh'])?></td><td><span class="status status-<?=strtolower(str_replace(' ','-',$r['status']))?>"><?=htmlspecialchars($r['status'])?></span></td><td><a class="btn btn-sm btn-detail" href="detail.php?id=<?=$r['id']?>"><i class="fa-solid fa-eye"></i></a></td></tr><?php endforeach;?></tbody></table></div></div></div>
</main></div><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script><script src="../../assets/js/script.js"></script></body></html>

<?php
require_once __DIR__.'/../../auth/auth_check.php';
require_once __DIR__.'/../../config/database.php';

$search = trim($_GET['q'] ?? '');
$status = trim($_GET['status'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$where = [];
$params = [];

if ($search !== '') {
    $where[] = '(nomor_permohonan LIKE ? OR nama_pemohon LIKE ? OR nomor_ippkh LIKE ?)';
    $term = '%' . $search . '%';
    array_push($params, $term, $term, $term);
}
if ($status !== '') {
    $where[] = 'status = ?';
    $params[] = $status;
}
$whereSql = $where ? ' WHERE ' . implode(' AND ', $where) : '';
$countStmt = $pdo->prepare('SELECT COUNT(*) FROM permohonan' . $whereSql);
$countStmt->execute($params);
$total = (int)$countStmt->fetchColumn();
$totalPages = max(1, (int)ceil($total / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;
$stmt = $pdo->prepare('SELECT * FROM permohonan' . $whereSql . ' ORDER BY created_at DESC LIMIT ' . $perPage . ' OFFSET ' . $offset);
$stmt->execute($params);
$rows = $stmt->fetchAll();
$queryString = http_build_query(array_filter(['q' => $search, 'status' => $status], static fn($v) => $v !== ''));
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Rekap Permohonan | SICERDAS</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet"><link href="../../assets/css/style.css" rel="stylesheet"></head><body>
<?php include __DIR__.'/../../layouts/sidebar.php'; ?><div class="main-wrapper"><?php include __DIR__.'/../../layouts/navbar.php'; ?><main class="content">
<div class="page-heading"><div><p>Data Administrasi</p><h1>Rekap Permohonan</h1></div><a class="btn btn-orange" href="create.php"><i class="fa-solid fa-plus"></i> Permohonan Baru</a></div>
<div class="panel table-panel"><div class="panel-header"><h2>Daftar Permohonan <span class="text-muted fw-normal">(<?= $total ?>)</span></h2><a href="export.php<?= $queryString ? '?' . htmlspecialchars($queryString, ENT_QUOTES, 'UTF-8') : '' ?>"><i class="fa-solid fa-file-csv"></i> Export CSV</a></div><div class="p-3">
<form class="row g-2 mb-3" method="get"><div class="col-md-5"><input class="form-control" name="q" placeholder="Cari nomor, pemohon, atau IPPKH" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>"></div><div class="col-md-3"><select class="form-select" name="status"><option value="">Semua status</option><?php foreach(['Draft','Diajukan','Dalam Pemeriksaan','Perlu Perbaikan','Disetujui','Ditolak','Selesai'] as $s): ?><option value="<?= htmlspecialchars($s, ENT_QUOTES, 'UTF-8') ?>" <?= $status === $s ? 'selected' : '' ?>><?= htmlspecialchars($s) ?></option><?php endforeach; ?></select></div><div class="col-md-2"><button class="btn btn-detail w-100"><i class="fa-solid fa-magnifying-glass"></i> Cari</button></div><div class="col-md-2"><a class="btn btn-outline-secondary w-100" href="index.php">Reset</a></div></form>
<div class="table-responsive"><table class="table sicerdas-table"><thead><tr><th>Nomor Permohonan</th><th>Tanggal</th><th>Pemohon</th><th>Jenis</th><th>IPPKH</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
<?php if (!$rows): ?><tr><td colspan="7" class="text-center py-4">Tidak ada data permohonan yang cocok.</td></tr><?php endif; ?>
<?php foreach($rows as $r): ?><tr><td><?= htmlspecialchars($r['nomor_permohonan']) ?></td><td><?= date('d/m/Y', strtotime($r['tanggal_permohonan'])) ?></td><td><?= htmlspecialchars($r['nama_pemohon']) ?></td><td><?= htmlspecialchars($r['jenis_permohonan']) ?></td><td><?= htmlspecialchars($r['nomor_ippkh'] ?? '') ?></td><td><span class="status status-<?= strtolower(str_replace(' ','-',htmlspecialchars($r['status'], ENT_QUOTES, 'UTF-8'))) ?>"><?= htmlspecialchars($r['status']) ?></span></td><td><a class="btn btn-sm btn-detail" href="detail.php?id=<?= (int)$r['id'] ?>" aria-label="Detail"><i class="fa-solid fa-eye"></i></a></td></tr><?php endforeach; ?>
</tbody></table></div>
<?php if ($totalPages > 1): ?><nav aria-label="Navigasi halaman"><ul class="pagination justify-content-end mb-0"><?php for($i=1;$i<=$totalPages;$i++): ?><li class="page-item <?= $i===$page?'active':'' ?>"><a class="page-link" href="?<?= $queryString ? htmlspecialchars($queryString, ENT_QUOTES, 'UTF-8').'&' : '' ?>page=<?=$i?>"><?=$i?></a></li><?php endfor; ?></ul></nav><?php endif; ?>
</div></div></main></div><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script><script src="../../assets/js/script.js"></script></body></html>

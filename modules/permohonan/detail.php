<?php
require_once __DIR__.'/../../auth/auth_check.php';
require_once __DIR__.'/../../config/database.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM permohonan WHERE id = ?');
$stmt->execute([$id]);
$r = $stmt->fetch();
if (!$r) { http_response_code(404); exit('Permohonan tidak ditemukan'); }
$allowed = ['Draft','Diajukan','Dalam Pemeriksaan','Perlu Perbaikan','Disetujui','Ditolak','Selesai'];
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!in_array($_POST['status'] ?? '', $allowed, true)) {
        $error = 'Status yang dipilih tidak valid.';
    } else {
        $newStatus = $_POST['status'];
        if ($newStatus !== $r['status']) {
            $update = $pdo->prepare('UPDATE permohonan SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?');
            $update->execute([$newStatus, $id]);
            $log = $pdo->prepare('INSERT INTO aktivitas_log (user_id,aksi,entitas,entitas_id,detail) VALUES (?,?,?,?,?)');
            $log->execute([$_SESSION['user_id'], 'Mengubah Status', 'permohonan', $id, 'Status ' . $r['status'] . ' menjadi ' . $newStatus]);
            $notice = $pdo->prepare('INSERT INTO notifikasi (user_id,judul,pesan) VALUES (?,?,?)');
            $notice->execute([$r['dibuat_oleh'], 'Status permohonan diperbarui', $r['nomor_permohonan'] . ' kini berstatus ' . $newStatus]);
        }
        header('Location: detail.php?id=' . $id); exit;
    }
}
$logStmt = $pdo->prepare('SELECT a.*,u.nama FROM aktivitas_log a LEFT JOIN users u ON u.id=a.user_id WHERE a.entitas="permohonan" AND a.entitas_id=? ORDER BY a.created_at DESC LIMIT 20');
$logStmt->execute([$id]);$logs=$logStmt->fetchAll();
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Detail Permohonan | SICERDAS</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet"><link href="../../assets/css/style.css" rel="stylesheet"></head><body><?php include __DIR__.'/../../layouts/sidebar.php'; ?><div class="main-wrapper"><?php include __DIR__.'/../../layouts/navbar.php'; ?><main class="content">
<div class="page-heading"><div><p>Detail Permohonan</p><h1><?= htmlspecialchars($r['nomor_permohonan']) ?></h1></div><a class="btn btn-detail" href="index.php"><i class="fa-solid fa-arrow-left"></i> Kembali</a></div>
<div class="row g-3"><div class="col-lg-8"><div class="panel p-4"><dl class="row mb-0"><dt class="col-sm-4">Nama Pemohon</dt><dd class="col-sm-8"><?= htmlspecialchars($r['nama_pemohon']) ?></dd><dt class="col-sm-4">Tanggal</dt><dd class="col-sm-8"><?= date('d/m/Y',strtotime($r['tanggal_permohonan'])) ?></dd><dt class="col-sm-4">Nomor IPPKH</dt><dd class="col-sm-8"><?= htmlspecialchars($r['nomor_ippkh'] ?: '-') ?></dd><dt class="col-sm-4">Jenis Permohonan</dt><dd class="col-sm-8"><?= htmlspecialchars($r['jenis_permohonan']) ?></dd><dt class="col-sm-4">Perihal</dt><dd class="col-sm-8"><?= htmlspecialchars($r['perihal'] ?: '-') ?></dd><dt class="col-sm-4">Lokasi</dt><dd class="col-sm-8"><?= htmlspecialchars(trim(($r['kecamatan']??'').' '.($r['kabupaten']??'').' '.($r['provinsi']??''))) ?></dd><dt class="col-sm-4">Luas Lahan</dt><dd class="col-sm-8"><?= number_format((float)$r['luas_lahan'],2,',','.') ?> Ha</dd><dt class="col-sm-4">Deskripsi</dt><dd class="col-sm-8"><?= nl2br(htmlspecialchars($r['deskripsi'] ?? '-')) ?></dd><dt class="col-sm-4">Dokumen</dt><dd class="col-sm-8"><?php if($r['dokumen_pendukung']): ?><a href="../../uploads/dokumen/<?= rawurlencode($r['dokumen_pendukung']) ?>" target="_blank" rel="noopener">Lihat/unduh dokumen</a><?php else: ?>-<?php endif; ?></dd></dl></div>
<div class="panel mt-3"><div class="panel-header"><h2>Riwayat Aktivitas</h2></div><div class="p-3"><?php if(!$logs): ?><p class="text-muted mb-0">Belum ada riwayat.</p><?php else: ?><ul class="list-unstyled mb-0"><?php foreach($logs as $log): ?><li class="border-bottom py-2"><strong><?= htmlspecialchars($log['aksi']) ?></strong> — <?= htmlspecialchars($log['detail'] ?? '') ?><small class="d-block text-muted"><?= htmlspecialchars($log['nama'] ?? 'Sistem') ?> · <?= date('d/m/Y H:i',strtotime($log['created_at'])) ?></small></li><?php endforeach; ?></ul><?php endif; ?></div></div></div>
<div class="col-lg-4"><div class="panel p-4"><h2 class="fs-6 mb-3">Status Permohonan</h2><?php if($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?><span class="status status-<?= strtolower(str_replace(' ','-',htmlspecialchars($r['status'], ENT_QUOTES, 'UTF-8'))) ?> d-inline-block mb-3"><?= htmlspecialchars($r['status']) ?></span><form method="post"><label class="form-label">Ubah Status</label><select class="form-select mb-3" name="status"><?php foreach($allowed as $s): ?><option value="<?=htmlspecialchars($s)?>" <?=$r['status']===$s?'selected':''?>><?=htmlspecialchars($s)?></option><?php endforeach; ?></select><button class="btn btn-orange w-100">Simpan Status</button></form></div></div></div>
</main></div><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script></body></html>

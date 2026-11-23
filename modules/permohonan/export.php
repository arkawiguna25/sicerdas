<?php
require_once __DIR__.'/../../auth/auth_check.php';
require_once __DIR__.'/../../config/database.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="permohonan-sicerdas.csv"');
$out=fopen('php://output','w');
fwrite($out,"sep=,\n");
fputcsv($out,['Nomor Permohonan','Tanggal','Pemohon','Jenis','IPPKH','Provinsi','Kabupaten','Status']);
foreach($pdo->query('SELECT nomor_permohonan,tanggal_permohonan,nama_pemohon,jenis_permohonan,nomor_ippkh,provinsi,kabupaten,status FROM permohonan ORDER BY created_at DESC') as $r){
    fputcsv($out,$r);
}
fclose($out);

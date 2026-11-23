<?php
require_once __DIR__.'/../../auth/auth_check.php';
require_once __DIR__.'/../../config/database.php';
$where=[];$params=[];
if(trim($_GET['q']??'')!==''){$where[]='(nomor_permohonan LIKE ? OR nama_pemohon LIKE ? OR nomor_ippkh LIKE ?)';$term='%'.trim($_GET['q']).'%';array_push($params,$term,$term,$term);}
if(trim($_GET['status']??'')!==''){$where[]='status=?';$params[]=$_GET['status'];}
$sql='SELECT nomor_permohonan,tanggal_permohonan,nama_pemohon,jenis_permohonan,nomor_ippkh,provinsi,kabupaten,status FROM permohonan'.($where?' WHERE '.implode(' AND ',$where):'').' ORDER BY created_at DESC';
$stmt=$pdo->prepare($sql);$stmt->execute($params);
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="sicerdas-permohonan-'.date('Y-m-d').'.csv"');
$out=fopen('php://output','w');fwrite($out,"\xEF\xBB\xBF");
fputcsv($out,['Nomor Permohonan','Tanggal','Pemohon','Jenis','IPPKH','Provinsi','Kabupaten','Status']);
while($row=$stmt->fetch(PDO::FETCH_NUM)){
    foreach($row as &$cell){$cell=(string)$cell;if(preg_match('/^[=+@\-\t\r]/',$cell)){$cell="'".$cell;}}
    unset($cell);fputcsv($out,$row);
}
fclose($out);exit;

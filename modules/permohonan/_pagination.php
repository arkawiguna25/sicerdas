<?php
require_once __DIR__.'/../../auth/auth_check.php';
require_once __DIR__.'/../../config/database.php';
$limit=10;$page=max(1,(int)($_GET['page']??1));$offset=($page-1)*$limit;
$where=[];$params=[];
if(trim($_GET['q']??'')!==''){$where[]='(nomor_permohonan LIKE ? OR nama_pemohon LIKE ? OR nomor_ippkh LIKE ?)';$v='%'.trim($_GET['q']).'%';$params=[$v,$v,$v];}
if(trim($_GET['status']??'')!==''){$where[]='status=?';$params[]=$_GET['status'];}
$sql='SELECT * FROM permohonan'.($where?' WHERE '.implode(' AND ',$where):'').' ORDER BY created_at DESC LIMIT '.$limit.' OFFSET '.$offset;
$stmt=$pdo->prepare($sql);$stmt->execute($params);$rows=$stmt->fetchAll();
$count=$pdo->query('SELECT COUNT(*) FROM permohonan')->fetchColumn();$pages=max(1,(int)ceil($count/$limit));
?>

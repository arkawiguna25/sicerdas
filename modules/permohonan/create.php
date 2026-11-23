<?php
require_once __DIR__.'/../../auth/auth_check.php';
require_once __DIR__.'/../../config/database.php';
$error='';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $nomor='REQ-'.date('Y').'-'.str_pad((string)random_int(1,9999),4,'0',STR_PAD_LEFT);
    $nama=trim($_POST['nama_pemohon']??'');
    $jenis=trim($_POST['jenis_permohonan']??'');

    if($nama===''||$jenis===''){
        $error='Nama perusahaan dan jenis permohonan wajib diisi.';
    } else {
        $fileName=null;
        if(isset($_FILES['dokumen'])&&$_FILES['dokumen']['error']===UPLOAD_ERR_OK){
            $allowed=['pdf','doc','docx','jpg','jpeg','png'];
            $ext=strtolower(pathinfo($_FILES['dokumen']['name'],PATHINFO_EXTENSION));
            if(in_array($ext,$allowed,true)&&$_FILES['dokumen']['size']<=5242880){
                $dir=__DIR__.'/../../uploads/dokumen';
                if(!is_dir($dir)){mkdir($dir,0777,true);}
                $fileName='permohonan_'.time().'_'.$nomor.'.'.$ext;
                move_uploaded_file($_FILES['dokumen']['tmp_name'],$dir.'/'.$fileName);
            }
        }

        $stmt=$pdo->prepare('INSERT INTO permohonan (nomor_permohonan,tanggal_permohonan,nama_pemohon,nomor_ippkh,jenis_permohonan,perihal,provinsi,kabupaten,kecamatan,luas_lahan,deskripsi,dokumen_pendukung,status,dibuat_oleh) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
        $stmt->execute([
            $nomor,$_POST['tanggal_permohonan']??date('Y-m-d'),$nama,$_POST['nomor_ippkh']??'',
            $jenis,$_POST['perihal']??'',$_POST['provinsi']??'',$_POST['kabupaten']??'',
            $_POST['kecamatan']??'',(float)($_POST['luas_lahan']??0),$_POST['deskripsi']??'',
            $fileName,'Draft',$_SESSION['user_id']
        ]);
        $id=(int)$pdo->lastInsertId();
        $log=$pdo->prepare('INSERT INTO aktivitas_log (user_id,aksi,entitas,entitas_id,detail) VALUES (?,?,?,?,?)');
        $log->execute([$_SESSION['user_id'],'Membuat','permohonan',$id,'Permohonan '.$nomor.' dibuat']);
        header('Location: detail.php?id='.$id);exit;
    }
}
?>
<!doctype html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Permohonan Baru | SICERDAS</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet"><link href="../../assets/css/style.css" rel="stylesheet"></head><body>
<?php include __DIR__.'/../../layouts/sidebar.php'; ?>
<div class="main-wrapper"><?php include __DIR__.'/../../layouts/navbar.php'; ?>
<main class="content"><div class="page-heading"><div><p>Permohonan Baru</p><h1>Tambah Permohonan</h1></div><a class="btn btn-detail" href="index.php">Kembali</a></div>
<div class="panel p-4"><form method="post" enctype="multipart/form-data" class="row g-3">
<?php if($error): ?><div class="col-12"><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div></div><?php endif; ?>
<div class="col-md-6"><label class="form-label">Tanggal Permohonan</label><input type="date" class="form-control" name="tanggal_permohonan" value="<?= date('Y-m-d') ?>"></div>
<div class="col-md-6"><label class="form-label">Nama Perusahaan / Pemohon</label><input class="form-control" name="nama_pemohon" required></div>
<div class="col-md-6"><label class="form-label">Nomor IPPKH</label><input class="form-control" name="nomor_ippkh"></div>
<div class="col-md-6"><label class="form-label">Jenis Permohonan</label><select class="form-select" name="jenis_permohonan" required><option value="">Pilih jenis</option><option>Rehabilitasi DAS</option><option>Lahan Kompensasi</option><option>Reklamasi Hutan</option><option>Tarlam</option></select></div>
<div class="col-12"><label class="form-label">Perihal Permohonan</label><input class="form-control" name="perihal"></div>
<div class="col-md-4"><label class="form-label">Provinsi</label><input class="form-control" name="provinsi"></div><div class="col-md-4"><label class="form-label">Kabupaten</label><input class="form-control" name="kabupaten"></div><div class="col-md-4"><label class="form-label">Kecamatan</label><input class="form-control" name="kecamatan"></div>
<div class="col-md-4"><label class="form-label">Luas Lahan (Ha)</label><input type="number" step="0.01" min="0" class="form-control" name="luas_lahan"></div>
<div class="col-md-8"><label class="form-label">Dokumen Pendukung</label><input type="file" class="form-control" name="dokumen" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"></div>
<div class="col-12"><label class="form-label">Deskripsi Permohonan</label><textarea class="form-control" name="deskripsi" rows="4"></textarea></div>
<div class="col-12"><button class="btn btn-orange" type="submit">Simpan</button> <button class="btn btn-secondary" type="reset">Reset</button> <a class="btn btn-outline-secondary" href="index.php">Batal</a></div>
</form></div></main></div><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script></body></html>

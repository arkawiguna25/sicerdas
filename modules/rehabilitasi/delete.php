<?php
require_once __DIR__.'/../../auth/auth_check.php';
require_once __DIR__.'/../../config/database.php';
if($_SERVER['REQUEST_METHOD']==='POST'&&($_SESSION['role']??'')==='admin'){$id=(int)$_POST['id'];$pdo->prepare('DELETE FROM rehabilitasi_das WHERE id=?')->execute([$id]);header('Location: index.php');exit;}
?>
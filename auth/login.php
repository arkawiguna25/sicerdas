<?php
session_start();
require_once __DIR__ . '/../config/database.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username dan password wajib diisi.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? AND status = "aktif" LIMIT 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header('Location: ../index.php');
            exit;
        }
        $error = 'Username atau password salah.';
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login | SICERDAS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="login-page">
  <div class="login-card">
    <div class="brand-lockup"><div class="brand-mark"><i class="fa-solid fa-leaf"></i></div><div><strong>SICERDAS</strong><small>Sistem Rehabilitasi DAS</small></div></div>
    <?php if ($error): ?><div class="alert alert-danger py-2"><i class="fa-solid fa-circle-exclamation me-1"></i><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="post" autocomplete="on">
      <label class="form-label">Username</label>
      <div class="input-group mb-3"><span class="input-group-text"><i class="fa-regular fa-user"></i></span><input class="form-control" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required></div>
      <label class="form-label">Password</label>
      <div class="input-group mb-4"><span class="input-group-text"><i class="fa-solid fa-lock"></i></span><input class="form-control" id="password" type="password" name="password" required><button class="btn btn-outline-secondary" type="button" id="togglePassword"><i class="fa-regular fa-eye"></i></button></div>
      <button class="btn btn-primary w-100 login-button">Masuk <i class="fa-solid fa-arrow-right ms-1"></i></button>
    </form>
  </div>
  <script src="../assets/js/script.js"></script>
</body>
</html>

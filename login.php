<?php
session_start();

// Redirect jika sudah login
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin.php");
    } else {
        header("Location: kasir.php");
    }
    exit();
}
// Proses login
$error = "";
if (isset($_POST['username'])) {
    $conn = mysqli_connect("localhost", "root", "zwei1", "kasir_mini");

    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);

    $data = mysqli_query($conn, "SELECT * FROM users WHERE username='$user' AND password='$pass'");
    $cek  = mysqli_num_rows($data);

    if ($cek > 0) {
        $d = mysqli_fetch_assoc($data);
        $_SESSION['role']     = $d['role'];
        $_SESSION['username'] = $d['username'];
        $_SESSION['id_user']  = $d['id_user'];

        if ($d['role'] == 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: kasir.php");
        }
        exit();
    } else {
        $error = "Username atau password salah!";
    }

    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background-image: url(assets/img/kasir.png);
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }

    .login-card {
      background: #ffffff;
      border: 1px solid #e5e7eb;
      border-radius: 12px;
      padding: 2.5rem 2rem;
      width: 100%;
      max-width: 360px;
      box-shadow: 0 4px 16px rgba(0,0,0,0.07);
    }

    .login-logo {
      width: 44px;
      height: 44px;
      background: #eff6ff;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1.25rem;
    }

    .login-logo i { font-size: 22px; color: #3b82f6; }

    .login-title {
      font-size: 20px;
      font-weight: 600;
      color: #111827;
      text-align: center;
      margin-bottom: 0.25rem;
    }

    .login-sub {
      font-size: 13px;
      color: #6b7280;
      text-align: center;
      margin-bottom: 1.75rem;
    }

    .error-msg {
      background: #fef2f2;
      border: 1px solid #fecaca;
      color: #dc2626;
      font-size: 13px;
      padding: 10px 12px;
      border-radius: 8px;
      margin-bottom: 1rem;
      text-align: center;
    }

    .field-group { margin-bottom: 1rem; }

    .field-label {
      display: block;
      font-size: 13px;
      font-weight: 500;
      color: #374151;
      margin-bottom: 6px;
    }

    .field-wrap {
      position: relative;
      display: flex;
      align-items: center;
    }

    .field-icon {
      position: absolute;
      left: 10px;
      color: #9ca3af;
      font-size: 16px;
      pointer-events: none;
    }

    .field-input {
      width: 100%;
      height: 40px;
      padding: 0 12px 0 34px;
      font-size: 14px;
      border: 1px solid #d1d5db;
      border-radius: 8px;
      outline: none;
      transition: border-color 0.2s;
      background: #fff;
      color: #111827;
    }

    .field-input:focus { border-color: #3b82f6; }

    .btn-login {
      width: 100%;
      height: 42px;
      margin-top: 1.25rem;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      background: #3b82f6;
      color: #ffffff;
      border: none;
      border-radius: 8px;
      letter-spacing: 0.01em;
      transition: background 0.15s;
    }

    .btn-login:hover { background: #2563eb; }
    .btn-login:active { transform: scale(0.98); }

    .forgot {
      text-align: center;
      margin-top: 1rem;
      font-size: 12px;
      color: #6b7280;
    }

    .forgot a { color: #3b82f6; text-decoration: none; }
    .forgot a:hover { text-decoration: underline; }
  </style>
</head>
<body>

  <div class="login-card">
    <div class="login-logo">
      <i class="ti ti-lock"></i>
    </div>
    <p class="login-title">Selamat datang</p>
    <p class="login-sub">Masuk ke akun Anda untuk melanjutkan</p>

    <?php if ($error): ?>
      <div class="error-msg">
        <i class="ti ti-alert-circle"></i> <?= $error ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="field-group">
        <label class="field-label" for="username">Username</label>
        <div class="field-wrap">
          <i class="ti ti-user field-icon"></i>
          <input class="field-input" type="text" id="username" name="username" 
                 placeholder="Masukkan username" required>
        </div>
      </div>

      <div class="field-group">
        <label class="field-label" for="password">Password</label>
        <div class="field-wrap">
          <i class="ti ti-key field-icon"></i>
          <input class="field-input" type="password" id="password" name="password" 
                 placeholder="Masukkan password" required>
        </div>
      </div>

      <button type="submit" class="btn-login">Masuk</button>
    </form>

    <p class="forgot"><a href="#">Lupa password?</a></p>
  </div>

</body>
</html>
<?php
include_once("config.php");
if (isLoggedIn()) { header('Location: index.php'); exit(); }

$errors = [];
$success = "";

if (isset($_POST['register'])) {
    $username  = mysqli_real_escape_string($conn, $_POST['username']);
    $email     = mysqli_real_escape_string($conn, $_POST['email']);
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $password  = $_POST['password'];
    $confirm   = $_POST['confirm_password'];

    if (empty($username))  $errors[] = 'Username tidak boleh kosong';
    if (empty($email))     $errors[] = 'Email tidak boleh kosong';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid';
    if (empty($full_name)) $errors[] = 'Nama lengkap tidak boleh kosong';
    if (strlen($password) < 6) $errors[] = 'Password minimal 6 karakter';
    if ($password !== $confirm)  $errors[] = 'Konfirmasi password tidak cocok';

    $check = mysqli_query($conn, "SELECT id FROM users WHERE username='$username' OR email='$email'");
    if (mysqli_num_rows($check) > 0) $errors[] = 'Username atau email sudah terdaftar';

    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, full_name, password) VALUES ('$username','$email','$full_name','$hashed')";
        if (mysqli_query($conn, $sql))
            $success = 'Registrasi berhasil! Silakan login.';
        else
            $errors[] = 'Error: ' . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body { font-family: Arial; display: flex; justify-content: center; align-items: center; min-height: 100vh; background: #f0f2f5; margin: 0; }
        .box { background: white; padding: 30px; border-radius: 8px; width: 380px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; margin-bottom: 20px; }
        input { width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #2196F3; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .error { color: red; }
        .success { color: green; }
        a { display: block; text-align: center; margin-top: 10px; }
    </style>
</head>
<body>
<div class="box">
    <h2>Register</h2>
    <?php if ($success): ?><p class="success"><?= $success ?></p><?php endif; ?>
    <?php foreach ($errors as $e): ?><p class="error"><?= $e ?></p><?php endforeach; ?>
    <form method="POST">
        <input type="text" name="full_name" placeholder="Nama Lengkap" required value="<?= isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : '' ?>">
        <input type="text" name="username" placeholder="Username" required value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
        <input type="email" name="email" placeholder="Email" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
        <input type="password" name="password" placeholder="Password (min 6 karakter)" required>
        <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required>
        <button type="submit" name="register">Daftar</button>
    </form>
    <a href="login.php">Sudah punya akun? Login</a>
</div>
</body>
</html>
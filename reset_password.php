<?php include "koneksi.php"; session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password Admin</h2>

<?php
if (!isset($_SESSION['reset_email'])) {
    echo "<p>Halaman tidak valid. Kembali ke <a href='lupa_password.php'>lupa password</a>.</p>";
    exit;
}
?>

<form method="POST" action="">
    <label>Password Baru:</label><br>
    <input type="password" name="password1" required><br>

    <label>Ulangi Password Baru:</label><br>
    <input type="password" name="password2" required><br><br>

    <input type="submit" name="reset" value="Reset Password">
</form>

<?php
if (isset($_POST['reset'])) {
    $pass1 = $_POST['password1'];
    $pass2 = $_POST['password2'];

    if ($pass1 !== $pass2) {
        echo "<p>Password tidak sama!</p>";
    } else {
        $email = $_SESSION['reset_email'];
        $hash = password_hash($pass1, PASSWORD_DEFAULT);
        $update = mysqli_query($koneksi, "UPDATE admin SET password='$hash' WHERE email='$email'");
        unset($_SESSION['reset_email']);
        echo "<script>alert('Password berhasil diubah. Silakan login.'); window.location.href='login.php';</script>";
    }
}
?>
</body>
</html>

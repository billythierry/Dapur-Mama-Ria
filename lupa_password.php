<?php include "koneksi.php"; session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password</title>
</head>
<body>
    <h2>Lupa Password Admin</h2>
    <form method="POST" action="">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        <input type="submit" name="cek" value="Lanjut Reset Password">
    </form>

<?php
if (isset($_POST['cek'])) {
    $email = $_POST['email'];
    $query = mysqli_query($koneksi, "SELECT * FROM admin WHERE email='$email'");

    if (mysqli_num_rows($query) > 0) {
        $_SESSION['reset_email'] = $email;
        header("Location: reset_password.php");
        exit;
    } else {
        echo "<p>Email tidak ditemukan.</p>";
    }
}
?>
</body>
</html>

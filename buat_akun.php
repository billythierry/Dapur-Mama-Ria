<?php include "koneksi.php"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Akun Admin</title>
</head>
<body>
    <h2>Daftar Akun Admin</h2>
    <form method="POST" action="">
        <label>Nama:</label><br>
        <input type="text" name="nama" required><br>

        <label>Email:</label><br>
        <input type="text" name="email" required><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <input type="submit" name="register" value="Daftar">
    </form>

<?php
if (isset($_POST['register'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $query = "INSERT INTO admin (nama, email, password) VALUES ('$nama', '$email', '$password')";
    
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Akun admin berhasil dibuat. Silakan login.'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Gagal membuat akun admin.');</script>";
    }
}
?>
</body>
</html>

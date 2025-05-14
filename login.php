<?php include "koneksi.php"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin Warung Makan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .container {
            max-width: 400px;
            margin: auto;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 10px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
        }
        .show-password {
            margin-top: 10px;
        }
        .forgot, .register {
            display: block;
            text-align: right;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Login Admin</h2>
    <form method="POST" action="">
        <label>Email Admin:</label>
        <input type="text" name="email" required>

        <label>Password:</label>
        <input type="password" name="password" id="password" required>

        <div class="show-password">
            <input type="checkbox" onclick="togglePassword()"> Tampilkan Password
        </div>

        <a href="lupa_password.php" class="forgot">Lupa Password?</a>

        <input type="submit" name="login" value="Login">

        <a href="buat_akun.php" class="register">Belum punya akun?</a>
    </form>
</div>

<script>
function togglePassword() {
    var x = document.getElementById("password");
    x.type = (x.type === "password") ? "text" : "password";
}
</script>

<?php
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM admin WHERE email='$email'";
    $result = mysqli_query($koneksi, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        
        if (password_verify($password, $data['password'])) {
            // Set session data
            session_start();
            $_SESSION['admin_id'] = $data['id'];
            $_SESSION['admin_nama'] = $data['nama'];
            $_SESSION['admin_email'] = $data['email'];
            
            // Login berhasil
            echo "<script>alert('Login berhasil sebagai admin'); window.location.href='dashboard.php';</script>";
        } else {
            // Password salah
            echo "<script>alert('Password salah!');</script>";
        }
    } else {
        // Email tidak ditemukan
        echo "<script>alert('Email tidak terdaftar!');</script>";
    }
}
?>
</body>
</html>
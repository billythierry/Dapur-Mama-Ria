<?php 
include "koneksi.php";
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil data admin yang login
$admin_id = $_SESSION['admin_id'];
$admin_nama = $_SESSION['admin_nama'];

// Proses tambah kategori
if (isset($_POST['tambah_kategori'])) {
    $nama_kategori = $_POST['nama_kategori'];
    
    $query_kategori = "INSERT INTO kategori (nama) VALUES ('$nama_kategori')";
    
    if (mysqli_query($koneksi, $query_kategori)) {
        $success_message = "Kategori berhasil ditambahkan!";
    } else {
        $error_message = "Gagal menambahkan kategori: " . mysqli_error($koneksi);
    }
}

// Proses tambah menu
if (isset($_POST['tambah_menu'])) {
    $nama_menu = $_POST['nama_menu'];
    //$kategori = $_POST['kategori'];
    $kategori_id = $_POST['kategori_id'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    
    // Cek apakah kategori_id valid
    $check_kategori = mysqli_query($koneksi, "SELECT id FROM kategori WHERE id = '$kategori_id'");
    if (mysqli_num_rows($check_kategori) > 0) {
        $query = "INSERT INTO menu (admin_id, nama_menu, kategori_id, deskripsi, harga) 
                VALUES ('$admin_id', '$nama_menu', '$kategori_id', '$deskripsi', '$harga')";
        
        if (mysqli_query($koneksi, $query)) {
            $success_message = "Menu berhasil ditambahkan!";
        } else {
            $error_message = "Gagal menambahkan menu: " . mysqli_error($koneksi);
        }
    } else {
        $error_message = "Kategori ID tidak valid! Silakan pilih kategori yang tersedia.";
    }
}

// Ambil daftar kategori
$query_kategori_list = "SELECT * FROM kategori ORDER BY id ASC";
$result_kategori = mysqli_query($koneksi, $query_kategori_list);

// Ambil daftar menu
$query_menu = "SELECT m.*, k.nama AS nama_kategori 
               FROM menu m
               LEFT JOIN kategori k ON m.kategori_id = k.id
               ORDER BY m.id DESC";
$result_menu = mysqli_query($koneksi, $query_menu);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin Warung</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
        }
        
        .welcome {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            overflow: hidden;
        }
        
        .card-header {
            background-color: #4CAF50;
            color: white;
            padding: 15px 20px;
        }
        
        .card-body {
            padding: 20px;
        }
        
        form {
            display: grid;
            grid-gap: 15px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        
        button:hover {
            background-color: #45a049;
        }
        
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
        }
        
        .alert-danger {
            background-color: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
        }
        
        .table-container {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table th, table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        table th {
            background-color: #f2f2f2;
        }
        
        .logout {
            background-color: #f44336;
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 4px;
        }
        
        .logout:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Dashboard Admin Warung</h1>
            <a href="logout.php" class="logout">Logout</a>
        </div>
        
        <div class="welcome">
            <h2>Selamat datang, <?php echo htmlspecialchars($admin_nama); ?>!</h2>
            <p>Anda login sebagai Admin dengan ID: <?php echo $admin_id; ?></p>
        </div>
        
        <?php if(isset($success_message)): ?>
        <div class="alert alert-success">
            <?php echo $success_message; ?>
        </div>
        <?php endif; ?>
        
        <?php if(isset($error_message)): ?>
        <div class="alert alert-danger">
            <?php echo $error_message; ?>
        </div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-header">
                <h2>Tambah Kategori</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="nama_kategori">Nama Kategori:</label>
                        <input type="text" id="nama_kategori" name="nama_kategori" required>
                    </div>
                    
                    <button type="submit" name="tambah_kategori">Tambah Kategori</button>
                </form>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h2>Daftar Kategori</h2>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Kategori</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($result_kategori) > 0): ?>
                                <?php while($kategori = mysqli_fetch_assoc($result_kategori)): ?>
                                    <tr>
                                        <td><?php echo $kategori['id']; ?></td>
                                        <td><?php echo htmlspecialchars($kategori['nama']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="2">Belum ada kategori yang ditambahkan</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h2>Tambah Menu Baru</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="nama_menu">Nama Menu:</label>
                        <input type="text" id="nama_menu" name="nama_menu" required>
                    </div>
                    
                    <!-- <div class="form-group">
                        <label for="kategori">Kategori:</label>
                        <select id="kategori" name="kategori" required>
                            <option value="Makanan">Makanan</option>
                            <option value="Minuman">Minuman</option>
                        </select>
                    </div> -->
                    
                    <div class="form-group">
                        <label for="kategori_id">Kategori ID:</label>
                        <select id="kategori_id" name="kategori_id" required>
                            <?php 
                            // Reset pointer hasil query
                            mysqli_data_seek($result_kategori, 0);
                            while($kat = mysqli_fetch_assoc($result_kategori)): 
                            ?>
                                <option value="<?php echo $kat['id']; ?>">
                                    <?php echo htmlspecialchars($kat['nama']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <small>Pilih kategori yang tersedia</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi:</label>
                        <textarea id="deskripsi" name="deskripsi" rows="4" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="harga">Harga (Rp):</label>
                        <input type="number" id="harga" name="harga" min="0" required>
                    </div>
                    
                    <button type="submit" name="tambah_menu">Tambah Menu</button>
                </form>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h2>Daftar Menu</h2>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Menu</th>
                                <th>Kategori</th>
                                <th>Deskripsi</th>
                                <th>Harga</th>
                                <th>Admin</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($result_menu) > 0): ?>
                                <?php while($menu = mysqli_fetch_assoc($result_menu)): ?>
                                    <tr>
                                        <td><?php echo $menu['id']; ?></td>
                                        <td><?php echo htmlspecialchars($menu['nama_menu']); ?></td>
                                        <td>ID: <?php echo $menu['kategori_id']; ?> - <?php echo htmlspecialchars($menu['nama_kategori']); ?></td>
                                        <td><?php echo htmlspecialchars($menu['deskripsi']); ?></td>
                                        <td>Rp <?php echo number_format($menu['harga'], 0, ',', '.'); ?></td>
                                        <td>
                                            <?php
                                            $admin_query = mysqli_query($koneksi, "SELECT nama FROM admin WHERE id = '{$menu['admin_id']}'");
                                            if($admin_data = mysqli_fetch_assoc($admin_query)) {
                                                echo htmlspecialchars($admin_data['nama']);
                                            } else {
                                                echo "Admin tidak ditemukan";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6">Belum ada menu yang ditambahkan</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
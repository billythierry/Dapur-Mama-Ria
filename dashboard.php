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

// Fungsi reload tiap POST
function reloadPage(){
    header("Location: dashboard.php");
    exit;
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
    $check_isi_data = mysqli_query($koneksi, "SELECT nama_menu FROM menu where nama_menu = '$nama_menu'");

    if (mysqli_num_rows($check_kategori) > 0) {
        if (mysqli_num_rows($check_isi_data) < 1){
            $query = "INSERT INTO menu (admin_id, nama_menu, kategori_id, deskripsi, harga) 
                VALUES ('$admin_id', '$nama_menu', '$kategori_id', '$deskripsi', '$harga')";
        
            if (mysqli_query($koneksi, $query)) {
                $success_message = "Menu berhasil ditambahkan!";
            } else {
                $error_message = "Gagal menambahkan menu: " . mysqli_error($koneksi);
            }
        }else {
            $error_message = "Menu yang Anda masukkan telah tersedia"; 
        }
    } else {
        $error_message = "Kategori ID tidak valid! Silakan pilih kategori yang tersedia.";
    }

    //reloadPage();
}

// Proses Hapus Menu
if (isset($_POST['hapus_menu'])) {
    $nama_menu = $_POST['nama_menu'];
    
    $query_hapus = "DELETE FROM menu WHERE nama_menu = '$nama_menu'";

    if (mysqli_query($koneksi, $query_hapus)) {
        if (mysqli_affected_rows($koneksi) > 0) {
            $success_message = "Menu berhasil dihapus!";
        } else {
            $error_message = "Menu tidak ditemukan!";
        }
    } else {
        $error_message = "Gagal menghapus menu: " . mysqli_error($koneksi);
    }

    //reloadPage();
}

// Proses Update Menu

// Tambah Stok
if (isset($_POST['tambah_stok'])) {
    $nama_barang = $_POST['nama_barang'];
    $stok_barang = $_POST['stok_barang'];
    $satuan_barang = $_POST['satuan_barang'];

    $check_stok = mysqli_query($koneksi, "SELECT id FROM stok WHERE nama_barang = '$nama_barang'");
    if (mysqli_num_rows($check_stok) < 1) {
        $query_stok = "INSERT INTO stok (admin_id, nama_barang, jumlah, satuan) VALUES ('$admin_id', '$nama_barang', '$stok_barang', '$satuan_barang')";

        if (mysqli_query($koneksi, $query_stok)) {
            $success_message = "Stok barang berhasil ditambahkan!";
        } else {
            $error_message = "Gagal menambahkan stok barang: " . mysqli_error($koneksi);
        }
    }else if(mysqli_num_rows($check_stok) > 0){
        $query_stok = "UPDATE stok SET jumlah = '$stok_barang' + jumlah WHERE nama_barang = '$nama_barang'";

        if (mysqli_query($koneksi, $query_stok)) {
            $success_message = "Stok barang berhasil ditambahkan!";
        } else {
            $error_message = "Gagal menambahkan stok barang: " . mysqli_error($koneksi);
        }

    } else {
        $error_message = "Barang yang kamu masukkan sudah ada, silahkan pergi ke menu edit"; 
    }
}

// Hapus Stok
if (isset($_POST['hapus_stok'])) {
    $nama_barang = $_POST['nama_barang'];

    $query_hapus_stok = "DELETE FROM stok WHERE nama_barang = '$nama_barang'";

    if (mysqli_query($koneksi, $query_hapus_stok)) {
        if (mysqli_affected_rows($koneksi) > 0) {
            $success_message = "Stok barang berhasil dihapus!";
        } else {
            $error_message = "Barang tidak ditemukan!";
        }
    } else {
        $error_message = "Gagal menghapus stok barang: " . mysqli_error($koneksi);
    }
}

// Tambah Pencatatan Keuangan
if (isset($_POST['tambah_keuangan'])) {
    $sumber = $_POST['sumber'];
    $nominal = $_POST['nominal'];
    $tanggal = $_POST['tanggal'];
    $bulan   = $_POST['bulan'];
    $tahun   = $_POST['tahun'];

    $tanggal_lengkap = "$tahun-$bulan-$tanggal"; // Format YYYY-MM-DD

    $check_keuangan = mysqli_query($koneksi, "SELECT * FROM keuangan WHERE tanggal = '$tanggal_lengkap'");
    if (mysqli_num_rows($check_keuangan) > 0) {
        $error_message = "Catatan keuangan untuk tanggal $tanggal_lengkap sudah ada.";
    } else {
        $query_keuangan = "INSERT INTO keuangan (admin_id, sumber, nominal, tanggal) VALUES ('$admin_id', '$sumber', '$nominal', '$tanggal_lengkap')";
        if (mysqli_query($koneksi, $query_keuangan)) {
            $success_message = "Catatan keuangan berhasil ditambahkan!";
        } else {
            $error_message = "Gagal menambahkan catatan keuangan: " . mysqli_error($koneksi);
        }
    }
}

// Hapus Data Keuangan
if (isset($_POST['hapus_keuangan'])) {
    $id = $_POST['id'];

    $query_hapus_keuangan = "DELETE FROM keuangan WHERE id = '$id'";

    if (mysqli_query($koneksi, $query_hapus_keuangan)) {
        if (mysqli_affected_rows($koneksi) > 0) {
            $success_message = "Data keuangan berhasil dihapus!";
        } else {
            $error_message = "Data keuangan tidak ditemukan!";
        }
    } else {
        $error_message = "Gagal menghapus data keuangan: " . mysqli_error($koneksi);
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

// Ambil daftar stok barang
$query_stok = "SELECT * FROM stok ORDER BY id ASC";
$result_stok = mysqli_query($koneksi, $query_stok);

// Ambil daftar keuangan
$query_keuangan = "SELECT * FROM keuangan ORDER BY id ASC";
$result_keuangan = mysqli_query($koneksi, $query_keuangan);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin Warung</title>
    <link rel="stylesheet" href="css/dashboard.css">
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
                    
                    <div class="form-group">
                        <label for="kategori_id">Kategori:</label>
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
                <h2>Hapus Menu</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="nama_menu">Nama Menu:</label>
                        <input type="text" id="nama_menu" name="nama_menu" required>
                    </div>
                    
                    <button type="submit" name="hapus_menu">Hapus Menu</button>
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
                                        <td><?php echo htmlspecialchars($menu['nama_kategori']); ?></td>
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

        <!-- PENCATATAN STOK BARANG -->

        <!-- Tambah Stok -->

        <div class="card">
            <div class="card-header">
                <h2>Tambah Stok Barang</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="nama_barang">Nama Barang:</label>
                        <input type="text" id="nama_barang" name="nama_barang" required>
                    </div>

                    <div class="form-group">
                        <label for="stok_barang">Jumlah Stok Barang:</label>
                        <input type="number" id="stok_barang" name="stok_barang" min="1" required>
                    </div>

                    <div class="form-group">
                        <label for="satuan_barang">Satuan:</label>
                        <select name="satuan_barang" id="satuan_barang">
                            <option value="buah">Buah</option>
                            <option value="butir">Butir</option>
                            <option value="kg">Kg</option>
                            <option value="gram">Gram</option>
                            <option value="liter">Liter</option>
                            <option value="ml">Ml</option>
                        </select>
                    </div>

                    <button type="submit" name="tambah_stok">Tambah Stok</button>
                </form>
            </div>
        </div>

        <!-- Hapus Stok -->
        <div class="card">
            <div class="card-header">
                <h2>Hapus Stok</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="nama_barang">Nama Barang:</label>
                        <input type="text" id="nama_barang" name="nama_barang" required>
                    </div>

                    <button type="submit" name="hapus_stok">Hapus Stok</button>
                </form>
            </div>
        </div>

        <!-- Daftar Stok Barang -->

        <div class="card">
            <div class="card-header">
                <h2>Daftar Stok Barang</h2>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Barang</th>
                                <th>Jumlah Stok Barang</th>
                                <th>Satuan</th>
                                <th>Admin</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($result_stok) > 0): ?>
                                <?php while($menu = mysqli_fetch_assoc($result_stok)): ?>
                                    <tr>
                                        <td><?php echo $menu['id']; ?></td>
                                        <td><?php echo htmlspecialchars($menu['nama_barang']); ?></td>
                                        <td><?php echo htmlspecialchars($menu['jumlah']); ?></td>
                                        <td><?php echo htmlspecialchars($menu['satuan']); ?></td>
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

        <!-- PENCATATAN KEUANGAN -->

        <!-- Tambah Data Keuangan -->
        
        <div class="card">
            <div class="card-header">
                <h2>Tambah Catatan Keuangan</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="sumber">Sumber:</label>
                        <select name="sumber" id="sumber">
                            <option value="pemasukan">Pemasukan</option>
                            <option value="pengeluaran">Pengeluaran</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="nominal">Nominal (Rp):</label>
                        <input type="number" id="nominal" name="nominal" min="0" required>
                    </div>

                    <div class="form-group">
                            <label for="tanggal">Tanggal:</label>
                            <select name="tanggal" id="tanggal">
                                <?php for ($i = 1; $i <= 31; $i++) : ?>
                                    <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>"><?= $i ?></option>
                                <?php endfor; ?>
                            </select>

                            <label for="bulan">Bulan:</label>
                            <select name="bulan" id="bulan">
                                <?php for ($i = 1; $i <= 12; $i++) : ?>
                                    <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>"><?= $i ?></option>
                                <?php endfor; ?>
                            </select>

                            <label for="tahun">Tahun:</label>
                            <select name="tahun" id="tahun">
                                <?php
                                $tahun_sekarang = date('Y');
                                for ($i = $tahun_sekarang; $i >= $tahun_sekarang - 50; $i--) :
                                ?>
                                    <option value="<?= $i ?>"><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                    </div>

                    <button type="submit" name="tambah_keuangan">Tambah Data Keuangan</button>
                </form>
            </div>
        </div>

        <!-- Hapus Data Keuangan -->
        <div class="card">
            <div class="card-header">
                <h2>Hapus data Keuangan</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="id">ID yang ingin dihapus:</label>
                        <input type="number" id="id" name="id" required>
                    </div>

                    <button type="submit" name="hapus_keuangan">Hapus Data Keuangan</button>
                </form>
            </div>
        </div>

        <!-- Daftar Keuangan -->
        <div class="card">
            <div class="card-header">
                <h2>Daftar Keuangan</h2>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Sumber</th>
                                <th>Nominal</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($result_keuangan) > 0): ?>
                                <?php while($keuangan = mysqli_fetch_assoc($result_keuangan)): ?>
                                    <tr>
                                        <td><?php echo $keuangan['id']; ?></td>
                                        <td><?php echo htmlspecialchars($keuangan['sumber']); ?></td>
                                        <td><?php echo htmlspecialchars($keuangan['nominal']); ?></td>
                                        <td><?php echo htmlspecialchars($keuangan['tanggal']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="2">Belum ada data keuangan yang ditambahkan</td>
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
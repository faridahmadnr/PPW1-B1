<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk Kopi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #6F4E37;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #6F4E37;
        }
        input[type="text"],
        input[type="number"],
        input[type="file"],
        select,
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            height: 100px;
            resize: vertical;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #6F4E37;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #5a3d2a;
        }
        .btn-secondary {
            background-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .error {
            color: red;
            margin-top: 5px;
        }
        .current-image {
            margin-top: 10px;
            margin-bottom: 10px;
            text-align: center;
        }
        .current-image img {
            max-width: 200px;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-edit"></i> Edit Produk Kopi</h1>
        
        <?php
        include_once("config.php");
        
        // Memastikan ID produk ada di URL
        if(!isset($_GET['id']) || empty($_GET['id'])) {
            // Redirect ke halaman daftar produk jika ID tidak ada atau kosong
            header("Location: produk.php");
            exit();
        }
        
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        
        // Cek apakah form sudah disubmit (untuk operasi UPDATE)
        if(isset($_POST['update'])) {
            $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
            $jenis_kopi = mysqli_real_escape_string($conn, $_POST['jenis_kopi']); // Ini akan mengambil nilai dari select
            $harga = mysqli_real_escape_string($conn, $_POST['harga']);
            $stok = mysqli_real_escape_string($conn, $_POST['stok']);
            $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
            $old_gambar_produk = mysqli_real_escape_string($conn, $_POST['old_gambar_produk']); // Hidden field for old image name
            
            $gambar_produk = $old_gambar_produk; // Default to old image if no new image is uploaded
            
            $errors = array();

            // Validasi input
            if(empty($nama_produk)) {
                $errors[] = "Nama produk tidak boleh kosong.";
            }
            if(empty($jenis_kopi)) {
                $errors[] = "Jenis kopi harus dipilih.";
            }
            if(empty($harga) || $harga <= 0) {
                $errors[] = "Harga harus diisi dan lebih dari 0.";
            }
            if(empty($stok) || $stok < 0) {
                $errors[] = "Stok harus diisi dan tidak boleh negatif.";
            }

            // Handle image upload jika ada gambar baru
            if(isset($_FILES['gambar_produk']) && $_FILES['gambar_produk']['error'] == 0) {
                $target_dir = "uploads/";
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true); // Pastikan folder ada dan bisa ditulis
                }
                $new_gambar_produk = basename($_FILES["gambar_produk"]["name"]);
                $target_file = $target_dir . $new_gambar_produk;
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                
                $check = getimagesize($_FILES["gambar_produk"]["tmp_name"]);
                if($check !== false) {
                    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                        $errors[] = "Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.";
                    }
                    if ($_FILES["gambar_produk"]["size"] > 5000000) { // 5MB limit
                        $errors[] = "Maaf, ukuran file gambar terlalu besar. Maksimal 5MB.";
                    }
                    // Jika file baru memiliki nama yang sama dengan file yang sudah ada, tambahkan timestamp
                    if (file_exists($target_file)) {
                        $new_gambar_produk = time() . '_' . $new_gambar_produk;
                        $target_file = $target_dir . $new_gambar_produk;
                    }
                } else {
                    $errors[] = "File bukan gambar atau file korup.";
                }

                // Coba upload file baru jika tidak ada error sebelumnya
                if (empty($errors)) {
                    if (move_uploaded_file($_FILES["gambar_produk"]["tmp_name"], $target_file)) {
                        // Jika upload berhasil, hapus gambar lama (jika ada)
                        if (!empty($old_gambar_produk) && file_exists($target_dir . $old_gambar_produk)) {
                            unlink($target_dir . $old_gambar_produk);
                        }
                        $gambar_produk = $new_gambar_produk; // Set gambar_produk ke nama file baru
                    } else {
                        $errors[] = "Terjadi kesalahan saat mengunggah gambar baru.";
                    }
                }
            }
            
            // Jika tidak ada error validasi atau upload
            if(empty($errors)) {
                $query = "UPDATE produk SET 
                            nama_produk='$nama_produk', 
                            jenis_kopi='$jenis_kopi', 
                            harga=$harga, 
                            stok=$stok, 
                            deskripsi='$deskripsi',
                            gambar_produk='$gambar_produk' 
                          WHERE id=$id";
                
                $result = mysqli_query($conn, $query);
                
                if($result) {
                    echo "<div style='padding: 10px; background-color: #d4edda; color: #155724; border-radius: 5px; margin-bottom: 15px;'>";
                    echo "Produk berhasil diperbarui. <a href='produk.php'>Lihat Daftar Produk</a>";
                    echo "</div>";
                } else {
                    echo "<div style='padding: 10px; background-color: #f8d7da; color: #721c24; border-radius: 5px; margin-bottom: 15px;'>";
                    echo "Error database: " . mysqli_error($conn);
                    echo "</div>";
                }
            } else {
                // Tampilkan error jika ada
                echo "<div style='padding: 10px; background-color: #f8d7da; color: #721c24; border-radius: 5px; margin-bottom: 15px;'>";
                echo "<ul>";
                foreach($errors as $error) {
                    echo "<li>$error</li>";
                }
                echo "</ul>";
                echo "</div>";
            }
        }
        
        // Ambil data produk dari database untuk mengisi form
        $result = mysqli_query($conn, "SELECT * FROM produk WHERE id=$id");
        
        if(mysqli_num_rows($result) == 0) {
            // Jika produk tidak ditemukan, redirect
            header("Location: produk.php");
            exit();
        }
        
        $row = mysqli_fetch_assoc($result);
        $nama_produk = $row['nama_produk'];
        $jenis_kopi = $row['jenis_kopi'];
        $harga = $row['harga'];
        $stok = $row['stok'];
        $deskripsi = $row['deskripsi'];
        $gambar_produk = $row['gambar_produk'];
        ?>
        
        <form action="edit_produk.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="old_gambar_produk" value="<?php echo htmlspecialchars($gambar_produk); ?>">

            <div class="form-group">
                <label for="nama_produk">Nama Produk</label>
                <input type="text" name="nama_produk" id="nama_produk" value="<?php echo htmlspecialchars($nama_produk); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="jenis_kopi">Jenis Kopi</label>
                <select name="jenis_kopi" id="jenis_kopi" required>
                    <option value="Classic Coffee" <?php echo ($jenis_kopi == 'Classic Coffee') ? 'selected' : ''; ?>>Classic Coffee</option>
                    <option value="Filter Coffee" <?php echo ($jenis_kopi == 'Filter Coffee') ? 'selected' : ''; ?>>Filter Coffee</option>
                    <option value="Speciality" <?php echo ($jenis_kopi == 'Speciality') ? 'selected' : ''; ?>>Speciality</option>
                    <option value="Drip Bag" <?php echo ($jenis_kopi == 'Drip Bag') ? 'selected' : ''; ?>>Drip Bag</option>
                    <option value="Coffee Gems" <?php echo ($jenis_kopi == 'Coffee Gems') ? 'selected' : ''; ?>>Coffee Gems</option>
                    <option value="Merchandise" <?php echo ($jenis_kopi == 'Merchandise') ? 'selected' : ''; ?>>Merchandise</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="harga">Harga (Rp)</label>
                <input type="number" name="harga" id="harga" min="0" step="1000" value="<?php echo htmlspecialchars($harga); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="stok">Stok</label>
                <input type="number" name="stok" id="stok" min="0" value="<?php echo htmlspecialchars($stok); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="deskripsi">Deskripsi Produk</label>
                <textarea name="deskripsi" id="deskripsi"><?php echo htmlspecialchars($deskripsi); ?></textarea>
            </div>

            <div class="form-group">
                <label>Gambar Produk Saat Ini</label>
                <?php if (!empty($gambar_produk)): ?>
                    <div class="current-image">
                        <img src="uploads/<?php echo htmlspecialchars($gambar_produk); ?>" alt="Gambar Produk">
                    </div>
                <?php else: ?>
                    <p>Tidak ada gambar saat ini.</p>
                <?php endif; ?>
                <label for="gambar_produk">Ganti Gambar Produk (Opsional)</label>
                <input type="file" name="gambar_produk" id="gambar_produk" accept="image/*">
            </div>
            
            <div style="margin-top: 20px;">
                <input type="submit" name="update" value="Update" class="btn">
                <a href="produk.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>
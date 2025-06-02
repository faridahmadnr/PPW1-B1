<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk Kopi</title>
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
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-coffee"></i> Tambah Produk Kopi</h1>
        
        <?php
        include_once("config.php");
        
        if(isset($_POST['submit'])) {
            $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
            $jenis_kopi = mysqli_real_escape_string($conn, $_POST['jenis_kopi']); // This value comes directly from the select option
            $harga = mysqli_real_escape_string($conn, $_POST['harga']);
            $stok = mysqli_real_escape_string($conn, $_POST['stok']);
            $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
            $tanggal_masuk = date('Y-m-d');
            
            $gambar_produk = ''; // Default empty string
            $errors = array();
            
            // Handle image upload
            if(isset($_FILES['gambar_produk']) && $_FILES['gambar_produk']['error'] == 0) {
                $target_dir = "uploads/"; // Directory where images will be stored
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                $gambar_produk = basename($_FILES["gambar_produk"]["name"]);
                $target_file = $target_dir . $gambar_produk;
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                
                // Check if image file is a actual image or fake image
                $check = getimagesize($_FILES["gambar_produk"]["tmp_name"]);
                if($check !== false) {
                    // Allow certain file formats
                    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                        $errors[] = "Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.";
                    }
                    // Check file size (e.g., limit to 5MB)
                    if ($_FILES["gambar_produk"]["size"] > 5000000) {
                        $errors[] = "Maaf, ukuran file gambar terlalu besar. Maksimal 5MB.";
                    }
                    // Check if file already exists
                    if (file_exists($target_file)) {
                        // Option 1: Rename the file to avoid conflict (recommended)
                        $gambar_produk = time() . '_' . $gambar_produk;
                        $target_file = $target_dir . $gambar_produk;
                    }
                } else {
                    $errors[] = "File bukan gambar.";
                }
            }

            if(empty($nama_produk)) {
                $errors[] = "Nama produk tidak boleh kosong";
            }
            
            if(empty($jenis_kopi)) {
                $errors[] = "Jenis kopi harus dipilih";
            }
            
            if(empty($harga) || $harga <= 0) {
                $errors[] = "Harga harus diisi dan lebih dari 0";
            }
            
            if(empty($stok) || $stok < 0) {
                $errors[] = "Stok harus diisi dan tidak boleh negatif";
            }
            
            if(empty($errors)) {
                // Only move uploaded file if it exists and no errors
                if (!empty($_FILES['gambar_produk']['name']) && $_FILES['gambar_produk']['error'] == 0) {
                    if (!move_uploaded_file($_FILES["gambar_produk"]["tmp_name"], $target_file)) {
                        $errors[] = "Terjadi kesalahan saat mengunggah gambar.";
                    }
                }

                if (empty($errors)) { // Check errors again after file upload attempt
                    $result = mysqli_query($conn, "INSERT INTO produk(nama_produk, jenis_kopi, harga, stok, deskripsi, tanggal_masuk, gambar_produk) 
                                                   VALUES('$nama_produk', '$jenis_kopi', $harga, $stok, '$deskripsi', '$tanggal_masuk', '$gambar_produk')");
                    
                    if($result) {
                        echo "<div style='padding: 10px; background-color: #d4edda; color: #155724; border-radius: 5px; margin-bottom: 15px;'>";
                        echo "Produk berhasil ditambahkan. <a href='produk.php'>Lihat Daftar Produk</a>"; // Assuming produk.php shows all products
                        echo "</div>";
                    } else {
                        echo "<div style='padding: 10px; background-color: #f8d7da; color: #721c24; border-radius: 5px; margin-bottom: 15px;'>";
                        echo "Error: " . mysqli_error($conn);
                        echo "</div>";
                    }
                }
            }
            
            if (!empty($errors)) {
                echo "<div style='padding: 10px; background-color: #f8d7da; color: #721c24; border-radius: 5px; margin-bottom: 15px;'>";
                echo "<ul>";
                foreach($errors as $error) {
                    echo "<li>$error</li>";
                }
                echo "</ul>";
                echo "</div>";
            }
        }
        ?>
        
        <form action="tambah_produk.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nama_produk">Nama Produk</label>
                <input type="text" name="nama_produk" id="nama_produk" required>
            </div>
            
            <div class="form-group">
                <label for="jenis_kopi">Jenis Kopi</label>
                <select name="jenis_kopi" id="jenis_kopi" required>
                    <option value="">Pilih Jenis Kopi</option>
                    <option value="Classic Coffee">Classic Coffee</option>
                    <option value="Filter Coffee">Filter Coffee</option>
                    <option value="Speciality">Speciality</option>
                    <option value="Drip Bag">Drip Bag</option>
                    <option value="Coffee Gems">Coffee Gems</option>
                    <option value="Merchandise">Merchandise</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="harga">Harga (Rp)</label>
                <input type="number" name="harga" id="harga" min="0" step="1000" required>
            </div>
            
            <div class="form-group">
                <label for="stok">Stok</label>
                <input type="number" name="stok" id="stok" min="0" required>
            </div>
            
            <div class="form-group">
                <label for="deskripsi">Deskripsi Produk</label>
                <textarea name="deskripsi" id="deskripsi"></textarea>
            </div>

            <div class="form-group">
                <label for="gambar_produk">Gambar Produk</label>
                <input type="file" name="gambar_produk" id="gambar_produk" accept="image/*">
            </div>
            
            <div style="margin-top: 20px;">
                <input type="submit" name="submit" value="Simpan" class="btn">
                <a href="produk.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>
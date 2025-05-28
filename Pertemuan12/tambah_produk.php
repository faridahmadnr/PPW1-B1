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
            $nama_produk = $_POST['nama_produk'];
            $jenis_kopi = $_POST['jenis_kopi'];
            $harga = $_POST['harga'];
            $stok = $_POST['stok'];
            $deskripsi = $_POST['deskripsi'];
            $tanggal_masuk = date('Y-m-d');
            
            $errors = array();
            
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
                $result = mysqli_query($conn, "INSERT INTO produk(nama_produk, jenis_kopi, harga, stok, deskripsi, tanggal_masuk) 
                                               VALUES('$nama_produk', '$jenis_kopi', $harga, $stok, '$deskripsi', '$tanggal_masuk')");
                
                if($result) {
                    echo "<div style='padding: 10px; background-color: #d4edda; color: #155724; border-radius: 5px; margin-bottom: 15px;'>";
                    echo "Produk berhasil ditambahkan. <a href='produk.php'>Lihat Daftar Produk</a>";
                    echo "</div>";
                } else {
                    echo "<div style='padding: 10px; background-color: #f8d7da; color: #721c24; border-radius: 5px; margin-bottom: 15px;'>";
                    echo "Error: " . mysqli_error($conn);
                    echo "</div>";
                }
            } else {
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
        
        <form action="tambah_produk.php" method="post">
            <div class="form-group">
                <label for="nama_produk">Nama Produk</label>
                <input type="text" name="nama_produk" id="nama_produk" required>
            </div>
            
            <div class="form-group">
                <label for="jenis_kopi">Jenis Kopi</label>
                <select name="jenis_kopi" id="jenis_kopi" required>
                    <option value="">Pilih Jenis Kopi</option>
                    <option value="Arabika">Arabika</option>
                    <option value="Robusta">Robusta</option>
                    <option value="Liberika">Liberika</option>
                    <option value="Excelsa">Excelsa</option>
                    <option value="Campuran">Campuran</option>
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
            
            <div style="margin-top: 20px;">
                <input type="submit" name="submit" value="Simpan" class="btn">
                <a href="produk.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>
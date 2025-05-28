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
        <h1><i class="fas fa-edit"></i> Edit Produk Kopi</h1>
        
        <?php
        include_once("config.php");
        
        if(!isset($_GET['id'])) {
            header("Location: produk.php");
            exit();
        }
        
        $id = $_GET['id'];
        
        if(isset($_POST['update'])) {
            $nama_produk = $_POST['nama_produk'];
            $jenis_kopi = $_POST['jenis_kopi'];
            $harga = $_POST['harga'];
            $stok = $_POST['stok'];
            $deskripsi = $_POST['deskripsi'];
            
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
                $result = mysqli_query($conn, "UPDATE produk SET 
                                             nama_produk='$nama_produk', 
                                             jenis_kopi='$jenis_kopi', 
                                             harga=$harga, 
                                             stok=$stok, 
                                             deskripsi='$deskripsi' 
                                             WHERE id=$id");
                
                if($result) {
                    echo "<div style='padding: 10px; background-color: #d4edda; color: #155724; border-radius: 5px; margin-bottom: 15px;'>";
                    echo "Produk berhasil diperbarui. <a href='produk.php'>Lihat Daftar Produk</a>";
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
        
        $result = mysqli_query($conn, "SELECT * FROM produk WHERE id=$id");
        
        if(mysqli_num_rows($result) == 0) {
            header("Location: produk.php");
            exit();
        }
        
        $row = mysqli_fetch_assoc($result);
        $nama_produk = $row['nama_produk'];
        $jenis_kopi = $row['jenis_kopi'];
        $harga = $row['harga'];
        $stok = $row['stok'];
        $deskripsi = $row['deskripsi'];
        ?>
        
        <form action="edit_produk.php?id=<?php echo $id; ?>" method="post">
            <div class="form-group">
                <label for="nama_produk">Nama Produk</label>
                <input type="text" name="nama_produk" id="nama_produk" value="<?php echo $nama_produk; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="jenis_kopi">Jenis Kopi</label>
                <select name="jenis_kopi" id="jenis_kopi" required>
                    <option value="Arabika" <?php echo ($jenis_kopi == 'Arabika') ? 'selected' : ''; ?>>Arabika</option>
                    <option value="Robusta" <?php echo ($jenis_kopi == 'Robusta') ? 'selected' : ''; ?>>Robusta</option>
                    <option value="Liberika" <?php echo ($jenis_kopi == 'Liberika') ? 'selected' : ''; ?>>Liberika</option>
                    <option value="Excelsa" <?php echo ($jenis_kopi == 'Excelsa') ? 'selected' : ''; ?>>Excelsa</option>
                    <option value="Campuran" <?php echo ($jenis_kopi == 'Campuran') ? 'selected' : ''; ?>>Campuran</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="harga">Harga (Rp)</label>
                <input type="number" name="harga" id="harga" min="0" step="1000" value="<?php echo $harga; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="stok">Stok</label>
                <input type="number" name="stok" id="stok" min="0" value="<?php echo $stok; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="deskripsi">Deskripsi Produk</label>
                <textarea name="deskripsi" id="deskripsi"><?php echo $deskripsi; ?></textarea>
            </div>
            
            <div style="margin-top: 20px
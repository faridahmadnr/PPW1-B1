<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk Kopi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
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
        .header-action {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #6F4E37;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
        .btn {
            display: inline-block;
            padding: 8px 12px;
            background-color: #6F4E37;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #5a3d2a;
        }
        .btn-edit {
            background-color: #2196F3;
        }
        .btn-edit:hover {
            background-color: #0b7dda;
        }
        .btn-delete {
            background-color: #f44336;
        }
        .btn-delete:hover {
            background-color: #da190b;
        }
        .search-form {
            display: flex;
            gap: 10px;
        }
        .search-form input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .search-form button {
            padding: 8px 15px;
            background-color: #6F4E37;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .stok-warning {
            color: red;
            font-weight: bold;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1> Kelola Produk Kopi</h1>
        <!-- <i class="fas fa-coffee"></i> -->
        <div class="header-action">
            <a href="tambah_produk.php" class="btn"><i class="fas fa-plus"></i> Tambah Produk</a>
            <!-- <form class="search-form" method="GET" action="">
                <input type="text" name="search" placeholder="Cari produk...">
                <button type="submit"><i class="fas fa-search"></i> Cari</button>
            </form> -->
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Jenis Kopi</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Tanggal Masuk</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include_once("config.php");
                
                $search = isset($_GET['search']) ? $_GET['search'] : '';
                $query = "SELECT * FROM produk";
                
                if (!empty($search)) {
                    $query .= " WHERE nama_produk LIKE '%$search%' OR jenis_kopi LIKE '%$search%'";
                }
                
                $query .= " ORDER BY tanggal_masuk DESC";
                
                $result = mysqli_query($conn, $query);
                
                if (mysqli_num_rows($result) > 0) {
                    $no = 1;
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>".$no++."</td>";
                        echo "<td>".$row['nama_produk']."</td>";
                        echo "<td>".$row['jenis_kopi']."</td>";
                        echo "<td>Rp ".number_format($row['harga'], 0, ',', '.')."</td>";
                        
                        if ($row['stok'] < 5) {
                            echo "<td class='stok-warning'>".$row['stok']." <i class='fas fa-exclamation-triangle'></i></td>";
                        } else {
                            echo "<td>".$row['stok']."</td>";
                        }
                        
                        echo "<td>".date('d/m/Y', strtotime($row['tanggal_masuk']))."</td>";
                        echo "<td>";
                        // echo "<a href='detail_produk.php?id=".$row['id']."' class='btn' title='Detail'><i class='fas fa-eye'></i></a> ";
                        echo "<a href='edit_produk.php?id=".$row['id']."' class='btn btn-edit' title='Edit'><i class='fas fa-edit'></i></a> ";
                        echo "<a href='hapus_produk.php?id=".$row['id']."' class='btn btn-delete' title='Hapus' onclick='return confirm(\"Yakin ingin menghapus produk ini?\")'><i class='fas fa-trash'></i></a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' style='text-align:center'>Tidak ada data produk</td></tr>";
                }
                
                mysqli_close($conn);
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
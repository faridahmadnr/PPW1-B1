<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Penjualan Kopi</title>
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
        .menu {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .menu-item {
            background-color: #6F4E37;
            color: white;
            padding: 15px 25px;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            margin: 10px;
            transition: background-color 0.3s;
            flex: 1;
            min-width: 200px;
        }
        .menu-item:hover {
            background-color: #5a3d2a;
        }
        .menu-item i {
            font-size: 24px;
            display: block;
            margin-bottom: 10px;
        }
        .dashboard-stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .stat-card {
            background-color: white;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
            margin: 10px;
            flex: 1;
            min-width: 200px;
            border-top: 4px solid #6F4E37;
        }
        .stat-card h3 {
            margin-top: 0;
            color: #6F4E37;
        }
        .stat-card .number {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        .recent-activity {
            margin-top: 30px;
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
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-coffee"></i> Manajemen Penjualan Kopi</h1>
        
        <div class="dashboard-stats">
            <div class="stat-card">
                <h3>Total Produk</h3>
                <div class="number">
                    <?php
                    include_once("config.php");
                    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM produk");
                    $row = mysqli_fetch_assoc($result);
                    echo $row['total'];
                    ?>
                </div>
            </div>
            <div class="stat-card">
                <h3>Total Pelanggan</h3>
                <div class="number">
                    <?php
                    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM pelanggan");
                    $row = mysqli_fetch_assoc($result);
                    echo $row['total'];
                    ?>
                </div>
            </div>
            <div class="stat-card">
                <h3>Total Transaksi</h3>
                <div class="number">
                    <?php
                    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi");
                    $row = mysqli_fetch_assoc($result);
                    echo $row['total'];
                    ?>
                </div>
            </div>
            <div class="stat-card">
                <h3>Pendapatan</h3>
                <div class="number">
                    <?php
                    $result = mysqli_query($conn, "SELECT SUM(total_harga) as total FROM transaksi WHERE status_pembayaran = 'Lunas'");
                    $row = mysqli_fetch_assoc($result);
                    echo "Rp " . number_format($row['total'], 0, ',', '.');
                    ?>
                </div>
            </div>
        </div>
        
        <div class="menu">
            <a href="produk.php" class="menu-item">
                <i class="fas fa-coffee"></i>
                Kelola Produk
            </a>
            <a href="pelanggan.php" class="menu-item">
                <i class="fas fa-users"></i>
                Kelola Pelanggan
            </a>
            <a href="transaksi.php" class="menu-item">
                <i class="fas fa-shopping-cart"></i>
                Kelola Transaksi
            </a>
            <a href="laporan.php" class="menu-item">
                <i class="fas fa-chart-bar"></i>
                Laporan Penjualan
            </a>
        </div>
        
        <div class="recent-activity">
            <h2><i class="fas fa-history"></i> Produk dengan Stok Sedikit</h2>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Jenis Kopi</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = mysqli_query($conn, "SELECT * FROM produk WHERE stok < 10 ORDER BY stok ASC LIMIT 5");
                    
                    if (mysqli_num_rows($result) > 0) {
                        $no = 1;
                        while($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>".$no++."</td>";
                            echo "<td>".$row['nama_produk']."</td>";
                            echo "<td>".$row['jenis_kopi']."</td>";
                            echo "<td>Rp ".number_format($row['harga'], 0, ',', '.')."</td>";
                            echo "<td style='color: ".($row['stok'] < 5 ? 'red' : 'orange')."'>".$row['stok']."</td>";
                            echo "<td>";
                            echo "<a href='edit_produk.php?id=".$row['id']."' class='btn btn-edit'>Edit</a> ";
                            echo "<a href='restok_produk.php?id=".$row['id']."' class='btn'>Restok</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' style='text-align:center'>Semua stok produk mencukupi</td></tr>";
                    }
                    
                    mysqli_close($conn);
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Produk Kopi - Djaya Roasters</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .header {
            background-color: #fff;
            padding: 15px 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header .logo {
            font-weight: bold;
            color: #6F4E37;
            font-size: 24px;
            text-decoration: none;
        }
        .header .nav-menu a {
            margin-left: 20px;
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }
        .header .icons a {
            margin-left: 20px;
            color: #333;
            font-size: 18px;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .product-categories {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .product-categories a {
            padding: 10px 20px;
            margin: 5px;
            text-decoration: none;
            color: #6F4E37;
            border: 1px solid #6F4E37;
            border-radius: 20px;
            transition: background-color 0.3s, color 0.3s;
        }
        .product-categories a:hover,
        .product-categories a.active {
            background-color: #6F4E37;
            color: white;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }
        .product-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            text-align: center;
            padding: 15px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .product-card img {
            max-width: 100%;
            height: 200px; /* Fixed height for consistency */
            object-fit: contain; /* Ensures the image fits without cropping */
            margin-bottom: 10px;
            background-color: #FFDDC1; /* Background color similar to the design */
            padding: 10px; /* Padding similar to the design */
            border-radius: 4px;
        }
        .product-card .weight { /* This rule is present but not used in the HTML for 'weight' display */
            background-color: rgba(0,0,0,0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.8em;
            position: absolute; /* This implies parent needs 'position: relative;' */
            top: 10px;
            left: 10px;
        }
        .product-card h3 {
            margin: 10px 0;
            color: #333;
            font-size: 1.2em;
        }
        .product-card .price {
            font-size: 1.1em;
            font-weight: bold;
            color: #6F4E37;
            margin-bottom: 10px;
        }
        .product-card .buy-button {
            display: inline-block;
            padding: 8px 15px;
            background-color: #6F4E37;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .product-card .buy-button:hover {
            background-color: #5a3d2a;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="header">
        <a href="index.php" class="logo">DJAYA ROASTERS</a>
        <div class="nav-menu">
            <a href="#">HOME</a>
            <a href="#">ABOUT</a>
            <a href="produk_display.php">PRODUCT</a>
            <a href="#">GALLERY</a>
        </div>
        <div class="icons">
            <a href="#"><i class="fas fa-search"></i></a>
            <a href="#"><i class="fas fa-user"></i></a>
            <a href="#"><i class="fas fa-shopping-cart"></i></a>
        </div>
    </div>

    <div class="container">
        <div class="product-categories">
            <?php
            // Define your categories. The KEY should be the value stored in your 'jenis_kopi' column in the DB.
            // The VALUE is the display text for the button.
            $categories = [
                'All' => 'SEMUA PRODUK',
                'Classic Coffee' => 'CLASSIC COFFEE',
                'Filter Coffee' => 'FILTER COFFEE',
                'Speciality' => 'SPECIALITY',
                'Drip Bag' => 'DRIP BAG',
                'Coffee Gems' => 'COFFEE GEMS',
                'Merchandise' => 'MERCHANDISE'
            ];

            // Get the selected category from the URL, default to 'All'
            $selected_category = isset($_GET['jenis']) ? $_GET['jenis'] : 'All';

            foreach ($categories as $value_in_db => $label_for_display) {
                $active_class = ($selected_category == $value_in_db) ? 'active' : '';
                // Encode the URL parameter value to handle spaces safely
                echo "<a href='produk_display.php?jenis=" . urlencode($value_in_db) . "' class='" . $active_class . "'>" . $label_for_display . "</a>";
            }
            ?>
        </div>

        <div class="product-grid">
            <?php
            include_once("config.php"); // Pastikan file config.php berisi koneksi database Anda

            $query = "SELECT * FROM produk";
            // Jika kategori yang dipilih BUKAN 'All', tambahkan klausa WHERE
            if ($selected_category != 'All') {
                // Gunakan nilai $selected_category secara langsung di klausa WHERE
                // Pastikan nilai ini persis sama dengan yang disimpan di database
                $query .= " WHERE jenis_kopi = '" . mysqli_real_escape_string($conn, $selected_category) . "'";
            }
            
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='product-card'>";
                    if (!empty($row['gambar_produk'])) {
                        echo "<img src='uploads/".$row['gambar_produk']."' alt='".$row['nama_produk']."'>";
                    } else {
                        // Pastikan Anda memiliki gambar placeholder.png di folder yang sama
                        echo "<img src='placeholder.png' alt='No Image Available'>"; 
                    }
                    echo "<h3>".$row['nama_produk']."</h3>";
                    echo "<div class='price'>Rp ".number_format($row['harga'], 0, ',', '.')." / KG</div>";
                    echo "<a href='#' class='buy-button'>Add to Cart</a>"; 
                    echo "</div>";
                }
            } else {
                echo "<p style='text-align:center; grid-column: 1 / -1;'>Tidak ada produk yang tersedia untuk kategori ini.</p>";
            }

            mysqli_close($conn);
            ?>
        </div>
    </div>
</body>
</html>
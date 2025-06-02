<?php
include_once("config.php");

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Fetch the image filename before deleting the product
    $result_image = mysqli_query($conn, "SELECT gambar_produk FROM produk WHERE id = $id");
    $row_image = mysqli_fetch_assoc($result_image);
    $gambar_to_delete = $row_image['gambar_produk'];

    // Cek apakah produk ada dalam transaksi
    $check = mysqli_query($conn, "SELECT COUNT(*) as total FROM detail_transaksi WHERE id_produk = $id");
    $row = mysqli_fetch_assoc($check);
    
    if($row['total'] > 0) {
        // Jika produk ada dalam transaksi, jangan hapus tapi beri pesan error
        header("Location: produk.php?error=Produk tidak dapat dihapus karena sudah tercatat dalam transaksi");
    } else {
        // Jika produk tidak ada dalam transaksi, hapus data
        $result = mysqli_query($conn, "DELETE FROM produk WHERE id=$id");
        
        if ($result) {
            // Delete the image file from the server
            if (!empty($gambar_to_delete) && file_exists("uploads/" . $gambar_to_delete)) {
                unlink("uploads/" . $gambar_to_delete);
            }
            header("Location: produk.php?success=Produk berhasil dihapus");
        } else {
            header("Location: produk.php?error=Gagal menghapus produk");
        }
    }
} else {
    header("Location: produk.php");
}
?>
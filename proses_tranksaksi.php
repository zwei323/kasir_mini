<?php
session_start();
include "koneksi.php";

// 🔐 Proteksi
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] != 'kasir') {
    header("Location: admin.php");
    exit();
}

// Fungsi untuk menghitung total setelah diskon
function hitungDiskon($harga, $diskon) {
    $potongan = $harga * ($diskon / 100);
    return $harga - $potongan;
}

// Fungsi untuk menghitung kembalian
function hitungKembalian($bayar, $total) {
    return $bayar - $total;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['harga'])) {
    
    // Ambil dan validasi input
    $harga = isset($_POST['harga']) ? (float) $_POST['harga'] : 0;
    $diskon = isset($_POST['diskon']) ? (float) $_POST['diskon'] : 0;
    $bayar = isset($_POST['bayar']) ? (float) $_POST['bayar'] : 0;
    
    // Validasi harga
    if ($harga <= 0) {
        $_SESSION['error_message'] = "Harga barang harus lebih dari 0!";
        header("Location: kasir.php");
        exit();
    }
    
    // Validasi diskon
    if ($diskon < 0 || $diskon > 100) {
        $_SESSION['error_message'] = "Diskon harus antara 0-100%!";
        header("Location: kasir.php");
        exit();
    }
    
    // Validasi bayar
    if ($bayar <= 0) {
        $_SESSION['error_message'] = "Uang bayar harus lebih dari 0!";
        header("Location: kasir.php");
        exit();
    }
    
    // Hitung total dan kembalian
    $total = hitungDiskon($harga, $diskon);
    $kembalian = hitungKembalian($bayar, $total);
    
    // Cek apakah pembayaran cukup
    if ($bayar < $total) {
        $_SESSION['error_message'] = "Pembayaran kurang! Total yang harus dibayar: Rp " . number_format($total, 0, ',', '.');
        header("Location: kasir.php");
        exit();
    }
    
    // Gunakan mysqli_real_escape_string untuk keamanan SQL Injection
    $harga_escape = mysqli_real_escape_string($conn, $harga);
    $diskon_escape = mysqli_real_escape_string($conn, $diskon);
    $total_escape = mysqli_real_escape_string($conn, $total);
    $bayar_escape = mysqli_real_escape_string($conn, $bayar);
    $kembalian_escape = mysqli_real_escape_string($conn, $kembalian);
    
    // Dapatkan ID user dari session (asumsi ada username di session)
    $username = $_SESSION['username'];
    $query_user = "SELECT id_user FROM user WHERE username = '$username'";
    $result_user = mysqli_query($conn, $query_user);
    
    if ($result_user && mysqli_num_rows($result_user) > 0) {
        $user_data = mysqli_fetch_assoc($result_user);
        $id_user = $user_data['id_user'];
    } else {
        $id_user = 1; // Default jika tidak ditemukan
    }
    
    // Buat nomor invoice/struk
    $invoice = "INV-" . date("YmdHis") . "-" . rand(100, 999);
    $invoice_escape = mysqli_real_escape_string($conn, $invoice);
    
    // Query INSERT dengan id_transaksi AUTO_INCREMENT (kosongkan atau NULL)
    $query = "INSERT INTO transaksi (id_transaksi, invoice, tanggal, harga, diskon, total, bayar, kembalian, id_user) 
              VALUES (NULL, '$invoice_escape', NOW(), '$harga_escape', '$diskon_escape', '$total_escape', '$bayar_escape', '$kembalian_escape', '$id_user')";
    
    if (mysqli_query($conn, $query)) {
        // Simpan data untuk struk
        $_SESSION['transaksi_sukses'] = [
            'invoice' => $invoice,
            'harga' => $harga,
            'diskon' => $diskon,
            'potongan' => $harga * ($diskon / 100),
            'total' => $total,
            'bayar' => $bayar,
            'kembalian' => $kembalian,
            'tanggal' => date('d/m/Y H:i:s'),
            'kasir' => $_SESSION['username']
        ];
        
        // Redirect ke halaman struk
        header("Location: struk.php");
        exit();
    } else {
        // Jika error saat insert
        $_SESSION['error_message'] = "Error: " . mysqli_error($conn);
        header("Location: kasir.php");
        exit();
    }
    
} else {
    // Jika bukan method POST atau tidak ada data
    header("Location: kasir.php");
    exit();
}
?>
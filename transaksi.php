<?php
session_start();
include "koneksi.php";

// 🔐 Proteksi
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

// Cek apakah ada data transaksi
if (!isset($_SESSION['transaksi_sukses'])) {
    header("Location: kasir.php");
    exit();
}

$transaksi = $_SESSION['transaksi_sukses'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace;
            background: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .struk {
            background: white;
            width: 350px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .header {
            text-align: center;
            border-bottom: 1px dashed #999;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        
        .header h2 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 11px;
            color: #666;
        }
        
        .info {
            border-bottom: 1px dashed #999;
            padding-bottom: 10px;
            margin-bottom: 10px;
            font-size: 12px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        
        .items {
            border-bottom: 1px dashed #999;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        
        .item-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 12px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-weight: bold;
            font-size: 14px;
        }
        
        .footer {
            text-align: center;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px dashed #999;
            font-size: 11px;
            color: #666;
        }
        
        .buttons {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }
        
        .btn {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-family: inherit;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary {
            background: #185FA5;
            color: white;
        }
        
        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }
        
        .btn-print {
            background: #10b981;
            color: white;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .buttons {
                display: none;
            }
            .struk {
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="struk">
        <div class="header">
            <h2>KASIR APP</h2>
            <p>Jl. Contoh No. 123, Kota</p>
            <p>Telp: (021) 1234567</p>
        </div>
        
        <div class="info">
            <div class="info-row">
                <span>Invoice:</span>
                <strong><?php echo $transaksi['invoice']; ?></strong>
            </div>
            <div class="info-row">
                <span>Tanggal:</span>
                <span><?php echo $transaksi['tanggal']; ?></span>
            </div>
            <div class="info-row">
                <span>Kasir:</span>
                <span><?php echo htmlspecialchars($transaksi['kasir']); ?></span>
            </div>
        </div>
        
        <div class="items">
            <div class="item-row">
                <span>Item</span>
                <span>Total</span>
            </div>
            <div class="item-row">
                <span>Barang</span>
                <span>Rp <?php echo number_format($transaksi['harga'], 0, ',', '.'); ?></span>
            </div>
        </div>
        
        <div class="items">
            <div class="total-row">
                <span>Harga:</span>
                <span>Rp <?php echo number_format($transaksi['harga'], 0, ',', '.'); ?></span>
            </div>
            <div class="total-row">
                <span>Diskon (<?php echo $transaksi['diskon']; ?>%):</span>
                <span>- Rp <?php echo number_format($transaksi['potongan'], 0, ',', '.'); ?></span>
            </div>
            <div class="total-row" style="border-top: 1px solid #ddd; padding-top: 8px; margin-top: 5px;">
                <span>TOTAL:</span>
                <span>Rp <?php echo number_format($transaksi['total'], 0, ',', '.'); ?></span>
            </div>
            <div class="total-row">
                <span>Bayar:</span>
                <span>Rp <?php echo number_format($transaksi['bayar'], 0, ',', '.'); ?></span>
            </div>
            <div class="total-row">
                <span>Kembalian:</span>
                <span>Rp <?php echo number_format($transaksi['kembalian'], 0, ',', '.'); ?></span>
            </div>
        </div>
        
        <div class="footer">
            <p>Terima kasih telah berbelanja!</p>
            <p>Barang yang sudah dibeli tidak dapat dikembalikan</p>
        </div>
        
        <div class="buttons">
            <button onclick="window.print()" class="btn btn-print">
                <i class="ti ti-printer"></i> Cetak
            </button>
            <a href="kasir.php" class="btn btn-primary">
                <i class="ti ti-receipt"></i> Transaksi Baru
            </a>
        </div>
    </div>
</body>
</html>

<?php
// Hapus session transaksi setelah ditampilkan
unset($_SESSION['transaksi_sukses']);
?>
<?php
session_start();
include "koneksi.php";

// 🔐 Proteksi
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] != 'kasir') {
    header("Location: admin.php"); // ✅ arahkan ke admin
    exit();
}


// =======================
// TAMBAH BARANG
// =======================
if (isset($_POST['tambah_barang'])) {
    $nama  = mysqli_real_escape_string($conn, $_POST['nama']);
    $harga = (int) $_POST['harga_barang'];
    $stok  = (int) $_POST['stok'];

    if ($nama != "" && $harga > 0 && $stok >= 0) {
        mysqli_query($conn, "INSERT INTO barang (nama, harga, stok) 
                             VALUES ('$nama','$harga','$stok')");
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Kasir</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css"/>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f6f9;
      color: #1a1a2e;
      min-height: 100vh;
      display: flex;
    }

    /* ===== SIDEBAR ===== */
    .sidebar {
      width: 200px;
      min-height: 100vh;
      background: #ffffff;
      border-right: 1px solid #e5e7eb;
      padding: 1.5rem 1rem;
      display: flex;
      flex-direction: column;
      gap: 1rem;
      position: fixed;
      top: 0; left: 0; bottom: 0;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 10px;
      padding-bottom: 1.25rem;
      border-bottom: 1px solid #e5e7eb;
    }

    .logo-icon {
      width: 34px; height: 34px;
      border-radius: 8px;
      background: #185FA5;
      display: flex; align-items: center; justify-content: center;
    }
    .logo-icon i { color: #fff; font-size: 17px; }
    .logo-text { font-size: 14px; font-weight: 600; color: #1a1a2e; }

    .nav-label {
      font-size: 10px;
      font-weight: 600;
      color: #9ca3af;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      padding: 0 0.5rem;
    }

    .nav-item {
      display: flex; align-items: center; gap: 10px;
      padding: 8px 12px;
      border-radius: 8px;
      font-size: 13px;
      color: #6b7280;
      text-decoration: none;
      transition: background 0.15s, color 0.15s;
    }
    .nav-item:hover { background: #f3f4f6; color: #1a1a2e; }
    .nav-item.active {
      background: #eff6ff;
      color: #1d4ed8;
      font-weight: 500;
      border: 1px solid #dbeafe;
    }
    .nav-item i { font-size: 16px; }

    .spacer { flex: 1; }

    .logout-btn {
      display: flex; align-items: center; gap: 10px;
      padding: 8px 12px;
      border-radius: 8px;
      font-size: 13px;
      color: #ef4444;
      text-decoration: none;
      transition: background 0.15s;
    }
    .logout-btn:hover { background: #fef2f2; }
    .logout-btn i { font-size: 16px; }

    /* ===== MAIN ===== */
    .main {
      margin-left: 200px;
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .topbar {
      display: flex; align-items: center; justify-content: space-between;
      padding: 0.9rem 1.5rem;
      background: #ffffff;
      border-bottom: 1px solid #e5e7eb;
      position: sticky; top: 0; z-index: 10;
    }
    .topbar-title { font-size: 15px; font-weight: 600; color: #1a1a2e; }
    .cashier-badge { display: flex; align-items: center; gap: 8px; }
    .avatar {
      width: 30px; height: 30px; border-radius: 50%;
      background: #dbeafe;
      display: flex; align-items: center; justify-content: center;
      font-size: 11px; font-weight: 600; color: #1d4ed8;
    }
    .cashier-name { font-size: 13px; color: #6b7280; }

    /* ===== CONTENT ===== */
    .content {
      padding: 1.5rem;
      display: grid;
      grid-template-columns: 1fr 340px;
      gap: 1.25rem;
      align-items: start;
    }

    .card {
      background: #ffffff;
      border: 1px solid #e5e7eb;
      border-radius: 12px;
      overflow: hidden;
    }

    .card-header {
      display: flex; align-items: center; gap: 10px;
      padding: 1rem 1.25rem;
      border-bottom: 1px solid #e5e7eb;
    }
    .card-icon {
      width: 32px; height: 32px; border-radius: 8px;
      background: #eff6ff;
      display: flex; align-items: center; justify-content: center;
    }
    .card-icon i { font-size: 16px; color: #1d4ed8; }
    .card-title { font-size: 14px; font-weight: 600; color: #1a1a2e; }
    .card-subtitle { font-size: 12px; color: #9ca3af; }

    .card-body { padding: 1.25rem; display: flex; flex-direction: column; gap: 1rem; }

    /* ===== FORM ===== */
    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-label {
      font-size: 12px; font-weight: 600; color: #374151;
      text-transform: uppercase; letter-spacing: 0.05em;
    }

    .input-wrap {
      display: flex; align-items: center;
      border: 1px solid #d1d5db;
      border-radius: 8px;
      overflow: hidden;
      transition: border-color 0.15s;
      background: #fff;
    }
    .input-wrap:focus-within { border-color: #185FA5; box-shadow: 0 0 0 3px #dbeafe; }

    .input-prefix {
      padding: 0 12px;
      font-size: 13px; font-weight: 500;
      color: #6b7280;
      background: #f9fafb;
      border-right: 1px solid #e5e7eb;
      height: 40px;
      display: flex; align-items: center;
    }

    .input-wrap input {
      border: none; outline: none;
      padding: 0 12px;
      height: 40px;
      font-size: 14px;
      color: #1a1a2e;
      width: 100%;
      background: transparent;
    }

    .input-suffix {
      padding: 0 12px;
      font-size: 13px; font-weight: 500;
      color: #6b7280;
      background: #f9fafb;
      border-left: 1px solid #e5e7eb;
      height: 40px;
      display: flex; align-items: center;
    }

    .input-hint { font-size: 11px; color: #9ca3af; }

    .divider { border: none; border-top: 1px solid #f3f4f6; margin: 0.25rem 0; }

    .btn-submit {
      width: 100%;
      padding: 11px;
      border: none;
      border-radius: 8px;
      background: #185FA5;
      color: #fff;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      transition: background 0.15s, transform 0.1s;
    }
    .btn-submit:hover { background: #0c447c; }
    .btn-submit:active { transform: scale(0.98); }
    .btn-submit i { font-size: 16px; }

    .btn-reset {
      width: 100%;
      padding: 9px;
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      background: #fff;
      color: #6b7280;
      font-size: 13px;
      cursor: pointer;
      display: flex; align-items: center; justify-content: center; gap: 6px;
      transition: background 0.15s;
    }
    .btn-reset:hover { background: #f9fafb; color: #1a1a2e; }
    .btn-reset i { font-size: 15px; }

    /* ===== RINGKASAN ===== */
    .summary-row {
      display: flex; justify-content: space-between; align-items: center;
      padding: 10px 0;
      border-bottom: 1px dashed #f3f4f6;
    }
    .summary-row:last-of-type { border-bottom: none; }
    .summary-label { font-size: 13px; color: #6b7280; display: flex; align-items: center; gap: 6px; }
    .summary-label i { font-size: 14px; }
    .summary-value { font-size: 14px; font-weight: 500; color: #1a1a2e; }
    .summary-value.discount { color: #d97706; }
    .summary-value.total { font-size: 17px; font-weight: 700; color: #185FA5; }
    .summary-value.kembalian { font-size: 16px; font-weight: 700; color: #15803d; }
    .summary-value.kurang { font-size: 14px; font-weight: 600; color: #dc2626; }

    .divider-total {
      border: none;
      border-top: 2px solid #e5e7eb;
      margin: 4px 0;
    }

    .empty-state {
      text-align: center;
      padding: 2rem 1rem;
    }
    .empty-state i { font-size: 32px; color: #d1d5db; margin-bottom: 8px; }
    .empty-state p { font-size: 13px; color: #9ca3af; }

    .status-badge {
      display: inline-flex; align-items: center; gap: 4px;
      padding: 3px 10px;
      border-radius: 99px;
      font-size: 11px; font-weight: 600;
    }
    .status-success { background: #dcfce7; color: #15803d; }
    .status-danger  { background: #fee2e2; color: #b91c1c; }
    .status-warning { background: #fef9c3; color: #a16207; }
  </style>
</head>
<body>

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="logo">
      <div class="logo-icon"><i class="ti ti-cash-register"></i></div>
      <span class="logo-text">Kasir App</span>
    </div>

    <div style="display:flex;flex-direction:column;gap:4px">
      <p class="nav-label">Menu</p>
      <a href="#" class="nav-item active"><i class="ti ti-receipt"></i>Transaksi</a>
      <a href="#" class="nav-item"><i class="ti ti-history"></i>Riwayat</a>
      <a href="barang.php" class="nav-item"><i class="ti ti-package"></i>Barang</a>
      <a href="#" class="nav-item"><i class="ti ti-chart-bar"></i>Laporan</a>
    </div>
    

    <div class="spacer"></div>
    <a href="logout.php" class="logout-btn"><i class="ti ti-logout"></i>Logout</a>
  </aside>

  <!-- MAIN -->
  <div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
      <span class="topbar-title">Kasir</span>
      <div class="cashier-badge">
        <div class="avatar">KS</div>
        <span class="cashier-name">Kasir</span>
      </div>
    </div>

    <!-- CONTENT -->
    <div class="content">

     <!-- FORM TRANSAKSI -->
<div class="card">
    <div class="card-header">
        <div class="card-icon"><i class="ti ti-calculator"></i></div>
        <div>
            <p class="card-title">Form Transaksi</p>
            <p class="card-subtitle">Masukkan data pembayaran</p>
        </div>
    </div>
    <div class="card-body">
        
        <!-- Tempat pesan error -->
        <div id="errorMessage" style="display:none;" class="alert alert-error">
            <div style="display: flex; align-items: center; gap: 8px; color: #b91c1c;">
                <i class="ti ti-alert-circle"></i>
                <span id="errorText" style="font-size: 13px;"></span>
            </div>
        </div>
        
        <form method="POST" action="proses_transaksi.php" id="kasirForm" onsubmit="return validateForm()">
            <!-- Harga -->
            <div class="form-group">
                <label class="form-label" for="harga">
                    <i class="ti ti-tag" style="vertical-align:-2px;margin-right:4px"></i>Harga Barang
                </label>
                <div class="input-wrap">
                    <span class="input-prefix">Rp</span>
                    <input type="number" id="harga" name="harga" placeholder="0" min="0" step="1000" oninput="hitungLive()" required/>
                </div>
                <span class="input-hint">Masukkan harga jual barang</span>
            </div>

            <!-- Diskon -->
            <div class="form-group">
                <label class="form-label" for="diskon">
                    <i class="ti ti-discount" style="vertical-align:-2px;margin-right:4px"></i>Diskon
                </label>
                <div class="input-wrap">
                    <input type="number" id="diskon" name="diskon" placeholder="0" min="0" max="100" step="1" oninput="hitungLive()" style="text-align:right"/>
                    <span class="input-suffix">%</span>
                </div>
                <span class="input-hint">Isi 0 jika tidak ada diskon (maks. 100%)</span>
            </div>

            <hr class="divider"/>

            <!-- Bayar -->
            <div class="form-group">
                <label class="form-label" for="bayar">
                    <i class="ti ti-wallet" style="vertical-align:-2px;margin-right:4px"></i>Uang Bayar
                </label>
                <div class="input-wrap">
                    <span class="input-prefix">Rp</span>
                    <input type="number" id="bayar" name="bayar" placeholder="0" min="0" step="1000" oninput="hitungLive()" required/>
                </div>
                <span class="input-hint">Jumlah uang yang diterima dari pelanggan</span>
            </div>

            <hr class="divider"/>

            <!-- Harga total setelah diskon (hidden) -->
            <input type="hidden" id="total_hidden" name="total" value="0"/>

            <!-- Tombol -->
            <button type="submit" class="btn-submit" id="btnSubmit">
                <i class="ti ti-circle-check"></i> Proses Transaksi
            </button>
            <button type="button" class="btn-reset" onclick="resetForm()">
                <i class="ti ti-refresh"></i> Reset Form
            </button>
        </form>
    </div>
</div>

<!-- RINGKASAN -->
<div style="display:flex;flex-direction:column;gap:1rem;">
    <div class="card">
        <div class="card-header">
            <div class="card-icon"><i class="ti ti-file-invoice"></i></div>
            <div>
                <p class="card-title">Ringkasan Pembayaran</p>
                <p class="card-subtitle">Kalkulasi otomatis</p>
            </div>
        </div>
        <div class="card-body" id="summaryBody">
            <div class="empty-state">
                <i class="ti ti-receipt-off"></i>
                <p>Isi form untuk melihat<br>ringkasan transaksi</p>
            </div>
        </div>
    </div>

    <!-- Info -->
    <div class="card">
        <div class="card-header">
            <div class="card-icon"><i class="ti ti-info-circle"></i></div>
            <div>
                <p class="card-title">Panduan</p>
            </div>
        </div>
        <div class="card-body" style="gap:8px">
            <div style="display:flex;align-items:flex-start;gap:8px;font-size:13px;color:#6b7280;">
                <i class="ti ti-circle-1" style="font-size:16px;color:#185FA5;flex-shrink:0;margin-top:1px"></i>
                Masukkan harga barang
            </div>
            <div style="display:flex;align-items:flex-start;gap:8px;font-size:13px;color:#6b7280;">
                <i class="ti ti-circle-2" style="font-size:16px;color:#185FA5;flex-shrink:0;margin-top:1px"></i>
                Isi diskon jika ada (opsional)
            </div>
            <div style="display:flex;align-items:flex-start;gap:8px;font-size:13px;color:#6b7280;">
                <i class="ti ti-circle-3" style="font-size:16px;color:#185FA5;flex-shrink:0;margin-top:1px"></i>
                Masukkan jumlah uang bayar
            </div>
            <!-- Tombol submit di sini sudah dihapus karena dobel, cukup di form saja -->
        </div>
    </div>
</div>


  
  <script>
    function formatRupiah(angka) {
      return 'Rp ' + Math.round(angka).toLocaleString('id-ID');
    }

    function hitungLive() {
      const harga  = parseFloat(document.getElementById('harga').value)  || 0;
      const diskon = parseFloat(document.getElementById('diskon').value) || 0;
      const bayar  = parseFloat(document.getElementById('bayar').value)  || 0;

      if (harga === 0 && bayar === 0) {
        document.getElementById('summaryBody').innerHTML = `
          <div class="empty-state">
            <i class="ti ti-receipt-off"></i>
            <p>Isi form untuk melihat<br>ringkasan transaksi</p>
          </div>`;
        return;
      }

      const potongan   = harga * (diskon / 100);
      const totalBayar = harga - potongan;
      const kembalian  = bayar - totalBayar;
      const kurang     = totalBayar - bayar;

      let statusHTML = '';
      if (bayar > 0 && kembalian >= 0) {
        statusHTML = `<span class="status-badge status-success"><i class="ti ti-check" style="font-size:11px"></i>Pembayaran Cukup</span>`;
      } else if (bayar > 0 && kurang > 0) {
        statusHTML = `<span class="status-badge status-danger"><i class="ti ti-x" style="font-size:11px"></i>Kurang Bayar</span>`;
      }

      document.getElementById('summaryBody').innerHTML = `
        <div class="summary-row">
          <span class="summary-label"><i class="ti ti-tag"></i>Harga</span>
          <span class="summary-value">${formatRupiah(harga)}</span>
        </div>
        <div class="summary-row">
          <span class="summary-label"><i class="ti ti-discount"></i>Diskon (${diskon}%)</span>
          <span class="summary-value discount">- ${formatRupiah(potongan)}</span>
        </div>
        <hr class="divider-total"/>
        <div class="summary-row">
          <span class="summary-label" style="font-weight:600;color:#1a1a2e"><i class="ti ti-receipt"></i>Total Bayar</span>
          <span class="summary-value total">${formatRupiah(totalBayar)}</span>
        </div>
        <div class="summary-row">
          <span class="summary-label"><i class="ti ti-wallet"></i>Uang Bayar</span>
          <span class="summary-value">${formatRupiah(bayar)}</span>
        </div>
        <hr class="divider-total"/>
        ${kembalian >= 0 && bayar > 0 ? `
        <div class="summary-row">
          <span class="summary-label" style="font-weight:600;color:#15803d"><i class="ti ti-coins"></i>Kembalian</span>
          <span class="summary-value kembalian">${formatRupiah(kembalian)}</span>
        </div>` : ''}
        ${kurang > 0 && bayar > 0 ? `
        <div class="summary-row">
          <span class="summary-label" style="font-weight:600;color:#dc2626"><i class="ti ti-alert-triangle"></i>Kurang</span>
          <span class="summary-value kurang">- ${formatRupiah(kurang)}</span>
        </div>` : ''}
        ${statusHTML ? `<div style="margin-top:4px">${statusHTML}</div>` : ''}
      `;
    }

    function resetForm() {
      document.getElementById('kasirForm').reset();
      document.getElementById('summaryBody').innerHTML = `
        <div class="empty-state">
          <i class="ti ti-receipt-off"></i>
          <p>Isi form untuk melihat<br>ringkasan transaksi</p>
        </div>`;
    }
  </script>

</body>
</html>

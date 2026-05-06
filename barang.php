<?php
$conn = mysqli_connect("localhost", "root", "zwei1", "kasir_mini");

// CEK KONEKSI (WAJIB)
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// TAMBAH BARANG
if (isset($_POST['tambah_barang'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $harga = (int) $_POST['harga_barang'];
    $stok = (int) $_POST['stok'];

    $query = "INSERT INTO barang (nama_barang, harga, stok) 
              VALUES ('$nama', '$harga', '$stok')";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Barang berhasil ditambahkan!');
                window.location='?page=barang';
              </script>";
    } else {
        echo "<script>alert('Gagal menambah barang!');</script>";
    }
}

$page = isset($_GET['page']) ? $_GET['page'] : 'barang';
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kasir App</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

<style>
body {
  font-family: 'Segoe UI', sans-serif;
  background: #f4f6f9;
  margin: 0;
  padding: 20px;
}

/* CARD */
.card {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.08);
  overflow: hidden;
}

.card-header {
  display: flex;
  align-items: center;
  gap: 15px;
  padding: 15px 20px;
  background: #4f46e5;
  color: white;
}

.card-icon {
  font-size: 28px;
}

.card-title {
  font-size: 18px;
  font-weight: bold;
  margin: 0;
}

.card-subtitle {
  font-size: 13px;
  opacity: 0.8;
  margin: 0;
}

.card-body {
  padding: 20px;
}

/* FORM */
.form-group {
  margin-bottom: 15px;
}

.form-label {
  display: block;
  margin-bottom: 5px;
  font-weight: 600;
}

.input-wrap {
  display: flex;
  align-items: center;
}

.input-wrap input {
  width: 100%;
  padding: 10px;
  border-radius: 8px;
  border: 1px solid #ddd;
  outline: none;
}

.input-wrap input:focus {
  border-color: #4f46e5;
}

.input-prefix {
  background: #eee;
  padding: 10px;
  border-radius: 8px 0 0 8px;
  border: 1px solid #ddd;
  border-right: none;
}

/* BUTTON */
.btn-submit {
  background: #4f46e5;
  color: white;
  padding: 10px 15px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
}

.btn-submit:hover {
  background: #4338ca;
}

/* TABLE */
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

th {
  background: #4f46e5;
  color: white;
}

th, td {
  padding: 10px;
  text-align: center;
  border-bottom: 1px solid #eee;
}

tr:hover {
  background: #f9fafb;
}
</style>

</head>
<body>

<div class="content">

<?php if ($page == 'barang') { ?>

<div class="card">

  <div class="card-header">
    <div class="card-icon"><i class="ti ti-package"></i></div>
    <div>
      <p class="card-title">Data Barang</p>
      <p class="card-subtitle">Tambah barang baru</p>
    </div>
  </div>

  <div class="card-body">

    <!-- FORM -->
    <form method="POST">
      <div class="form-group">
        <label class="form-label">Nama Barang</label>
        <div class="input-wrap">
          <input type="text" name="nama" required>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Harga</label>
        <div class="input-wrap">
          <span class="input-prefix">Rp</span>
          <input type="number" name="harga_barang" required>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Stok</label>
        <div class="input-wrap">
          <input type="number" name="stok" required>
        </div>
      </div>

      <button type="submit" name="tambah_barang" class="btn-submit">
        <i class="ti ti-plus"></i> Tambah Barang
      </button>
    </form>

    <hr>

    <!-- TABLE -->
    <table>
      <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Harga</th>
        <th>Stok</th>
      </tr>

      <?php
      $no = 1;
      $data = mysqli_query($conn, "SELECT * FROM barang");

      if ($data && mysqli_num_rows($data) > 0) {
          while ($d = mysqli_fetch_assoc($data)) {
              echo "<tr>
                      <td>".$no++."</td>
                      <td>".$d['nama_barang']."</td>
                      <td>Rp ".number_format($d['harga'])."</td>
                      <td>".$d['stok']."</td>
                    </tr>";
          }
      } else {
          echo "<tr><td colspan='4'>Data kosong</td></tr>";
      }
      ?>
    </table>

  </div>
</div>

<?php } ?>

</div>

</body>
</html>
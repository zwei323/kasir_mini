<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Halaman Admin</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css"/>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f6f9;
      color: #1a1a2e;
      display: flex;
      min-height: 100vh;
    }

    /* ===== SIDEBAR ===== */
    .sidebar {
      width: 220px;
      min-height: 100vh;
      background: #ffffff;
      border-right: 1px solid #e5e7eb;
      padding: 1.5rem 1rem;
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
      position: fixed;
      top: 0; left: 0; bottom: 0;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 10px;
      padding-bottom: 1.5rem;
      border-bottom: 1px solid #e5e7eb;
    }

    .logo-icon {
      width: 36px; height: 36px;
      border-radius: 8px;
      background: #185FA5;
      display: flex; align-items: center; justify-content: center;
    }

    .logo-icon i { color: #fff; font-size: 18px; }

    .logo-text { font-size: 15px; font-weight: 600; color: #1a1a2e; }

    .nav-label {
      font-size: 11px;
      font-weight: 600;
      color: #9ca3af;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      padding: 0 0.5rem;
      margin-bottom: 4px;
    }

    .nav-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 9px 12px;
      border-radius: 8px;
      font-size: 14px;
      color: #6b7280;
      cursor: pointer;
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

    .nav-item i { font-size: 17px; }

    .spacer { flex: 1; }

    .logout-btn {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 9px 12px;
      border-radius: 8px;
      font-size: 14px;
      color: #ef4444;
      cursor: pointer;
      text-decoration: none;
      transition: background 0.15s;
    }

    .logout-btn:hover { background: #fef2f2; }
    .logout-btn i { font-size: 17px; }

    /* ===== MAIN ===== */
    .main {
      margin-left: 220px;
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .topbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 1rem 1.5rem;
      background: #ffffff;
      border-bottom: 1px solid #e5e7eb;
      position: sticky;
      top: 0;
      z-index: 10;
    }

    .topbar-title { font-size: 16px; font-weight: 600; color: #1a1a2e; }

    .admin-badge { display: flex; align-items: center; gap: 8px; }

    .avatar {
      width: 32px; height: 32px;
      border-radius: 50%;
      background: #dbeafe;
      display: flex; align-items: center; justify-content: center;
      font-size: 12px; font-weight: 600; color: #1d4ed8;
    }

    .admin-name { font-size: 13px; color: #6b7280; }

    /* ===== CONTENT ===== */
    .content { padding: 1.5rem; display: flex; flex-direction: column; gap: 1.5rem; }

    .section-title { font-size: 13px; font-weight: 500; color: #6b7280; margin-bottom: 0.5rem; }

    .cards-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 12px;
    }

    .stat-card {
      background: #ffffff;
      border: 1px solid #e5e7eb;
      border-radius: 10px;
      padding: 1rem;
    }

    .stat-label { font-size: 12px; color: #6b7280; margin-bottom: 6px; }
    .stat-value { font-size: 24px; font-weight: 600; color: #1a1a2e; }
    .stat-sub { font-size: 12px; color: #9ca3af; margin-top: 3px; }

    /* ===== TABLE CARD ===== */
    .table-card {
      background: #ffffff;
      border: 1px solid #e5e7eb;
      border-radius: 12px;
      overflow: hidden;
    }

    .table-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 1rem 1.25rem;
      border-bottom: 1px solid #e5e7eb;
    }

    .table-title { font-size: 14px; font-weight: 600; color: #1a1a2e; }

    .btn-add {
      display: flex;
      align-items: center;
      gap: 6px;
      padding: 6px 14px;
      border-radius: 8px;
      background: #185FA5;
      border: none;
      font-size: 13px;
      color: #ffffff;
      cursor: pointer;
      transition: background 0.15s;
    }

    .btn-add:hover { background: #0c447c; }
    .btn-add i { font-size: 14px; }

    table { width: 100%; border-collapse: collapse; font-size: 13px; }

    th {
      text-align: left;
      padding: 10px 1.25rem;
      font-size: 11px;
      font-weight: 600;
      color: #9ca3af;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      border-bottom: 1px solid #e5e7eb;
      background: #f9fafb;
    }

    td {
      padding: 12px 1.25rem;
      color: #1a1a2e;
      border-bottom: 1px solid #f3f4f6;
    }

    tr:last-child td { border-bottom: none; }
    tr:hover td { background: #f9fafb; }

    .badge {
      display: inline-block;
      padding: 3px 10px;
      border-radius: 99px;
      font-size: 11px;
      font-weight: 500;
    }

    .badge-success { background: #dcfce7; color: #15803d; }
    .badge-warning { background: #fef9c3; color: #a16207; }
    .badge-danger  { background: #fee2e2; color: #b91c1c; }

    .icon-btn {
      background: none;
      border: none;
      cursor: pointer;
      color: #9ca3af;
      padding: 5px;
      border-radius: 6px;
      transition: background 0.15s, color 0.15s;
    }

    .icon-btn:hover { color: #1a1a2e; background: #f3f4f6; }
    .icon-btn i { font-size: 15px; }
  </style>
</head>
<body>

  <!-- ===== SIDEBAR ===== -->
  <aside class="sidebar">
    <div class="logo">
      <div class="logo-icon"><i class="ti ti-shield-check"></i></div>
      <span class="logo-text">Admin Panel</span>
    </div>

    <div style="display:flex;flex-direction:column;gap:4px">
      <p class="nav-label">Menu</p>
      <a href="#" class="nav-item active"><i class="ti ti-layout-dashboard"></i>Dashboard</a>
      <a href="barang.php" class="nav-item"><i class="ti ti-package"></i>Kelola Barang</a>
      <a href="#" class="nav-item"><i class="ti ti-users"></i>Pengguna</a>
      <a href="#" class="nav-item"><i class="ti ti-chart-bar"></i>Laporan</a>
      <a href="#" class="nav-item"><i class="ti ti-settings"></i>Pengaturan</a>
    </div>

    <div class="spacer"></div>
    <a href="logout.php" class="logout-btn"><i class="ti ti-logout"></i>Logout</a>
  </aside>

  <!-- ===== MAIN ===== -->
  <div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
      <span class="topbar-title">Halaman Admin</span>
      <div class="admin-badge">
        <div class="avatar">AD</div>
        <span class="admin-name">Administrator</span>
      </div>
    </div>

    <!-- CONTENT -->
    <div class="content">

      <!-- STATISTIK -->
      <div>
        <p class="section-title">Ringkasan Data</p>
        <div class="cards-grid">
          <div class="stat-card">
            <p class="stat-label">Total Barang</p>
            <p class="stat-value">128</p>
            <p class="stat-sub">+4 bulan ini</p>
          </div>
          <div class="stat-card">
            <p class="stat-label">Stok Menipis</p>
            <p class="stat-value">7</p>
            <p class="stat-sub">Perlu restock</p>
          </div>
          <div class="stat-card">
            <p class="stat-label">Kategori</p>
            <p class="stat-value">12</p>
            <p class="stat-sub">Aktif</p>
          </div>
          <div class="stat-card">
            <p class="stat-label">Pengguna</p>
            <p class="stat-value">34</p>
            <p class="stat-sub">Terdaftar</p>
          </div>
        </div>
      </div>

      <!-- TABEL BARANG -->
      <div class="table-card">
        <div class="table-header">
          <span class="table-title">Daftar Barang</span>
          <button class="btn-add" onclick="window.location.href='barang.php'">
            <i class="ti ti-plus"></i> Tambah Barang
          </button>
        </div>
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Nama Barang</th>
              <th>Kategori</th>
              <th>Stok</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>001</td>
              <td>Laptop ASUS X415</td>
              <td>Elektronik</td>
              <td>24</td>
              <td><span class="badge badge-success">Tersedia</span></td>
              <td>
                <button class="icon-btn" title="Edit"><i class="ti ti-edit"></i></button>
                <button class="icon-btn" title="Hapus"><i class="ti ti-trash"></i></button>
              </td>
            </tr>
            <tr>
              <td>002</td>
              <td>Mouse Wireless</td>
              <td>Aksesori</td>
              <td>3</td>
              <td><span class="badge badge-warning">Menipis</span></td>
              <td>
                <button class="icon-btn" title="Edit"><i class="ti ti-edit"></i></button>
                <button class="icon-btn" title="Hapus"><i class="ti ti-trash"></i></button>
              </td>
            </tr>
            <tr>
              <td>003</td>
              <td>Keyboard Mekanikal</td>
              <td>Aksesori</td>
              <td>15</td>
              <td><span class="badge badge-success">Tersedia</span></td>
              <td>
                <button class="icon-btn" title="Edit"><i class="ti ti-edit"></i></button>
                <button class="icon-btn" title="Hapus"><i class="ti ti-trash"></i></button>
              </td>
            </tr>
            <tr>
              <td>004</td>
              <td>Monitor LED 24"</td>
              <td>Elektronik</td>
              <td>0</td>
              <td><span class="badge badge-danger">Habis</span></td>
              <td>
                <button class="icon-btn" title="Edit"><i class="ti ti-edit"></i></button>
                <button class="icon-btn" title="Hapus"><i class="ti ti-trash"></i></button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

    </div><!-- end .content -->
  </div><!-- end .main -->

</body>
</html>

@php
  $logo = url('templates/dist/img/'.rawurlencode('k3.jpeg'));
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Safety Patrol System</title>
  <link rel="stylesheet" href="{{ asset('templates/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('templates/plugins/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('templates/dist/css/adminlte.min.css') }}">
  <style>
    body {
      background: linear-gradient(135deg, #0b4d75 0%, #063245 60%, #041e29 100%);
      min-height: 100vh;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      font-size: 14px;
    }
    
    .navbar-custom {
      background: rgba(11, 77, 117, 0.95);
      backdrop-filter: blur(10px);
      box-shadow: 0 2px 15px rgba(0,0,0,0.2);
      padding: 8px 0;
    }
    
    .navbar-brand {
      font-size: 1.1rem;
    }
    
    .navbar-brand img {
      height: 32px;
      background: #fff;
      border-radius: 6px;
      padding: 4px;
      transition: transform 0.3s ease;
    }
    
    .navbar-brand img:hover {
      transform: scale(1.05);
    }
    
    .btn-outline-light {
      font-size: 0.875rem;
      padding: 6px 16px;
    }
    
    .hero {
      padding: 35px 0 25px;
      text-align: center;
      color: #fff;
      animation: fadeIn 1s ease-in;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .hero-logo {
      max-height: 70px;
      background: #fff;
      border-radius: 12px;
      padding: 10px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.3);
      animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-8px); }
    }
    
    .hero h1 {
      font-size: 1.75rem;
      font-weight: 700;
      margin-top: 15px;
      margin-bottom: 8px;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    
    .hero p {
      font-size: 0.95rem;
      margin-bottom: 15px;
      opacity: 0.9;
    }
    
    .btn-login-hero {
      background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
      border: none;
      padding: 10px 35px;
      font-size: 0.95rem;
      font-weight: 600;
      border-radius: 25px;
      color: #fff;
      box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
      transition: all 0.3s ease;
    }
    
    .btn-login-hero:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(40, 167, 69, 0.6);
      background: linear-gradient(135deg, #20c997 0%, #28a745 100%);
    }
    
    .stats-container {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 8px 30px rgba(0,0,0,0.2);
      margin-top: 25px;
      animation: slideUp 1s ease-in;
    }
    
    @keyframes slideUp {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .stats-container h4 {
      color: #0b4d75;
      font-weight: 700;
      margin-bottom: 18px;
      font-size: 1.3rem;
    }
    
    .stat-card {
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
      border-radius: 10px;
      padding: 18px;
      margin-bottom: 15px;
      border-left: 4px solid #0b4d75;
      transition: all 0.3s ease;
    }
    
    .stat-card:hover {
      transform: translateX(5px);
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .stat-card h3 {
      font-size: 1.8rem;
      font-weight: 700;
      color: #0b4d75;
      margin: 0 0 5px 0;
    }
    
    .stat-card p {
      margin: 0;
      color: #6c757d;
      font-size: 0.875rem;
      font-weight: 500;
    }
    
    .table-custom {
      border-radius: 8px;
      overflow: hidden;
      font-size: 0.875rem;
    }
    
    .table-custom thead {
      background: linear-gradient(135deg, #0b4d75 0%, #063245 100%);
      color: #fff;
    }
    
    .table-custom th {
      border: none;
      padding: 10px 12px;
      font-weight: 600;
      font-size: 0.875rem;
    }
    
    .table-custom td {
      padding: 8px 12px;
      vertical-align: middle;
    }
    
    .badge-status {
      padding: 5px 12px;
      border-radius: 15px;
      font-weight: 600;
      font-size: 0.8rem;
    }
    
    footer {
      background: rgba(0,0,0,0.2);
      color: #fff;
      padding: 15px 0;
      text-align: center;
      margin-top: 30px;
      font-size: 0.875rem;
    }
    
    .container {
      max-width: 1140px;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="{{ $logo }}" alt="Logo">
        <span class="ml-2 font-weight-bold">Safety Patrol System</span>
      </a>
      <div class="ml-auto">
        <a href="{{ route('login') }}" class="btn btn-outline-light">
          <i class="fas fa-sign-in-alt mr-1"></i>Login
        </a>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <div class="container hero" style="margin-top: 65px;">
    <img src="{{ $logo }}" alt="Logo" class="hero-logo">
    <h1>Safety Patrol System</h1>
    <p>Sistem Manajemen Temuan K3 Terintegrasi</p>
    <a href="{{ route('login') }}" class="btn btn-login-hero">
      <i class="fas fa-sign-in-alt mr-2"></i>Masuk ke Sistem
    </a>
  </div>

  <!-- Statistics Section -->
  <div class="container">
    <div class="row mb-3">
      <div class="col-md-3 col-sm-6 mb-2">
        <div class="stat-card text-center">
          <h3>{{ $totalFindings ?? 0 }}</h3>
          <p><i class="fas fa-clipboard-list mr-1"></i>Total Temuan</p>
        </div>
      </div>
      <div class="col-md-3 col-sm-6 mb-2">
        <div class="stat-card text-center">
          <h3 class="text-warning">{{ $inProgress ?? 0 }}</h3>
          <p><i class="fas fa-hourglass-half mr-1"></i>Dalam Progress</p>
        </div>
      </div>
      <div class="col-md-3 col-sm-6 mb-2">
        <div class="stat-card text-center">
          <h3 class="text-success">{{ $completed ?? 0 }}</h3>
          <p><i class="fas fa-check-circle mr-1"></i>Selesai</p>
        </div>
      </div>
      <div class="col-md-3 col-sm-6 mb-2">
        <div class="stat-card text-center">
          <h3 class="text-info">{{ $closed ?? 0 }}</h3>
          <p><i class="fas fa-archive mr-1"></i>Closed</p>
        </div>
      </div>
    </div>

    <!-- Table Section -->
    <div class="stats-container">
      <h4 class="text-center">
        <i class="fas fa-chart-bar mr-2"></i>Statistik Temuan per Departemen
      </h4>
      <div class="table-responsive">
        <table class="table table-custom table-hover mb-0">
          <thead>
            <tr>
              <th style="width:40px;">No</th>
              <th>Departemen</th>
              <th class="text-center" style="width:80px;">Masuk</th>
              <th class="text-center" style="width:90px;">Progress</th>
              <th class="text-center" style="width:80px;">Selesai</th>
              <th class="text-center" style="width:80px;">Closed</th>
            </tr>
          </thead>
          <tbody>
            @forelse(($rows ?? []) as $i => $r)
              <tr>
                <td class="text-center">{{ $i+1 }}</td>
                <td><strong>{{ $r->dept_name }}</strong></td>
                <td class="text-center">
                  <span class="badge badge-status bg-primary">{{ $r->masuk }}</span>
                </td>
                <td class="text-center">
                  <span class="badge badge-status bg-warning">{{ $r->progress }}</span>
                </td>
                <td class="text-center">
                  <span class="badge badge-status bg-success">{{ $r->selesai }}</span>
                </td>
                <td class="text-center">
                  <span class="badge badge-status bg-info">{{ $r->close }}</span>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-3">
                  <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                  Belum ada data temuan
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    <div class="container">
      <p class="mb-0">&copy; {{ date('Y') }} Safety Patrol System. All rights reserved.</p>
    </div>
  </footer>

  <script src="{{ asset('templates/plugins/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('templates/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('templates/dist/js/adminlte.min.js') }}"></script>
</body>
</html>

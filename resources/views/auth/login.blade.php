@php
  $logo = url('templates/dist/img/'.rawurlencode('k3.jpeg'));
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Safety Patrol</title>
  
  <link rel="stylesheet" href="{{ asset('templates/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('templates/plugins/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('templates/dist/css/adminlte.min.css') }}">
  
  <style>
    body {
      background: linear-gradient(135deg, #0b4d75 0%, #063245 60%, #041e29 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      font-size: 14px;
      padding-top: 80px;
      padding-bottom: 30px;
    }
    
    .navbar-custom {
      background: rgba(11, 77, 117, 0.95);
      backdrop-filter: blur(10px);
      box-shadow: 0 2px 15px rgba(0,0,0,0.2);
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 1000;
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
    
    .login-container {
      margin-top: 0;
      animation: slideUp 0.8s ease-out;
      width: 100%;
    }
    
    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .login-box {
      width: 100%;
      max-width: 400px;
      margin: 0 auto;
    }
    
    .login-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 15px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.3);
      border: none;
      overflow: hidden;
    }
    
    .login-header {
      background: linear-gradient(135deg, #0b4d75 0%, #063245 100%);
      padding: 18px 20px;
      text-align: center;
      color: #fff;
    }
    
    .login-header img {
      height: 45px;
      background: #fff;
      border-radius: 10px;
      padding: 6px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
      animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-5px); }
    }
    
    .login-header h3 {
      margin-top: 10px;
      margin-bottom: 2px;
      font-weight: 700;
      font-size: 1.15rem;
    }
    
    .login-header p {
      margin: 0;
      opacity: 0.9;
      font-size: 0.8rem;
    }
    
    .login-body {
      padding: 25px 20px;
    }
    
    .form-group label {
      color: #495057;
      font-weight: 600;
      margin-bottom: 6px;
      font-size: 0.875rem;
    }
    
    .input-group {
      margin-bottom: 15px;
    }
    
    .form-control {
      border: 2px solid #e9ecef;
      border-radius: 8px;
      padding: 9px 12px;
      font-size: 0.875rem;
      transition: all 0.3s ease;
    }
    
    .form-control:focus {
      border-color: #0b4d75;
      box-shadow: 0 0 0 0.15rem rgba(11, 77, 117, 0.15);
    }
    
    .input-group-text {
      background: #f8f9fa;
      border: 2px solid #e9ecef;
      border-left: none;
      border-radius: 0 8px 8px 0;
      color: #6c757d;
      padding: 9px 12px;
    }
    
    .input-group .form-control {
      border-right: none;
      border-radius: 8px 0 0 8px;
    }
    
    .btn-login {
      background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
      border: none;
      padding: 10px 25px;
      font-size: 0.95rem;
      font-weight: 600;
      border-radius: 8px;
      color: #fff;
      width: 100%;
      box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
      transition: all 0.3s ease;
    }
    
    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(40, 167, 69, 0.5);
      background: linear-gradient(135deg, #20c997 0%, #28a745 100%);
    }
    
    .btn-back {
      background: transparent;
      border: 2px solid #0b4d75;
      color: #0b4d75;
      padding: 10px 25px;
      font-size: 0.95rem;
      font-weight: 600;
      border-radius: 8px;
      width: 100%;
      transition: all 0.3s ease;
    }
    
    .btn-back:hover {
      background: #0b4d75;
      color: #fff;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(11, 77, 117, 0.3);
    }
    
    .alert-custom {
      border-radius: 8px;
      border: none;
      padding: 12px 15px;
      margin-bottom: 15px;
      font-size: 0.875rem;
    }
    
    .alert-success {
      background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
      color: #155724;
    }
    
    .alert-danger {
      background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
      color: #721c24;
    }
    
    .icheck-primary input:checked + label::before {
      background-color: #0b4d75;
      border-color: #0b4d75;
    }
    
    .remember-forgot {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 18px;
    }
    
    .icheck-primary {
      margin: 0;
    }
    
    .icheck-primary label {
      font-weight: 500;
      color: #6c757d;
      font-size: 0.875rem;
    }
    
    .footer-text {
      text-align: center;
      margin-top: 20px;
    }
    
    .footer-text p {
      color: #fff;
      margin: 0;
      font-size: 0.8rem;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="{{ route('welcome') }}">
        <img src="{{ $logo }}" alt="Logo">
        <span class="ml-2 font-weight-bold">Safety Patrol System</span>
      </a>
    </div>
  </nav>

  <!-- Login Container -->
  <div class="login-container">
    <div class="login-box">
      <div class="login-card">
        <!-- Header -->
        <div class="login-header">
          <img src="{{ $logo }}" alt="Logo">
          <h3>Safety Patrol</h3>
          <p>Silakan login untuk melanjutkan</p>
        </div>

        <!-- Body -->
        <div class="login-body">
          <!-- Alert Success -->
          @if(session('success'))
            <div class="alert alert-success alert-custom alert-dismissible">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
          @endif

          <!-- Alert Error -->
          @if($errors->any())
            <div class="alert alert-danger alert-custom alert-dismissible">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <i class="fas fa-exclamation-circle mr-2"></i>
              @foreach($errors->all() as $error)
                {{ $error }}
              @endforeach
            </div>
          @endif

          <!-- Form Login -->
          <form action="{{ route('login.post') }}" method="post">
            @csrf
            
            <div class="form-group">
              <label for="name">
                <i class="fas fa-user mr-1"></i>Nama
              </label>
              <div class="input-group">
                <input type="text" 
                       name="name" 
                       id="name"
                       class="form-control" 
                       placeholder="Masukkan nama Anda" 
                       value="{{ old('name') }}" 
                       required 
                       autofocus>
                <div class="input-group-append">
                  <div class="input-group-text">
                    <i class="fas fa-user"></i>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="password">
                <i class="fas fa-lock mr-1"></i>Password
              </label>
              <div class="input-group">
                <input type="password" 
                       name="password" 
                       id="password"
                       class="form-control" 
                       placeholder="Masukkan password" 
                       required>
                <div class="input-group-append">
                  <div class="input-group-text">
                    <i class="fas fa-lock"></i>
                  </div>
                </div>
              </div>
            </div>

            <div class="remember-forgot">
              <div class="icheck-primary">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">
                  Ingat Saya
                </label>
              </div>
            </div>

            <button type="submit" class="btn btn-login">
              <i class="fas fa-sign-in-alt mr-2"></i>Login
            </button>

            <div class="mt-2">
              <a href="{{ route('welcome') }}" class="btn btn-back">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Beranda
              </a>
            </div>
          </form>
        </div>
      </div>

      <!-- Footer -->
      <div class="footer-text">
        <p>&copy; {{ date('Y') }} Safety Patrol System. All rights reserved.</p>
      </div>
    </div>
  </div>

  <script src="{{ asset('templates/plugins/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('templates/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('templates/dist/js/adminlte.min.js') }}"></script>
</body>
</html>

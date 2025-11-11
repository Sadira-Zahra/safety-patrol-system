@extends('layouts.main')

@section('header')
  <h1 class="m-0" style="font-size: 1.5rem;">
    Ganti Password
    <small class="text-muted" style="font-size: 0.875rem;">Ubah Password Akun Anda</small>
  </h1>
@endsection

@section('styles')
<style>
  .compact-container {
    max-width: 1200px;
    margin: 0 auto;
  }
  .compact-card .card-body {
    padding: 1rem;
  }
  .compact-card .card-header {
    padding: 0.75rem 1rem;
  }
  .compact-card .card-title {
    font-size: 1rem;
    margin-bottom: 0;
  }
  .compact-form .form-group {
    margin-bottom: 0.75rem;
  }
  .compact-form label {
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
  }
  .compact-form .form-control {
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
  }
  .compact-form .input-group-text {
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
  }
  .compact-form small {
    font-size: 0.75rem;
  }
  .compact-callout {
    padding: 0.75rem;
  }
  .compact-callout h5 {
    font-size: 0.95rem;
  }
  .compact-callout p {
    font-size: 0.8rem;
  }
</style>
@endsection

@section('content')
<div class="compact-container">
  <div class="row">
    <!-- Form Ganti Password (Kiri) -->
    <div class="col-lg-7">
      <div class="card card-primary card-outline compact-card">
        <div class="card-header">
          <h3 class="card-title">
            <i class="fas fa-key mr-2"></i>Form Ganti Password
          </h3>
        </div>
        
        <form action="{{ route('change-password.update') }}" method="POST" class="compact-form">
          @csrf
          <div class="card-body">
            @if(session('success'))
              <div class="alert alert-success alert-dismissible" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                <button type="button" class="close" data-dismiss="alert" style="padding: 0.5rem;">&times;</button>
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
              </div>
            @endif

            @if($errors->any())
              <div class="alert alert-danger alert-dismissible" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                <button type="button" class="close" data-dismiss="alert" style="padding: 0.5rem;">&times;</button>
                <i class="fas fa-exclamation-circle mr-2"></i>
                <ul class="mb-0" style="padding-left: 1.25rem;">
                  @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <!-- Info User -->
            <div class="callout callout-info compact-callout">
              <h5><i class="fas fa-user mr-2"></i>Informasi Akun</h5>
              <div class="row">
                <div class="col-sm-6">
                  <p class="mb-1"><strong>Nama:</strong> {{ Auth::user()->name }}</p>
                  <p class="mb-0"><strong>NIK:</strong> {{ Auth::user()->nik ?? '-' }}</p>
                </div>
                <div class="col-sm-6">
                  <p class="mb-1"><strong>Email:</strong> {{ Auth::user()->email }}</p>
                  <p class="mb-0"><strong>Role:</strong> {{ ucwords(str_replace('_', ' ', strtolower(Auth::user()->role))) }}</p>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="currentPassword">Password Lama <span class="text-danger">*</span></label>
              <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-lock"></i></span>
                </div>
                <input type="password" 
                       name="current_password" 
                       id="currentPassword" 
                       class="form-control @error('current_password') is-invalid @enderror" 
                       placeholder="Masukkan password lama"
                       required>
              </div>
              @error('current_password')
                <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>

            <div class="form-group">
              <label for="newPassword">Password Baru <span class="text-danger">*</span></label>
              <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-key"></i></span>
                </div>
                <input type="password" 
                       name="new_password" 
                       id="newPassword" 
                       class="form-control @error('new_password') is-invalid @enderror" 
                       placeholder="Masukkan password baru"
                       minlength="6"
                       required>
              </div>
              @error('new_password')
                <small class="text-danger">{{ $message }}</small>
              @else
                <small class="text-muted">Minimal 6 karakter</small>
              @enderror
            </div>

            <div class="form-group">
              <label for="confirmPassword">Konfirmasi Password Baru <span class="text-danger">*</span></label>
              <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-key"></i></span>
                </div>
                <input type="password" 
                       name="new_password_confirmation" 
                       id="confirmPassword" 
                       class="form-control" 
                       placeholder="Ulangi password baru"
                       minlength="6"
                       required>
              </div>
              <small class="text-muted" id="matchStatus"></small>
            </div>

            <div class="form-group mb-0">
              <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="showPassword">
                <label class="custom-control-label" for="showPassword" style="font-size: 0.875rem;">Tampilkan Password</label>
              </div>
            </div>
          </div>

          <div class="card-footer" style="padding: 0.75rem 1rem;">
            <button type="submit" class="btn btn-primary btn-sm">
              <i class="fas fa-save mr-1"></i>Simpan Password Baru
            </button>
            <button type="reset" class="btn btn-secondary btn-sm">
              <i class="fas fa-undo mr-1"></i>Reset
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Tips Keamanan & Password Strength (Kanan) -->
    <div class="col-lg-5">
      <!-- Tips Keamanan Password -->
      <div class="card card-warning card-outline compact-card">
        <div class="card-header">
          <h3 class="card-title">
            <i class="fas fa-shield-alt mr-2"></i>Tips Keamanan Password
          </h3>
        </div>
        <div class="card-body">
          <h6 class="text-bold" style="font-size: 0.9rem;">Password yang Kuat:</h6>
          <ul class="pl-3 mb-0" style="font-size: 0.8rem;">
            <li>Minimal 6 karakter (disarankan 8+ karakter)</li>
            <li>Kombinasi huruf besar dan kecil</li>
            <li>Mengandung angka (0-9)</li>
            <li>Mengandung simbol (!@#$%^&*)</li>
          </ul>
        </div>
      </div>

      <!-- Password Strength Indicator -->
      <div class="card card-secondary card-outline compact-card">
        <div class="card-header">
          <h3 class="card-title">
            <i class="fas fa-chart-line mr-2"></i>Kekuatan Password
          </h3>
        </div>
        <div class="card-body">
          <div id="passwordStrength" class="mb-2">
            <span class="badge badge-secondary" style="font-size: 0.75rem;">Belum diisi</span>
          </div>
          <div class="progress" style="height: 18px;">
            <div id="strengthBar" class="progress-bar" role="progressbar" style="width: 0%; font-size: 0.75rem;">0%</div>
          </div>
          <small class="text-muted mt-2 d-block" style="font-size: 0.7rem;">Password akan dinilai secara otomatis saat Anda mengetik</small>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
$(function() {
  // Show/Hide Password
  $('#showPassword').on('change', function() {
    const type = $(this).is(':checked') ? 'text' : 'password';
    $('#currentPassword, #newPassword, #confirmPassword').attr('type', type);
  });

  // Password Match Indicator
  $('#confirmPassword').on('keyup', function() {
    const newPass = $('#newPassword').val();
    const confirmPass = $(this).val();
    
    if (confirmPass.length > 0) {
      if (newPass === confirmPass) {
        $(this).removeClass('is-invalid').addClass('is-valid');
        $('#matchStatus').html('<i class="fas fa-check text-success"></i> Password cocok').removeClass('text-danger').addClass('text-success');
      } else {
        $(this).removeClass('is-valid').addClass('is-invalid');
        $('#matchStatus').html('<i class="fas fa-times text-danger"></i> Password tidak cocok').removeClass('text-success').addClass('text-danger');
      }
    } else {
      $(this).removeClass('is-valid is-invalid');
      $('#matchStatus').html('').removeClass('text-success text-danger');
    }
  });

  // Password Strength Meter
  $('#newPassword').on('keyup', function() {
    const password = $(this).val();
    const strength = calculatePasswordStrength(password);
    
    let strengthText = '';
    let strengthClass = '';
    let strengthColor = '';
    
    if (strength === 0) {
      strengthText = 'Belum diisi';
      strengthClass = 'badge-secondary';
      strengthColor = 'bg-secondary';
    } else if (strength < 30) {
      strengthText = 'Sangat Lemah';
      strengthClass = 'badge-danger';
      strengthColor = 'bg-danger';
    } else if (strength < 50) {
      strengthText = 'Lemah';
      strengthClass = 'badge-warning';
      strengthColor = 'bg-warning';
    } else if (strength < 70) {
      strengthText = 'Sedang';
      strengthClass = 'badge-info';
      strengthColor = 'bg-info';
    } else if (strength < 90) {
      strengthText = 'Kuat';
      strengthClass = 'badge-primary';
      strengthColor = 'bg-primary';
    } else {
      strengthText = 'Sangat Kuat';
      strengthClass = 'badge-success';
      strengthColor = 'bg-success';
    }
    
    $('#passwordStrength').html(`<span class="badge ${strengthClass}" style="font-size: 0.75rem;">${strengthText}</span>`);
    $('#strengthBar').css('width', strength + '%')
                     .removeClass('bg-secondary bg-danger bg-warning bg-info bg-primary bg-success')
                     .addClass(strengthColor)
                     .text(strength + '%');
  });

  function calculatePasswordStrength(password) {
    if (!password) return 0;
    
    let strength = 0;
    
    // Length
    if (password.length >= 6) strength += 20;
    if (password.length >= 8) strength += 10;
    if (password.length >= 12) strength += 10;
    
    // Lowercase
    if (/[a-z]/.test(password)) strength += 15;
    
    // Uppercase
    if (/[A-Z]/.test(password)) strength += 15;
    
    // Numbers
    if (/[0-9]/.test(password)) strength += 15;
    
    // Special characters
    if (/[^a-zA-Z0-9]/.test(password)) strength += 15;
    
    return Math.min(strength, 100);
  }
});
</script>
@endsection

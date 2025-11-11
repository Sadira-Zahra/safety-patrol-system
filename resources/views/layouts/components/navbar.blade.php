<nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background: linear-gradient(135deg, #0b4d75 0%, #063245 100%); color: #fff;">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button" style="color: #fff;">
        <i class="fas fa-bars"></i>
      </a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="{{ route('dashboard.index') }}" class="nav-link" style="color: #fff;">
        <i class="fas fa-home mr-1"></i>Home
      </a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- User Dropdown -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#" style="color: #fff;">
        <i class="far fa-user mr-1"></i>
        {{ Auth::user()->name }}
      </a>
      <div class="dropdown-menu dropdown-menu-right">
        <a href="{{ route('change-password.index') }}" class="dropdown-item">
          <i class="fas fa-key mr-2"></i>Ganti Password
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <i class="fas fa-sign-out-alt mr-2"></i>Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
      </div>
    </li>
  </ul>
</nav>

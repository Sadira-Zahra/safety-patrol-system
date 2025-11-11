@php
$user = Auth::user();

$menus = [
    (object)["title" => "Dashboard", "icon" => "fas fa-tachometer-alt", "path" => "dashboard", "roles" => ['ADMINISTRATOR', 'SAFETY_PATROLLER', 'SAFETY_ADMIN', 'DEPT_PIC', 'DEPT_MANAGER']],
    (object)[
        "title" => "Master User",
        "icon" => "fas fa-users-cog",
        "has_submenu" => true,
        "roles" => ['ADMINISTRATOR'],
        "submenu" => [
            (object)["title" => "Data Administrator", "path" => "master_user/administrator"],
            (object)["title" => "Data Safety Patroller", "path" => "master_user/safety_patroller"],
            (object)["title" => "Data Safety Admin", "path" => "master_user/safety_admin"],
            (object)["title" => "Data PIC Departemen", "path" => "master_user/pic_departemen"],
            (object)["title" => "Data Manager", "path" => "master_user/manager"],
        ],
    ],
    (object)[
        "title" => "Master Data",
        "icon" => "fas fa-database",
        "has_submenu" => true,
        "roles" => ['ADMINISTRATOR'],
        "submenu" => [
            (object)["title" => "Data Departemen", "path" => "master_master/departemen"],
            (object)["title" => "Data Kategori", "path" => "master_master/kategori"],
            (object)["title" => "Data Grade", "path" => "master_master/grade"],
        ],
    ],
    (object)["title" => "Temuan", "icon" => "fas fa-clipboard-list", "path" => "findings", "roles" => ['ADMINISTRATOR', 'SAFETY_PATROLLER', 'SAFETY_ADMIN', 'DEPT_PIC', 'DEPT_MANAGER']],
    (object)[
        "title" => "Laporan",
        "icon" => "fas fa-chart-bar",
        "has_submenu" => true,
        "roles" => ['ADMINISTRATOR', 'SAFETY_PATROLLER', 'SAFETY_ADMIN', 'DEPT_PIC', 'DEPT_MANAGER'],
        "submenu" => [
            (object)["title" => "Laporan Temuan", "path" => "laporan"],
        ],
    ],
    (object)["title" => "Ganti Password", "icon" => "fas fa-key", "path" => "change-password", "roles" => ['ADMINISTRATOR', 'SAFETY_PATROLLER', 'SAFETY_ADMIN', 'DEPT_PIC', 'DEPT_MANAGER']],
];

$currentPath = request()->path();

function isActiveMenu($path, $currentPath) {
    return str_starts_with($currentPath, $path);
}

function canAccessMenu($menu, $userRole) {
    if (!isset($menu->roles)) {
        return true;
    }
    return in_array($userRole, $menu->roles);
}
@endphp

<style>
  /* Sidebar Compact Style */
  .main-sidebar {
    font-size: 0.875rem;
  }
  
  .brand-link {
    padding: 0.65rem 0.5rem;
    font-size: 1rem;
  }
  
  .brand-link .brand-image {
    width: 28px;
    max-height: 28px;
  }
  
  .brand-link .brand-text {
    font-size: 1rem;
  }
  
  .user-panel {
    padding: 0.65rem 0.5rem;
    margin: 0.5rem 0;
  }
  
  .user-panel .image img {
    width: 2rem;
    height: 2rem;
  }
  
  .user-panel .info {
    padding-left: 0.5rem;
  }
  
  .user-panel .info a {
    font-size: 0.875rem;
    padding: 0;
  }
  
  .user-panel .info small {
    font-size: 0.7rem;
  }
  
  .sidebar .nav-sidebar {
    margin-top: 0;
  }
  
  .nav-sidebar .nav-link {
    padding: 0.4rem 0.5rem;
    font-size: 0.875rem;
  }
  
  .nav-sidebar .nav-icon {
    font-size: 0.875rem;
    margin-right: 0.5rem;
  }
  
  .nav-sidebar .nav-link p {
    font-size: 0.875rem;
    margin-bottom: 0;
  }
  
  .nav-treeview > .nav-item > .nav-link {
    padding: 0.35rem 0.5rem 0.35rem 2rem;
    font-size: 0.8rem;
  }
  
  .nav-treeview .nav-icon {
    font-size: 0.7rem;
  }
  
  [class*="sidebar-dark"] .user-panel {
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  }
</style>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{ route('dashboard.index') }}" class="brand-link" style="background: linear-gradient(135deg, #0b4d75 0%, #063245 100%);">
    <img src="{{ asset('templates/dist/img/k3.jpeg') }}" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">Safety Patrol</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- User Panel -->
    <div class="user-panel mt-2 pb-2 mb-2 d-flex">
      <div class="image">
        <img src="{{ asset('templates/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User">
      </div>
      <div class="info">
        <a href="#" class="d-block">{{ $user->name }}</a>
        <small class="text-muted d-block">{{ ucwords(str_replace('_', ' ', strtolower($user->role))) }}</small>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-1">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        @foreach($menus as $menu)
          @if(canAccessMenu($menu, $user->role))
            @if(isset($menu->has_submenu) && $menu->has_submenu)
              <li class="nav-item {{ isActiveMenu($menu->submenu[0]->path, $currentPath) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ isActiveMenu($menu->submenu[0]->path, $currentPath) ? 'active' : '' }}">
                  <i class="nav-icon {{ $menu->icon }}"></i>
                  <p>
                    {{ $menu->title }}
                    <i class="fas fa-angle-left right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  @foreach($menu->submenu as $sub)
                    <li class="nav-item">
                      <a href="{{ url($sub->path) }}" class="nav-link {{ isActiveMenu($sub->path, $currentPath) ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ $sub->title }}</p>
                      </a>
                    </li>
                  @endforeach
                </ul>
              </li>
            @else
              <li class="nav-item">
                <a href="{{ url($menu->path) }}" class="nav-link {{ isActiveMenu($menu->path, $currentPath) ? 'active' : '' }}">
                  <i class="nav-icon {{ $menu->icon }}"></i>
                  <p>{{ $menu->title }}</p>
                </a>
              </li>
            @endif
          @endif
        @endforeach

        <!-- Logout -->
        <li class="nav-item">
          <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="nav-icon fas fa-sign-out-alt text-danger"></i>
            <p>Logout</p>
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </li>
      </ul>
    </nav>
  </div>
</aside>

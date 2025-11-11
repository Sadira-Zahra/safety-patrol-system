<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Safety Patrol</title>

  <!-- AdminLTE & FontAwesome (JANGAN pakai 'public/' di depan) -->
  <link rel="stylesheet" href="{{ asset('templates/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('templates/dist/css/adminlte.min.css') }}">

  <style>
    body, p, h1, h2, h3, h4, h5, h6, span, div, a, li {
      font-family: Arial, sans-serif !important;
    }
  </style>
  @yield('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  {{-- Navbar --}}
  @include('layouts.components.navbar')

  {{-- Sidebar --}}
  @include('layouts.components.sidebar')

  {{-- Content --}}
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        @yield('header')
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        @yield('content')
      </div>
    </section>
  </div>
</div>

<!-- Script WAJIB (urutannya penting) -->
<script src="{{ asset('templates/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('templates/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('templates/dist/js/adminlte.min.js') }}"></script>
@yield('scripts')
</body>
</html>

{{-- layouts/main.blade.php (paling bawah) --}}
@stack('scripts')




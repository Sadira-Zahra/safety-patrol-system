@extends('layouts.main')

@section('header')
  <h1 class="m-0">
    Dashboard
    <small class="text-muted">Selamat datang, {{ Auth::user()->name }}</small>
  </h1>
@endsection

@section('content')
<div class="row">
  <!-- Info Boxes User -->
  <div class="col-lg-3 col-6">
    <div class="small-box bg-info">
      <div class="inner">
        <h3>{{ $totalUsers }}</h3>
        <p>Total User</p>
      </div>
      <div class="icon">
        <i class="fas fa-users"></i>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <div class="small-box bg-success">
      <div class="inner">
        <h3>{{ $totalDepartemen }}</h3>
        <p>Total Departemen</p>
      </div>
      <div class="icon">
        <i class="fas fa-building"></i>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <div class="small-box bg-warning">
      <div class="inner">
        <h3>{{ $totalCategories }}</h3>
        <p>Total Kategori</p>
      </div>
      <div class="icon">
        <i class="fas fa-tags"></i>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <div class="small-box bg-danger">
      <div class="inner">
        <h3>{{ $totalGrades }}</h3>
        <p>Total Grade</p>
      </div>
      <div class="icon">
        <i class="fas fa-star"></i>
      </div>
    </div>
  </div>
</div>

<!-- Statistik Temuan -->
<div class="row">
  <div class="col-12">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-clipboard-list mr-2"></i>Statistik Temuan
        </h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-2 col-sm-6 col-6">
            <div class="description-block border-right">
              <span class="description-percentage text-primary">
                <i class="fas fa-clipboard-list"></i>
              </span>
              <h5 class="description-header">{{ $totalFindings }}</h5>
              <span class="description-text">TOTAL</span>
            </div>
          </div>
          <div class="col-md-2 col-sm-6 col-6">
            <div class="description-block border-right">
              <span class="description-percentage text-secondary">
                <i class="fas fa-inbox"></i>
              </span>
              <h5 class="description-header">{{ $findingsOpen }}</h5>
              <span class="description-text">OPEN</span>
            </div>
          </div>
          <div class="col-md-3 col-sm-6 col-6">
            <div class="description-block border-right">
              <span class="description-percentage text-warning">
                <i class="fas fa-hourglass-half"></i>
              </span>
              <h5 class="description-header">{{ $findingsInProgress }}</h5>
              <span class="description-text">IN PROGRESS</span>
            </div>
          </div>
          <div class="col-md-2 col-sm-6 col-6">
            <div class="description-block border-right">
              <span class="description-percentage text-success">
                <i class="fas fa-check-circle"></i>
              </span>
              <h5 class="description-header">{{ $findingsCompleted }}</h5>
              <span class="description-text">COMPLETED</span>
            </div>
          </div>
          <div class="col-md-3 col-sm-6 col-6">
            <div class="description-block">
              <span class="description-percentage text-info">
                <i class="fas fa-archive"></i>
              </span>
              <h5 class="description-header">{{ $findingsClosed }}</h5>
              <span class="description-text">CLOSED</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <!-- Recent Findings -->
  <div class="col-md-7">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-history mr-2"></i>Temuan Terbaru
        </h3>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-sm table-hover">
            <thead>
              <tr>
                <th>No. Temuan</th>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @forelse($recentFindings as $finding)
                <tr>
                  <td><strong>{{ $finding->finding_number }}</strong></td>
                  <td>{{ $finding->finding_date->format('d/m/Y') }}</td>
                  <td>{{ $finding->category->name ?? '-' }}</td>
                  <td>
                    @if($finding->status == 'OPEN')
                      <span class="badge badge-secondary">Open</span>
                    @elseif($finding->status == 'IN_PROGRESS')
                      <span class="badge badge-warning">In Progress</span>
                    @elseif($finding->status == 'COMPLETED')
                      <span class="badge badge-success">Completed</span>
                    @elseif($finding->status == 'CLOSED')
                      <span class="badge badge-info">Closed</span>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center text-muted py-3">Belum ada temuan</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Charts -->
  <div class="col-md-5">
    <!-- Temuan per Departemen -->
    <div class="card card-success card-outline">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-chart-pie mr-2"></i>Top 5 Departemen
        </h3>
      </div>
      <div class="card-body">
        <div class="chart-container" style="height: 200px;">
          <canvas id="deptChart"></canvas>
        </div>
      </div>
    </div>

    <!-- Temuan per Kategori -->
    <div class="card card-warning card-outline">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-chart-bar mr-2"></i>Top 5 Kategori
        </h3>
      </div>
      <div class="card-body">
        <div class="chart-container" style="height: 200px;">
          <canvas id="categoryChart"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('templates/plugins/chart.js/Chart.min.js') }}"></script>
<script>
$(function() {
  // Data untuk chart departemen
  var deptLabels = {!! json_encode($findingsByDepartment->pluck('name')) !!};
  var deptData = {!! json_encode($findingsByDepartment->pluck('total')) !!};

  // Departemen Chart
  var deptCtx = document.getElementById('deptChart').getContext('2d');
  new Chart(deptCtx, {
    type: 'doughnut',
    data: {
      labels: deptLabels,
      datasets: [{
        data: deptData,
        backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8']
      }]
    },
    options: {
      maintainAspectRatio: false,
      legend: { position: 'bottom', labels: { boxWidth: 12, fontSize: 11 } }
    }
  });

  // Data untuk chart kategori
  var catLabels = {!! json_encode($findingsByCategory->pluck('name')) !!};
  var catData = {!! json_encode($findingsByCategory->pluck('total')) !!};

  // Category Chart
  var catCtx = document.getElementById('categoryChart').getContext('2d');
  new Chart(catCtx, {
    type: 'bar',
    data: {
      labels: catLabels,
      datasets: [{
        label: 'Jumlah Temuan',
        data: catData,
        backgroundColor: '#ffc107'
      }]
    },
    options: {
      maintainAspectRatio: false,
      legend: { display: false },
      scales: {
        yAxes: [{ ticks: { beginAtZero: true, stepSize: 1 } }]
      }
    }
  });
});
</script>
@endsection

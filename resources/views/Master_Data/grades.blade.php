@extends('layouts.main')

@section('header')
  
@endsection

@section('styles')
  <link rel="stylesheet" href="{{ asset('templates/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('templates/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <style>
    .compact-container {
      max-width: 1400px;
      margin: 0 auto;
    }
    .compact-card .card-header {
      padding: 0.75rem 1rem;
      background: linear-gradient(135deg, #0b4d75 0%, #063245 100%);
      color: white;
    }
    .compact-card .card-title {
      font-size: 0.95rem;
      margin-bottom: 0;
      font-weight: 500;
    }
    .compact-card .card-body {
      padding: 1rem;
    }
    .compact-card .card-footer {
      padding: 0.5rem 1rem;
      font-size: 0.75rem;
    }
    .compact-card .btn {
      font-size: 0.8rem;
      padding: 0.35rem 0.75rem;
    }
    .table {
      font-size: 0.85rem;
    }
    .table th {
      font-size: 0.8rem;
      padding: 0.5rem;
      font-weight: 600;
      background-color: #f8f9fa;
    }
    .table td {
      padding: 0.5rem;
      vertical-align: middle;
    }
    .btn-sm {
      font-size: 0.75rem;
      padding: 0.25rem 0.5rem;
    }
    .modal-header {
      padding: 0.75rem 1rem;
      background: linear-gradient(135deg, #0b4d75 0%, #063245 100%);
      color: white;
    }
    .modal-title {
      font-size: 0.95rem;
    }
    .modal-body {
      padding: 1rem;
    }
    .modal-footer {
      padding: 0.5rem 1rem;
    }
    .form-group {
      margin-bottom: 0.75rem;
    }
    .form-group label {
      font-size: 0.85rem;
      margin-bottom: 0.25rem;
      font-weight: 500;
    }
    .form-control {
      font-size: 0.85rem;
      padding: 0.375rem 0.75rem;
    }
    .alert {
      padding: 0.5rem 1rem;
      font-size: 0.85rem;
    }
  </style>
@endsection

@section('content')
<div class="compact-container">
  <div class="row">
    <div class="col-12">

      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
          <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
          <button type="button" class="close" data-dismiss="alert" style="padding: 0.5rem;"><span>&times;</span></button>
        </div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
          <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
          <button type="button" class="close" data-dismiss="alert" style="padding: 0.5rem;"><span>&times;</span></button>
        </div>
      @endif

      <div class="card card-outline card-primary compact-card">
        <div class="card-header">
          <h3 class="card-title">
            <i class="fas fa-star mr-2"></i>Grade
          </h3>
          <div class="card-tools">
            <a href="#" class="btn btn-light btn-sm" data-toggle="modal" data-target="#modalAdd">
              <i class="fas fa-plus mr-1"></i>Tambah Grade
            </a>
          </div>
        </div>

        <div class="card-body">
          <div class="table-responsive">
            <table id="table-grades" class="table table-bordered table-striped table-hover">
              <thead>
                <tr>
                  <th style="width:50px;">NO</th>
                  <th>KODE</th>
                  <th>SLA (Hari)</th>
                  <th style="width:100px;">OPSI</th>
                </tr>
              </thead>
              <tbody>
                @php $no = 1; @endphp
                @forelse($grades ?? [] as $grade)
                  <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $grade->code }}</td>
                    <td>{{ $grade->sla_days }}</td>
                    <td class="text-center text-nowrap">
                      <a href="#" class="btn btn-warning btn-sm btn-edit"
                         data-show="{{ route('grades.show', $grade->id) }}"
                         data-update="{{ route('grades.update', $grade->id) }}">
                        <i class="fas fa-edit"></i>
                      </a>
                      <form action="{{ route('grades.destroy', $grade->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="4" class="text-center text-muted">Belum ada data</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <div class="card-footer d-flex justify-content-between align-items-center">
          <small class="text-muted">Copyright © {{ date('Y') }} - Safety Patrol</small>
          <small class="text-muted">Version 1.0</small>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ===================== MODAL TAMBAH ===================== --}}
<div class="modal fade" id="modalAdd" tabindex="-1">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="fas fa-plus-circle mr-2"></i>Tambah Grade
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>

      <form action="{{ route('grades.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label>Kode Grade <span class="text-danger">*</span></label>
            <input type="text" name="code" class="form-control" placeholder="Contoh: A, B, C, A1, B2 .." maxlength="10" required>
            <small class="text-muted">Kode harus unik</small>
          </div>
          <div class="form-group">
            <label>SLA (Hari) <span class="text-danger">*</span></label>
            <input type="number" name="sla_days" class="form-control" placeholder="Jumlah hari .." min="1" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i>Tutup
          </button>
          <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-save mr-1"></i>Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- ===================== MODAL EDIT ===================== --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="fas fa-edit mr-2"></i>Edit Data Grade
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>

      <form id="formEdit" method="POST" action="#">
        @csrf @method('PUT')
        <div class="modal-body">
          <div class="form-group">
            <label>Kode Grade <span class="text-danger">*</span></label>
            <input type="text" name="code" id="edit_code" class="form-control" maxlength="10" required>
            <small class="text-muted">Kode harus unik</small>
          </div>
          <div class="form-group">
            <label>SLA (Hari) <span class="text-danger">*</span></label>
            <input type="number" name="sla_days" id="edit_sla_days" class="form-control" min="1" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i>Tutup
          </button>
          <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-save mr-1"></i>Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
  <script src="{{ asset('templates/plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('templates/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('templates/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('templates/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

  <script>
    $(function () {
      $('#table-grades').DataTable({
        paging: true, 
        searching: true, 
        ordering: false,
        info: true, 
        autoWidth: false, 
        responsive: true,
        pageLength: 10,
        language: {
          search: "Cari:",
          lengthMenu: "Tampilkan _MENU_ data",
          info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
          infoEmpty: "Tidak ada data",
          zeroRecords: "Data tidak ditemukan",
          paginate: {
            first: "Pertama",
            last: "Terakhir",
            next: "Selanjutnya",
            previous: "Sebelumnya"
          }
        }
      });

      // buka modal edit
      $(document).on('click', '.btn-edit', function (e) {
        e.preventDefault();
        const showUrl = $(this).data('show');
        const updateUrl = $(this).data('update');

        $('#formEdit')[0].reset();

        $.get(showUrl, function (res) {
          $('#edit_code').val(res.code);
          $('#edit_sla_days').val(res.sla_days);
          $('#formEdit').attr('action', updateUrl);
          $('#modalEdit').modal('show');
        }).fail(function () {
          alert('Gagal memuat data.');
        });
      });
    });
  </script>
@endsection

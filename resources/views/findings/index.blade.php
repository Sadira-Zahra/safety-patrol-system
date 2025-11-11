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
    }
    .modal-title {
      font-size: 0.95rem;
    }
    .modal-body {
      padding: 1rem;
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

    .modal-split .left-pane { border-right: 1px dashed #dee2e6; }
  .modal-split .section-title { font-weight: 600; font-size: 0.9rem; margin-bottom: .5rem; }
  .modal-split .require-note { font-size: 0.85rem; }

  .preview-image {
        max-width: 100%;
        max-height: 300px;
        margin-top: 10px;
        display: none;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 5px;
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
            <i class="fas fa-clipboard-list mr-2"></i>Daftar Temuan
          </h3>
          <div class="card-tools">
            @if($user->role === 'SAFETY_PATROLLER')
              <button type="button" class="btn btn-light btn-sm" data-toggle="modal" data-target="#modalForm">
                <i class="fas fa-plus mr-1"></i>Tambah Temuan
              </button>
            @endif
          </div>
        </div>
        
        <div class="card-body">
          <div class="table-responsive">
            <table id="findingsTable" class="table table-bordered table-striped table-hover">
              <thead>
                <tr>
                  <th style="width:50px;">No</th>
                  <th>No. Temuan</th>
                  <th>Tanggal</th>
                  <th>Departemen</th>
                  <th>Kategori</th>
                  <th>Grade</th>
                  <th>Lokasi</th>
                  <th>Status</th>
                  <th>Patroller</th>
                  @if($user->role !== 'SAFETY_PATROLLER')
                    <th>PIC</th>
                  @endif
                  <th style="width:120px;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse($findings as $i => $finding)
                  <tr>
                    <td class="text-center">{{ $i+1 }}</td>
                    <td><strong>{{ $finding->finding_number }}</strong></td>
                    <td>{{ $finding->finding_date ? $finding->finding_date->format('d/m/Y') : '-' }}</td>
                    <td>{{ $finding->departemen->name ?? '-' }}</td>
                    <td>{{ $finding->category->name ?? '-' }}</td>
                    <td><span class="badge badge-info">{{ $finding->grade->code ?? '-' }}</span></td>
                    <td>{{ $finding->location }}</td>
                    <td>
                      @if($finding->status === 'PENDING')
                        <span class="badge badge-warning">Pending</span>
                      @elseif($finding->status === 'IN_PROGRESS')
                        <span class="badge badge-info">In Progress</span>
                      @elseif($finding->status === 'COMPLETED')
                        <span class="badge badge-success">Completed</span>
                      @else
                        <span class="badge badge-secondary">Closed</span>
                      @endif
                    </td>
                    <td>{{ $finding->reporter->name ?? '-' }}</td>
                    @if ($user->role !== 'SAFETY_PATROLLER')
    <td>
        {{ $finding->pic_id ? ($finding->pic->name ?? '-') : '-' }}
    </td>
@endif

                    <td class="text-center text-nowrap">
  <button type="button" class="btn btn-info btn-sm" onclick="showDetail({{ $finding->id }})" title="Lihat Detail">
    <i class="fas fa-eye"></i>
  </button>

  @if($user->role === 'SAFETY_ADMIN' && $finding->status === 'COMPLETED')
    <button type="button" class="btn btn-success btn-sm" onclick="closeFinding({{ $finding->id }})" title="Verifikasi Counter Action">
      <i class="fas fa-check-circle"></i>
    </button>
  @endif

  {{-- @if($user->role === 'DEPT_PIC' && $finding->status === 'IN_PROGRESS')
    <button type="button" class="btn btn-primary btn-sm" onclick="updateAction({{ $finding->id }})" title="Update Counter Action">
      <i class="fas fa-tasks"></i>
    </button>
  @endif --}}

  @if($user->role === 'SAFETY_PATROLLER' && $finding->status === 'PENDING')
    <button type="button" class="btn btn-danger btn-sm" onclick="deleteFinding({{ $finding->id }})" title="Hapus Temuan">
      <i class="fas fa-trash"></i>
    </button>
  @endif
</td>

                  </tr>
                @empty
                  <tr>
                    <td colspan="{{ $user->role === 'SAFETY_PATROLLER' ? 10 : 11 }}" class="text-center text-muted">
                      Belum ada data temuan
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Form Tambah Temuan (Safety Patroller) -->
@if($user->role === 'SAFETY_PATROLLER')
<div class="modal fade" id="modalForm" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="{{ route('findings.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header bg-primary">
          <h5 class="modal-title text-white">
            <i class="fas fa-plus-circle mr-2"></i>Tambah Temuan Baru
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Penanggung Jawab Departemen <span class="text-danger">*</span></label>
                <select name="departemen_id" class="form-control" required>
                  <option value="">-- Pilih Departemen --</option>
                  @foreach($departemens as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Kategori <span class="text-danger">*</span></label>
                <select name="category_id" class="form-control" required>
                  <option value="">-- Pilih Kategori --</option>
                  @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Grade <span class="text-danger">*</span></label>
                <select name="grade_id" class="form-control" required>
                  <option value="">-- Pilih Grade --</option>
                  @foreach($grades as $grade)
                    <option value="{{ $grade->id }}">{{ $grade->code }} ({{ $grade->sla_days }} hari)</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Section <span class="text-danger">*</span></label>
                <input type="text" name="section" class="form-control" placeholder="Contoh: Area Produksi" required>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label>Lokasi <span class="text-danger">*</span></label>
                <input type="text" name="location" class="form-control" placeholder="Contoh: Gedung A Lt. 2" required>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label>Deskripsi Temuan <span class="text-danger">*</span></label>
                <textarea name="description" class="form-control" rows="4" placeholder="Jelaskan detail temuan..." required></textarea>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label>Foto Before <span class="text-danger">*</span></label>
                <div class="custom-file">
                  <input type="file" name="image_before" class="custom-file-input" id="imageBefore" accept="image/*" required>
                  <label class="custom-file-label" for="imageBefore">Pilih file...</label>
                </div>
                <small class="text-muted">Format: JPG, PNG, JPEG (Ukuran bebas, otomatis dikompres)</small>
                
                <div id="previewBefore" class="mt-3" style="display:none;">
                  <img id="imgPreviewBefore" src="" class="img-thumbnail" style="max-height: 200px;">
                  <div class="mt-2">
                    <button type="button" class="btn btn-sm btn-info" onclick="viewImage('imgPreviewBefore')">
                      <i class="fas fa-eye"></i> Lihat Foto
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i>Batal
          </button>
          <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-save mr-1"></i>Simpan Temuan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif

<!-- Modal Detail (Semua Role) -->
<div class="modal fade" id="modalDetail" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-info">
        <h5 class="modal-title text-white">
          <i class="fas fa-info-circle mr-2"></i>Detail Temuan
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="detailContent">
        <div class="text-center">
          <i class="fas fa-spinner fa-spin fa-3x text-info"></i>
          <p class="mt-3">Loading...</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Update Action (PIC Departemen) -->
@if($user->role === 'DEPT_PIC')
<div class="modal fade" id="modalAction" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="formAction" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header bg-primary">
          <h5 class="modal-title text-white">
            <i class="fas fa-edit mr-2"></i>Update Action Plan
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Action Plan <span class="text-danger">*</span></label>
                <textarea name="action_plan" class="form-control" rows="3" required></textarea>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Tanggal Selesai <span class="text-danger">*</span></label>
                <input type="date" name="completion_date" class="form-control" required>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Jam Selesai <span class="text-danger">*</span></label>
                <input type="time" name="completion_time" class="form-control" required>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label>Counter Action <span class="text-danger">*</span></label>
                <textarea name="counter_action" class="form-control" rows="3" required></textarea>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label>Lokasi Action <span class="text-danger">*</span></label>
                <input type="text" name="action_location" class="form-control" required>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label>Foto After <span class="text-danger">*</span></label>
                <div class="custom-file">
                  <input type="file" name="image_after" class="custom-file-input" id="imageAfter" accept="image/*" required>
                  <label class="custom-file-label" for="imageAfter">Pilih file...</label>
                </div>
                <small class="text-muted">Format: JPG, PNG, JPEG (Ukuran bebas, otomatis dikompres)</small>
                
                <div id="previewAfter" class="mt-3" style="display:none;">
                  <img id="imgPreviewAfter" src="" class="img-thumbnail" style="max-height: 200px;">
                  <div class="mt-2">
                    <button type="button" class="btn btn-sm btn-info" onclick="viewImage('imgPreviewAfter')">
                      <i class="fas fa-eye"></i> Lihat Foto
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i>Batal
          </button>
          <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-save mr-1"></i>Submit
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif

<!-- Modal Close (Safety Admin) - VERSI BARU -->
@if($user->role === 'SAFETY_ADMIN')
<div class="modal fade" id="modalClose" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formClose" method="POST">
        @csrf
        <div class="modal-header bg-success">
          <h5 class="modal-title text-white">
            <i class="fas fa-check-circle mr-2"></i>Verifikasi Counter Action
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        
        <div class="modal-body">
          <div class="alert alert-info">
            <i class="fas fa-info-circle mr-2"></i>
            Pastikan counter action dan foto after sudah sesuai sebelum melakukan verifikasi.
          </div>

          <div class="form-group">
            <label class="font-weight-bold">Apakah counter action sudah sesuai? <span class="text-danger">*</span></label>
            <div class="mt-2">
              <div class="custom-control custom-radio mb-2">
                <input type="radio" id="approveRadio" name="verification_result" value="approved" class="custom-control-input" required>
                <label class="custom-control-label" for="approveRadio">
                  <span class="text-success font-weight-bold"><i class="fas fa-check-circle mr-1"></i>Ya</span>, setujui dan tutup temuan (Status: CLOSED)
                </label>
              </div>
              <div class="custom-control custom-radio">
                <input type="radio" id="rejectRadio" name="verification_result" value="rejected" class="custom-control-input" required>
                <label class="custom-control-label" for="rejectRadio">
                  <span class="text-danger font-weight-bold"><i class="fas fa-times-circle mr-1"></i>Tidak</span>, kembalikan ke PIC untuk perbaikan (Status: IN_PROGRESS)
                </label>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label>Catatan Verifikasi (opsional)</label>
            <textarea name="close_note" class="form-control" rows="3" placeholder="Berikan catatan jika diperlukan..."></textarea>
            <small class="text-muted">Catatan akan dilihat oleh PIC departemen</small>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i>Batal
          </button>
          <button type="submit" class="btn btn-success btn-sm">
            <i class="fas fa-paper-plane mr-1"></i>Submit Verifikasi
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif


<!-- Modal Verifikasi Counter Action -->
<div class="modal fade" id="verifyCounterActionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST" id="verifyCounterActionForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Verifikasi Counter Action</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Counter Action dari PIC:</label>
                        <p id="counterActionText" class="border p-2 bg-light"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Apakah counter action sudah sesuai?</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="verification_result" 
                                   id="approveRadio" value="approved" required>
                            <label class="form-check-label" for="approveRadio">
                                <strong class="text-success">Ya</strong>, setujui dan tutup temuan
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="verification_result" 
                                   id="rejectRadio" value="rejected" required>
                            <label class="form-check-label" for="rejectRadio">
                                <strong class="text-danger">Tidak</strong>, kembalikan ke PIC untuk perbaikan
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="closeNote" class="form-label">Catatan (opsional)</label>
                        <textarea class="form-control" id="closeNote" name="close_note" rows="3" 
                                  placeholder="Berikan catatan jika diperlukan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Submit Verifikasi</button>
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
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function() {
  $('#findingsTable').DataTable({
    responsive: true,
    autoWidth: false,
    order: [[2, 'desc']],
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

  $('#imageBefore').on('change', function() {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        $('#imgPreviewBefore').attr('src', e.target.result);
        $('#previewBefore').show();
      }
      reader.readAsDataURL(file);
      $(this).next('.custom-file-label').html(file.name);
    }
  });

  $('#imageAfter').on('change', function() {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        $('#imgPreviewAfter').attr('src', e.target.result);
        $('#previewAfter').show();
      }
      reader.readAsDataURL(file);
      $(this).next('.custom-file-label').html(file.name);
    }
  });
});

function viewImage(imgId) {
  const imgSrc = $('#' + imgId).attr('src');
  const modal = `
    <div class="modal fade" id="modalViewImage" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-dark">
            <h5 class="modal-title text-white">Preview Foto</h5>
            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body text-center bg-dark">
            <img src="${imgSrc}" class="img-fluid" style="max-width: 100%;">
          </div>
        </div>
      </div>
    </div>
  `;
  $('body').append(modal);
  $('#modalViewImage').modal('show');
  $('#modalViewImage').on('hidden.bs.modal', function() {
    $(this).remove();
  });
}

function showDetail(id) {
  const userRole = '{{ $user->role }}';
  
  $.ajax({
    url: `/findings/${id}`,
    method: 'GET',
    success: function(data) {
      if (userRole === 'SAFETY_ADMIN') {
        showAdminDetail(data);
      } else {
        showReadOnlyDetail(data);
      }
      $('#modalDetail').modal('show');
    },
    error: function() {
      alert('Gagal memuat data');
    }
  });
}

// Detail Read-Only untuk role lain
// index.blade.php
function showReadOnlyDetail(data) {
  let html = `
    <div class="row">
      <div class="col-md-6">
        <table class="table table-sm table-bordered">
          <tr><th width="140">No. Temuan:</th><td>${data.finding_number}</td></tr>
          <tr><th>Tanggal:</th><td>${moment(data.finding_date).format('DD/MM/YYYY')}</td></tr>
          <tr><th>Departemen:</th><td>${data.departemen ? data.departemen.name : '-'}</td></tr>
          <tr><th>Kategori:</th><td>${data.category ? data.category.name : '-'}</td></tr>
          <tr><th>Grade:</th><td><span class="badge badge-info">${data.grade ? data.grade.code : '-'}</span></td></tr>
          <tr><th>Lokasi:</th><td>${data.location || '-'}</td></tr>
          <tr><th>Section:</th><td>${data.section || '-'}</td></tr>
          <tr><th>Status:</th><td>${getStatusBadge(data.status)}</td></tr>
        </table>
      </div>
      <div class="col-md-6">
        <table class="table table-sm table-bordered">
          <tr><th width="140">Patroller:</th><td>${data.reporter ? data.reporter.name : '-'}</td></tr>
          <tr><th>PIC:</th><td>${data.pic_id ? (data.pic ? data.pic.name : '-') : '-'}</td></tr>
          <tr><th>Manager:</th><td>${data.manager ? data.manager.name : '-'}</td></tr>
          <tr><th>Target:</th><td>${data.target_date ? moment(data.target_date).format('DD/MM/YYYY') : '-'}</td></tr>
          <tr><th>Completion:</th><td>${data.completion_date ? (moment(data.completion_date).format('DD/MM/YYYY') + ' ' + (data.completion_time || '')) : '-'}</td></tr>
          <tr><th>Verified By:</th><td>${data.verifiedBy ? data.verifiedBy.name : '-'}</td></tr>
          <tr><th>Closed By:</th><td>${data.closedBy ? data.closedBy.name : '-'}</td></tr>
        </table>
      </div>
    </div>
    <hr>
    <div class="row modal-split">
      <!-- KIRI: Foto Before + Deskripsi -->
      <div class="col-md-6 left-pane">
        <h6 class="section-title">Foto Before</h6>
        <a href="/storage/${data.image_before}" target="_blank" class="btn btn-info btn-sm btn-block">
          <i class="fas fa-eye mr-1"></i> Lihat Foto
        </a>

        <h6 class="section-title mt-3">Deskripsi</h6>
        <p>${data.description || '-'}</p>
      </div>

      <!-- KANAN: Form Update Action (DEPT_PIC) atau ringkasan hasil -->
      <div class="col-md-6">
        @if($user->role === 'DEPT_PIC' && $user->role)
        ${data.status === 'IN_PROGRESS' ? `
          <div class="alert alert-warning require-note" role="alert">
            Harap isi Counter Action dan unggah Foto After untuk menyelesaikan temuan.
          </div>
          <h6 class="section-title">Counter Action</h6>
          <form id="formUpdateAction" method="POST" enctype="multipart/form-data" action="{{ url('/findings') }}/${data.id}/action-plan">
            @csrf
            <div class="form-group">
              <textarea name="counter_action" class="form-control form-control-sm" rows="4" placeholder="Tuliskan tindakan perbaikan" required></textarea>
            </div>

            <h6 class="section-title mt-2">Foto After</h6>
            <div class="form-group">
              <input type="file" name="image_after" class="form-control form-control-sm" accept="image/*" required>
            </div>

           

            <div class="text-right">
              <button type="submit" class="btn btn-primary btn-sm">
                <i class="fas fa-save mr-1"></i> Update Action
              </button>
            </div>
          </form>
        ` : `
          <h6 class="section-title">Counter Action</h6>
          <div class="form-group">
            <textarea class="form-control form-control-sm" rows="4" readonly style="background-color:#f8f9fa;">${data.counter_action || '-'}</textarea>
          </div>
          ${data.image_after ? `
            <h6 class="section-title">Foto After</h6>
            <a href="/storage/${data.image_after}" target="_blank" class="btn btn-success btn-sm btn-block">
              <i class="fas fa-eye mr-1"></i> Lihat Foto
            </a>
          ` : ''}
        `}
        @else
          <!-- Untuk non DEPT_PIC, cukup tampilkan ringkasan jika ada -->
          ${data.status === 'COMPLETED' ? `
            <h6 class="section-title">Counter Action</h6>
            <div class="form-group">
              <textarea class="form-control form-control-sm" rows="4" readonly style="background-color:#f8f9fa;">${data.counter_action || '-'}</textarea>
            </div>
            ${data.image_after ? `
              <h6 class="section-title">Foto After</h6>
              <a href="/storage/${data.image_after}" target="_blank" class="btn btn-success btn-sm btn-block">
                <i class="fas fa-eye mr-1"></i> Lihat Foto
              </a>
            ` : ''}
          ` : ''}
        @endif
      </div>
    </div>
  `;
  $('#detailContent').html(html);
}


// Detail Editable untuk Safety Admin
function showAdminDetail(data) {
  const isEditable = data.status === 'PENDING';
  const disabledAttr = isEditable ? '' : 'disabled';
  
  let departemenOptions = '';
  @foreach($departemens as $dept)
    departemenOptions += `<option value="{{ $dept->id }}" ${data.department_id == {{ $dept->id }} ? 'selected' : ''}>{{ $dept->name }}</option>`;
  @endforeach
  
  let categoryOptions = '';
  @foreach($categories as $cat)
    categoryOptions += `<option value="{{ $cat->id }}" ${data.category_id == {{ $cat->id }} ? 'selected' : ''}>{{ $cat->name }}</option>`;
  @endforeach
  
  let gradeOptions = '';
  @foreach($grades as $grade)
    gradeOptions += `<option value="{{ $grade->id }}" ${data.grade_id == {{ $grade->id }} ? 'selected' : ''}>{{ $grade->code }} ({{ $grade->sla_days }} hari)</option>`;
  @endforeach
  
  let html = `
    <form id="formEditDetail" action="/findings/${data.id}" method="POST">
      @csrf
      @method('PUT')
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label>No. Temuan</label>
            <input type="text" class="form-control form-control-sm" value="${data.finding_number}" readonly style="background-color: #f8f9fa;">
          </div>
          
          <div class="form-group">
            <label>Tanggal</label>
            <input type="text" class="form-control form-control-sm" value="${moment(data.finding_date).format('DD/MM/YYYY')}" readonly style="background-color: #f8f9fa;">
          </div>
          
          <div class="form-group">
            <label>Departemen <span class="text-danger">*</span></label>
            <select name="departemen_id" class="form-control form-control-sm" required ${disabledAttr}>
              ${departemenOptions}
            </select>
          </div>
          
          <div class="form-group">
            <label>Kategori <span class="text-danger">*</span></label>
            <select name="category_id" class="form-control form-control-sm" required ${disabledAttr}>
              ${categoryOptions}
            </select>
          </div>
          
          <div class="form-group">
            <label>Grade <span class="text-danger">*</span></label>
            <select name="grade_id" class="form-control form-control-sm" required ${disabledAttr}>
              ${gradeOptions}
            </select>
          </div>
        </div>
        
        <div class="col-md-6">
          <div class="form-group">
            <label>Section <span class="text-danger">*</span></label>
            <input type="text" name="section" class="form-control form-control-sm" value="${data.section}" required ${disabledAttr}>
          </div>
          
          <div class="form-group">
            <label>Lokasi <span class="text-danger">*</span></label>
            <input type="text" name="location" class="form-control form-control-sm" value="${data.location}" required ${disabledAttr}>
          </div>
          
          <div class="form-group">
            <label>Status</label>
            <input type="text" class="form-control form-control-sm" value="${getStatusText(data.status)}" readonly style="background-color: #f8f9fa;">
          </div>
          
          <div class="form-group">
            <label>Patroller</label>
            <input type="text" class="form-control form-control-sm" value="${data.reporter.name}" readonly style="background-color: #f8f9fa;">
          </div>
          
          <div class="form-group">
    <label>PIC</label>
    <input type="text" class="form-control form-control-sm"
        value="${data.pic_id ? (data.pic ? data.pic.name : '-') : '-'}"
        readonly style="background-color:#f8f9fa;">
</div>

        </div>
        
        <div class="col-md-12">
          <div class="form-group">
            <label>Deskripsi Temuan <span class="text-danger">*</span></label>
            <textarea name="description" class="form-control form-control-sm" rows="4" required ${disabledAttr}>${data.description}</textarea>
          </div>
        </div>
        
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-3">
              <h6 class="font-weight-bold mb-2" style="font-size: 0.9rem;">Foto Before</h6>
              <a href="/storage/${data.image_before}" target="_blank" class="btn btn-info btn-sm btn-block">
                <i class="fas fa-eye mr-1"></i> Lihat Foto
              </a>
              ${data.image_after ? `
                <h6 class="font-weight-bold mb-2 mt-3" style="font-size: 0.9rem;">Foto After</h6>
                <a href="/storage/${data.image_after}" target="_blank" class="btn btn-success btn-sm btn-block">
                  <i class="fas fa-eye mr-1"></i> Lihat Foto
                </a>
              ` : ''}
            </div>
            <div class="col-md-9">
             
              ${data.counter_action ? `
                <div class="form-group">
                  <label style="font-size: 0.9rem;">Counter Action</label>
                  <textarea class="form-control form-control-sm" rows="2" readonly style="background-color: #f8f9fa;">${data.counter_action}</textarea>
                </div>
              ` : ''}
            </div>
          </div>
        </div>
      </div>
      
      <hr>
      
      <div class="text-right">
        ${isEditable ? `
          <button type="button" class="btn btn-success btn-sm mr-2" 
                  onclick="confirmVerifyModal(${data.id}, '${data.departemen.name}', '${data.grade.code}')">
            <i class="fas fa-check mr-1"></i> Verifikasi Temuan
          </button>
          <button type="submit" class="btn btn-warning btn-sm">
            <i class="fas fa-save mr-1"></i> Update Data
          </button>
        ` : `
          <div class="alert alert-info mb-0 text-left">
            <i class="fas fa-info-circle mr-2"></i>
            <strong>Status: ${getStatusText(data.status)}</strong> - Temuan sudah diverifikasi, data tidak dapat diubah lagi.
          </div>
        `}
      </div>
    </form>
  `;
  $('#detailContent').html(html);
  
  if (isEditable) {
    $('#formEditDetail').on('submit', function(e) {
      e.preventDefault();
      $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: $(this).serialize(),
        success: function() {
          $('#modalDetail').modal('hide');
          Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data temuan berhasil diupdate',
            timer: 1500,
            showConfirmButton: false
          });
          setTimeout(function() {
            location.reload();
          }, 1500);
        },
        error: function() {
          Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Gagal update data temuan',
          });
        }
      });
    });
  }
}

function confirmVerifyModal(id, deptName, gradeCode) {
  if (confirm(`Verifikasi temuan ini?\n\nAkan dikirim ke SEMUA PIC departemen ${deptName}\n\nTarget selesai otomatis berdasarkan grade ${gradeCode}`)) {
    $.ajax({
      url: `/findings/${id}/verify`,
      method: 'POST',
      data: {
        _token: '{{ csrf_token() }}',
        _method: 'PUT'
      },
      beforeSend: function() {
        $('#modalDetail').modal('hide');
      },
      success: function(response) {
        Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: 'Temuan berhasil diverifikasi dan dikirim ke semua PIC departemen',
          timer: 2000,
          showConfirmButton: false
        });
        
        setTimeout(function() {
          location.reload();
        }, 2000);
      },
      error: function(xhr) {
        Swal.fire({
          icon: 'error',
          title: 'Gagal!',
          text: xhr.responseJSON?.message || 'Gagal verifikasi temuan',
        });
      }
    });
  }
}

function getStatusText(status) {
  const statusText = {
    'PENDING': 'Pending',
    'IN_PROGRESS': 'In Progress',
    'COMPLETED': 'Completed',
    'CLOSED': 'Closed'
  };
  return statusText[status] || status;
}

function getStatusBadge(status) {
  const badges = {
    'PENDING': '<span class="badge badge-warning">Pending</span>',
    'IN_PROGRESS': '<span class="badge badge-info">In Progress</span>',
    'COMPLETED': '<span class="badge badge-success">Completed</span>',
    'CLOSED': '<span class="badge badge-secondary">Closed</span>'
  };
  return badges[status] || status;
}

@if($user->role === 'SAFETY_ADMIN')
function closeFinding(id) {
  $('#formClose').attr('action', `/findings/${id}/close`);
  $('#modalClose').modal('show');
}

// Handle form submit dengan konfirmasi
$('#formClose').on('submit', function(e) {
  e.preventDefault();
  
  const result = $('input[name="verification_result"]:checked').val();
  const resultText = result === 'approved' ? 'APPROVE dan CLOSE temuan ini' : 'REJECT dan kembalikan ke PIC';
  
  if (confirm(`Yakin ingin ${resultText}?`)) {
    $.ajax({
      url: $(this).attr('action'),
      method: 'POST',
      data: $(this).serialize(),
      success: function(response) {
        $('#modalClose').modal('hide');
        Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: response.message || 'Verifikasi berhasil',
          timer: 2000,
          showConfirmButton: false
        });
        setTimeout(function() {
          location.reload();
        }, 2000);
      },
      error: function(xhr) {
        Swal.fire({
          icon: 'error',
          title: 'Gagal!',
          text: xhr.responseJSON?.message || 'Gagal melakukan verifikasi',
        });
      }
    });
  }
});
@endif

@if($user->role === 'DEPT_PIC')
function updateAction(id) {
  $('#formAction').attr('action', `/findings/${id}/action`);
  $('#modalAction').modal('show');
}
@endif

function deleteFinding(id) {
  if(confirm('Yakin ingin menghapus temuan ini?')) {
    $.ajax({
      url: `/findings/${id}`,
      method: 'DELETE',
      data: {_token: '{{ csrf_token() }}'},
      success: function() {
        location.reload();
      },
      error: function() {
        alert('Gagal menghapus data');
      }
    });
  }
}
</script>
@endsection

@extends('layouts.main')

@section('header')

@endsection

@section('styles')
<style>
    /* Compact Container */
    .compact-container {
        max-width: 1400px;
        margin: 0 auto;
    }
    
    /* Filter Card */
    .filter-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        padding: 1.25rem;
        margin-bottom: 1rem;
    }
    
    .filter-card h5 {
        font-size: 1rem;
        margin-bottom: 1rem;
        font-weight: 600;
        color: #2c3e50;
        border-bottom: 2px solid #4472C4;
        padding-bottom: 0.5rem;
    }
    
    .filter-card .form-label {
        font-size: 0.85rem;
        margin-bottom: 0.35rem;
        font-weight: 500;
        color: #495057;
    }
    
    .filter-card .form-control,
    .filter-card .form-select {
        font-size: 0.85rem;
        padding: 0.5rem 0.75rem;
        border: 1px solid #ced4da;
        border-radius: 4px;
    }
    
    .filter-card .form-control:focus,
    .filter-card .form-select:focus {
        border-color: #4472C4;
        box-shadow: 0 0 0 0.2rem rgba(68, 114, 196, 0.25);
    }
    
    /* Button Group */
    .button-group {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    /* Button Styles */
    .btn-filter,
    .btn-export,
    .btn-reset {
        font-size: 0.85rem;
        padding: 0.5rem 1.25rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .btn-filter {
        background: #4472C4;
        color: #fff;
    }
    
    .btn-filter:hover {
        background: #365ba8;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    
    .btn-export {
        background: #28a745;
        color: #fff;
    }
    
    .btn-export:hover {
        background: #218838;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    
    .btn-reset {
        background: #6c757d;
        color: #fff;
    }
    
    .btn-reset:hover {
        background: #5a6268;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    
    /* Table Styles - Compact */
    .table-responsive {
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
    }
    
    .table {
        margin-bottom: 0;
        font-size: 0.85rem;
    }
    
    .table thead th {
        background: #4472C4;
        color: #fff;
        font-weight: 600;
        border: none;
        padding: 0.6rem 0.75rem;
        vertical-align: middle;
        font-size: 0.8rem;
    }
    
    .table tbody td {
        padding: 0.5rem 0.75rem;
        vertical-align: middle;
        font-size: 0.85rem;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    /* Badge Status - Extra Compact */
    .badge-status {
        padding: 0.25rem 0.5rem;
        border-radius: 3px;
        font-size: 0.7rem;
        font-weight: 500;
        display: inline-block;
    }
    
    .status-pending { 
        background-color: #ffc107; 
        color: #000; 
    }
    
    .status-in-progress { 
        background-color: #17a2b8; 
        color: #fff; 
    }
    
    .status-completed { 
        background-color: #28a745; 
        color: #fff; 
    }
    
    .status-closed { 
        background-color: #6c757d; 
        color: #fff; 
    }
    
    /* Button in table */
    .btn-sm {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    
    /* Alert */
    .alert {
        padding: 0.75rem 1rem;
        font-size: 0.85rem;
        margin-bottom: 1rem;
    }
    
    /* Modal Styles - Compact */
    .modal-header {
        padding: 0.75rem 1rem;
        background: linear-gradient(135deg, #4472C4 0%, #365ba8 100%);
        color: #fff;
        border: none;
    }
    
    .modal-title {
        font-size: 0.95rem;
        font-weight: 600;
    }
    
    .modal-body {
        padding: 1rem;
        font-size: 0.85rem;
    }
    
    /* Detail Table in Modal - Compact */
    .detail-table {
        width: 100%;
        margin-bottom: 0.75rem;
        font-size: 0.8rem;
    }
    
    .detail-table th {
        background-color: #f8f9fa;
        padding: 0.4rem 0.5rem;
        font-weight: 600;
        color: #495057;
        width: 35%;
        border: 1px solid #dee2e6;
        font-size: 0.8rem;
    }
    
    .detail-table td {
        padding: 0.4rem 0.5rem;
        border: 1px solid #dee2e6;
        color: #212529;
        font-size: 0.8rem;
    }
    
    .section-title {
        font-weight: 600;
        color: #2c3e50;
        font-size: 0.85rem;
        margin-top: 0.75rem;
        margin-bottom: 0.5rem;
        padding-bottom: 0.35rem;
        border-bottom: 2px solid #4472C4;
    }
    
    .image-preview {
        max-width: 100%;
        height: auto;
        border-radius: 6px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        margin-top: 0.5rem;
    }
    
    .image-label {
        font-weight: 600;
        color: #495057;
        font-size: 0.8rem;
        margin-bottom: 0.4rem;
        display: block;
    }
    
    /* Info text */
    .text-info-count {
        font-size: 0.85rem;
        color: #6c757d;
    }
    
    /* Empty state */
    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
    }
    
    .empty-state i {
        font-size: 3rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }
    
    .empty-state p {
        color: #6c757d;
        font-size: 0.9rem;
    }
    
    /* Info box */
    .info-box {
        background: #e7f3ff;
        border-left: 4px solid #4472C4;
        padding: 0.75rem 1rem;
        border-radius: 4px;
        margin-bottom: 1rem;
    }
    
    .info-box i {
        color: #4472C4;
        margin-right: 0.5rem;
    }
    
    .info-box p {
        margin: 0;
        color: #2c3e50;
        font-size: 0.85rem;
    }
</style>
@endsection

@section('content')
<div class="compact-container">
    
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" style="padding: 0.5rem;">
            <span>&times;</span>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" style="padding: 0.5rem;">
            <span>&times;</span>
        </button>
    </div>
    @endif

   <!-- Filter Card -->
<div class="filter-card">
    <h5><i class="fas fa-filter mr-2"></i>Filter Laporan</h5>
    <form method="GET" action="{{ route('laporan.index') }}" id="filterForm">
        <div class="row g-2 align-items-end">
            <!-- Tanggal Mulai -->
            <div class="col-md-2">
                <label class="form-label"><i class="fas fa-calendar-alt mr-1"></i> Tanggal Mulai <span class="text-danger">*</span></label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" required>
            </div>
            
            <!-- Tanggal Akhir -->
            <div class="col-md-2">
                <label class="form-label"><i class="fas fa-calendar-alt mr-1"></i> Tanggal Akhir <span class="text-danger">*</span></label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" required>
            </div>
            
            <!-- Departemen (hanya untuk role tertentu) -->
            @if(in_array($user->role, ['ADMINISTRATOR', 'SAFETY_PATROLLER', 'SAFETY_ADMIN']))
            <div class="col-md-2">
                <label class="form-label"><i class="fas fa-building mr-1"></i> Departemen</label>
                <select name="department_id" class="form-select">
                    <option value="">-- Semua --</option>
                    @foreach($departemens as $dept)
                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                        {{ $dept->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Buttons -->
            <div class="col-md-6">
                <div class="button-group">
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-search"></i> Tampilkan
                    </button>
                    <a href="{{ route('laporan.index') }}" class="btn-reset">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                    @if(request('start_date') && request('end_date'))
                    <button type="button" class="btn-export" onclick="exportToExcel()">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </button>
                    @endif
                </div>
            </div>
            @else
            <!-- Buttons untuk role tanpa filter departemen -->
            <div class="col-md-8">
                <div class="button-group">
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-search"></i> Tampilkan
                    </button>
                    <a href="{{ route('laporan.index') }}" class="btn-reset">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                    @if(request('start_date') && request('end_date'))
                    <button type="button" class="btn-export" onclick="exportToExcel()">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </button>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </form>
</div>


    @if(!request('start_date') || !request('end_date'))
    <!-- Info Box ketika belum filter -->
    <div class="info-box">
        <i class="fas fa-info-circle"></i>
        <p><strong>Informasi:</strong> Silakan pilih tanggal mulai dan tanggal akhir untuk menampilkan data laporan temuan.</p>
    </div>
    @endif

    <!-- Table Card -->
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Tanggal</th>
                    <th>Patroller</th>
                    @if(in_array($user->role, ['ADMINISTRATOR', 'SAFETY_PATROLLER', 'SAFETY_ADMIN']))
                    <th>Departemen</th>
                    @endif
                    <th>Lokasi</th>
                    <th>Kategori</th>
                    <th style="width: 80px;">Grade</th>
                    <th>PIC</th>
                    <th style="width: 100px;">Status</th>
                    <th style="width: 80px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($findings as $i => $finding)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $finding->finding_date ? $finding->finding_date->format('d/m/Y') : '-' }}</td>
                    <td>{{ $finding->reporter->name ?? '-' }}</td>
                    @if(in_array($user->role, ['ADMINISTRATOR', 'SAFETY_PATROLLER', 'SAFETY_ADMIN']))
                    <td>{{ $finding->departemen->name ?? '-' }}</td>
                    @endif
                    <td>{{ $finding->location }}</td>
                    <td>{{ ucfirst($finding->category->name ?? '-') }}</td>
                    <td><span class="badge bg-info">{{ $finding->grade->code ?? '-' }}</span></td>
                    <td>{{ $finding->pic->name ?? '-' }}</td>
                    <td>
                        @if($finding->status === 'PENDING')
                            <span class="badge-status status-pending">Pending</span>
                        @elseif($finding->status === 'IN_PROGRESS')
                            <span class="badge-status status-in-progress">In Progress</span>
                        @elseif($finding->status === 'COMPLETED')
                            <span class="badge-status status-completed">Completed</span>
                        @else
                            <span class="badge-status status-closed">Closed</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-info" onclick="showDetail({{ $finding->id }})" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ in_array($user->role, ['ADMINISTRATOR', 'SAFETY_PATROLLER', 'SAFETY_ADMIN']) ? '10' : '9' }}" class="text-center">
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>{{ (request('start_date') && request('end_date')) ? 'Tidak ada data laporan pada periode yang dipilih' : 'Pilih tanggal untuk menampilkan data' }}</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if((request('start_date') && request('end_date')) && $findings->count() > 0)
    <div class="mt-2 text-info-count">
        <small><i class="fas fa-info-circle"></i> Total: <strong>{{ $findings->count() }}</strong> temuan ditemukan</small>
    </div>
    @endif
</div>

<!-- Modal Detail -->
<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white"><i class="fas fa-info-circle mr-2"></i>Detail Temuan</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="detailContent">
                <div class="text-center py-5">
                    <i class="fas fa-spinner fa-spin fa-3x text-info"></i>
                    <p class="mt-3">Loading...</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function showDetail(id) {
    $('#modalDetail').modal('show');
    
    // Reset content
    $('#detailContent').html(`
        <div class="text-center py-5">
            <i class="fas fa-spinner fa-spin fa-3x text-info"></i>
            <p class="mt-3">Loading...</p>
        </div>
    `);
    
    // Fetch data
    $.ajax({
        url: `/laporan/${id}`,
        method: 'GET',
        success: function(data) {
            const content = `
                <!-- Informasi Dasar -->
                <div class="section-title">
                    <i class="fas fa-info-circle mr-2"></i>Informasi Dasar
                </div>
                <table class="detail-table table table-bordered table-sm">
                    <tbody>
                        <tr>
                            <th>No. Temuan</th>
                            <td>${data.finding_number || '-'}</td>
                            <th>Tanggal Temuan</th>
                            <td>${formatDate(data.finding_date)}</td>
                        </tr>
                        <tr>
                            <th>Patroller</th>
                            <td>${data.reporter?.name || '-'}</td>
                            <th>Departemen</th>
                            <td>${data.departemen?.name || '-'}</td>
                        </tr>
                        <tr>
                            <th>Lokasi</th>
                            <td>${data.location || '-'}</td>
                            <th>Section</th>
                            <td>${data.section || '-'}</td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>${data.category?.name || '-'}</td>
                            <th>Grade</th>
                            <td><span class="badge badge-info">${data.grade?.code || '-'}</span></td>
                        </tr>
                    </tbody>
                </table>
                
                <!-- Penanganan -->
                <div class="section-title">
                    <i class="fas fa-users mr-2"></i>Penanganan
                </div>
                <table class="detail-table table table-bordered table-sm">
                    <tbody>
                        <tr>
                            <th>PIC</th>
                            <td>${data.pic?.name || '-'}</td>
                            <th>Manager</th>
                            <td>${data.manager?.name || '-'}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>${getStatusBadge(data.status)}</td>
                            <th>Target Selesai</th>
                            <td>${formatDate(data.target_date)}</td>
                        </tr>
                    </tbody>
                </table>
                
                <!-- Deskripsi -->
                <div class="section-title">
                    <i class="fas fa-file-alt mr-2"></i>Deskripsi
                </div>
                <table class="detail-table table table-bordered table-sm">
                    <tbody>
                        <tr>
                            <th>Deskripsi Temuan</th>
                            <td colspan="3">${data.description || '-'}</td>
                        </tr>
                        ${data.counter_action ? `
                        <tr>
                            <th>Counter Action</th>
                            <td colspan="3">${data.counter_action}</td>
                        </tr>
                        ` : ''}
                    </tbody>
                </table>
                
                <!-- Dokumentasi -->
                ${data.image_before || data.image_after ? `
                <div class="section-title">
                    <i class="fas fa-camera mr-2"></i>Dokumentasi
                </div>
                <div class="row">
                    ${data.image_before ? `
                        <div class="col-md-6 mb-3">
                            <span class="image-label">Foto Sebelum:</span>
                            <img src="/storage/${data.image_before}" class="image-preview" alt="Before">
                        </div>
                    ` : ''}
                    
                    ${data.image_after ? `
                        <div class="col-md-6 mb-3">
                            <span class="image-label">Foto Sesudah:</span>
                            <img src="/storage/${data.image_after}" class="image-preview" alt="After">
                        </div>
                    ` : ''}
                </div>
                ` : ''}
                
                <!-- Status Penyelesaian -->
                ${data.closed_at ? `
                <div class="section-title">
                    <i class="fas fa-check-circle mr-2"></i>Status Penyelesaian
                </div>
                <table class="detail-table table table-bordered table-sm">
                    <tbody>
                        <tr>
                            <th>Diverifikasi oleh</th>
                            <td>${data.verified_by?.name || '-'}</td>
                            <th>Tanggal Verifikasi</th>
                            <td>${formatDate(data.verified_at)}</td>
                        </tr>
                        <tr>
                            <th>Ditutup oleh</th>
                            <td>${data.closed_by?.name || '-'}</td>
                            <th>Tanggal Close</th>
                            <td>${formatDate(data.closed_at)}</td>
                        </tr>
                        ${data.close_note ? `
                        <tr>
                            <th>Catatan Close</th>
                            <td colspan="3">${data.close_note}</td>
                        </tr>
                        ` : ''}
                    </tbody>
                </table>
                ` : ''}
            `;
            
            $('#detailContent').html(content);
        },
        error: function() {
            $('#detailContent').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Gagal memuat detail temuan. Silakan coba lagi.
                </div>
            `);
        }
    });
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    return `${day}/${month}/${year} ${hours}:${minutes}`;
}

function getStatusBadge(status) {
    const badges = {
        'PENDING': '<span class="badge-status status-pending">Pending</span>',
        'IN_PROGRESS': '<span class="badge-status status-in-progress">In Progress</span>',
        'COMPLETED': '<span class="badge-status status-completed">Completed</span>',
        'CLOSED': '<span class="badge-status status-closed">Closed</span>'
    };
    return badges[status] || status;
}

function exportToExcel() {
    // Ambil parameter filter dari form
    const form = document.getElementById('filterForm');
    const params = new URLSearchParams(new FormData(form)).toString();
    
    // Redirect ke route export dengan parameter
    window.location.href = `/laporan/export?${params}`;
}
</script>
@endsection

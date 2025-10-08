@extends('layouts.app')

@section('title', 'Audit Trail')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history mr-2"></i>
                        Audit Trail - Log Aktivitas Sistem
                    </h3>
                </div>
                
                <!-- Filter Form -->
                <div class="card-body border-bottom">
                    <form method="GET" action="{{ route('admin.audit-trail.index') }}" class="row g-3">
                        <div class="col-md-2">
                            <label for="action" class="form-label">Aksi</label>
                            <select name="action" id="action" class="form-select">
                                <option value="">Semua Aksi</option>
                                @foreach($actions as $action)
                                    <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                        {{ ucfirst($action) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <label for="user_id" class="form-label">User</label>
                            <select name="user_id" id="user_id" class="form-select">
                                <option value="">Semua User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <label for="model_type" class="form-label">Model</label>
                            <select name="model_type" id="model_type" class="form-select">
                                <option value="">Semua Model</option>
                                @foreach($modelTypes as $modelType)
                                    <option value="App\Models\{{ $modelType }}" {{ request('model_type') == "App\Models\\$modelType" ? 'selected' : '' }}>
                                        {{ $modelType }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <label for="date_from" class="form-label">Dari Tanggal</label>
                            <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        
                        <div class="col-md-2">
                            <label for="date_to" class="form-label">Sampai Tanggal</label>
                            <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="btn-group w-100">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                                <a href="{{ route('admin.audit-trail.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body">
                    @if($auditTrails->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Waktu</th>
                                        <th>User</th>
                                        <th>Aksi</th>
                                        <th>Model</th>
                                        <th>Deskripsi</th>
                                        <th>IP Address</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($auditTrails as $trail)
                                        <tr>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $trail->created_at->format('d/m/Y H:i:s') }}
                                                </small>
                                            </td>
                                            <td>
                                                <strong>{{ $trail->user->name ?? 'System' }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $trail->user->email ?? '-' }}</small>
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    @if($trail->action == 'login') bg-success
                                                    @elseif($trail->action == 'logout') bg-warning
                                                    @elseif($trail->action == 'create') bg-primary
                                                    @elseif($trail->action == 'update_status') bg-info
                                                    @elseif($trail->action == 'download') bg-secondary
                                                    @else bg-dark
                                                    @endif
                                                ">
                                                    {{ ucfirst($trail->action) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($trail->model_type)
                                                    <span class="badge bg-light text-dark">
                                                        {{ class_basename($trail->model_type) }}
                                                    </span>
                                                    @if($trail->model_id)
                                                        <br><small class="text-muted">ID: {{ $trail->model_id }}</small>
                                                    @endif
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 300px;" title="{{ $trail->description }}">
                                                    {{ $trail->description ?? '-' }}
                                                </div>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $trail->ip_address ?? '-' }}</small>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.audit-trail.show', $trail->id) }}" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <small class="text-muted">
                                    Menampilkan {{ $auditTrails->firstItem() }} - {{ $auditTrails->lastItem() }} 
                                    dari {{ $auditTrails->total() }} data
                                </small>
                            </div>
                            <div>
                                {{ $auditTrails->appends(request()->query())->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak ada data audit trail</h5>
                            <p class="text-muted">Belum ada aktivitas yang tercatat dalam sistem.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.875rem;
}

.badge {
    font-size: 0.75rem;
}

.btn-group .btn {
    border-radius: 0;
}

.btn-group .btn:first-child {
    border-top-left-radius: 0.375rem;
    border-bottom-left-radius: 0.375rem;
}

.btn-group .btn:last-child {
    border-top-right-radius: 0.375rem;
    border-bottom-right-radius: 0.375rem;
}
</style>
@endsection
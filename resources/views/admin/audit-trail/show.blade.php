@extends('layouts.app')

@section('title', 'Detail Audit Trail')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle mr-2"></i>
                            Detail Audit Trail
                        </h3>
                        <a href="{{ route('admin.audit-trail.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-6">
                            <div class="card border-0 bg-light">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Informasi Dasar</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless mb-0">
                                        <tr>
                                            <td class="fw-bold" style="width: 40%;">ID:</td>
                                            <td>{{ $auditTrail->id }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Waktu:</td>
                                            <td>{{ $auditTrail->created_at->format('d/m/Y H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Aksi:</td>
                                            <td>
                                                <span class="badge 
                                                    @if($auditTrail->action == 'login') bg-success
                                                    @elseif($auditTrail->action == 'logout') bg-warning
                                                    @elseif($auditTrail->action == 'create') bg-primary
                                                    @elseif($auditTrail->action == 'update_status') bg-info
                                                    @elseif($auditTrail->action == 'download') bg-secondary
                                                    @else bg-dark
                                                    @endif
                                                ">
                                                    {{ ucfirst($auditTrail->action) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">User:</td>
                                            <td>
                                                @if($auditTrail->user)
                                                    <strong>{{ $auditTrail->user->name }}</strong><br>
                                                    <small class="text-muted">{{ $auditTrail->user->email }}</small>
                                                @else
                                                    <span class="text-muted">System</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">IP Address:</td>
                                            <td>{{ $auditTrail->ip_address ?? '-' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Model Information -->
                        <div class="col-md-6">
                            <div class="card border-0 bg-light">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0">Informasi Model</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless mb-0">
                                        <tr>
                                            <td class="fw-bold" style="width: 40%;">Model Type:</td>
                                            <td>
                                                @if($auditTrail->model_type)
                                                    <span class="badge bg-light text-dark">
                                                        {{ class_basename($auditTrail->model_type) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Model ID:</td>
                                            <td>{{ $auditTrail->model_id ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">User Agent:</td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $auditTrail->user_agent ? Str::limit($auditTrail->user_agent, 50) : '-' }}
                                                </small>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($auditTrail->description)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card border-0 bg-light">
                                    <div class="card-header bg-success text-white">
                                        <h5 class="mb-0">Deskripsi</h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-0">{{ $auditTrail->description }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Old Values -->
                    @if($auditTrail->old_values && count($auditTrail->old_values) > 0)
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-header bg-warning text-dark">
                                        <h5 class="mb-0">Nilai Sebelumnya</h5>
                                    </div>
                                    <div class="card-body">
                                        <pre class="bg-white p-3 rounded border"><code>{{ json_encode($auditTrail->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                                    </div>
                                </div>
                            </div>

                            <!-- New Values -->
                            @if($auditTrail->new_values && count($auditTrail->new_values) > 0)
                                <div class="col-md-6">
                                    <div class="card border-0 bg-light">
                                        <div class="card-header bg-success text-white">
                                            <h5 class="mb-0">Nilai Baru</h5>
                                        </div>
                                        <div class="card-body">
                                            <pre class="bg-white p-3 rounded border"><code>{{ json_encode($auditTrail->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @elseif($auditTrail->new_values && count($auditTrail->new_values) > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card border-0 bg-light">
                                    <div class="card-header bg-success text-white">
                                        <h5 class="mb-0">Data</h5>
                                    </div>
                                    <div class="card-body">
                                        <pre class="bg-white p-3 rounded border"><code>{{ json_encode($auditTrail->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Full User Agent -->
                    @if($auditTrail->user_agent)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card border-0 bg-light">
                                    <div class="card-header bg-secondary text-white">
                                        <h5 class="mb-0">User Agent Lengkap</h5>
                                    </div>
                                    <div class="card-body">
                                        <small class="text-muted">{{ $auditTrail->user_agent }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card-header h5 {
    font-size: 1rem;
    font-weight: 600;
}

pre {
    font-size: 0.875rem;
    max-height: 300px;
    overflow-y: auto;
}

.badge {
    font-size: 0.75rem;
}

.table td {
    padding: 0.5rem 0;
    vertical-align: top;
}

.fw-bold {
    font-weight: 600 !important;
}
</style>
@endsection
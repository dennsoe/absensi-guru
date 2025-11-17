@extends('layouts.app')

@section('title', 'Activity Log')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">
                    <i class="bi bi-activity text-primary me-2"></i>
                    Activity Log
                </h2>
                <p class="text-muted mb-0">Monitoring aktivitas sistem dan pengguna</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>

        <!-- Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.activity-log') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" name="start_date"
                                value="{{ request('start_date', Carbon\Carbon::today()->subDays(7)->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" name="end_date"
                                value="{{ request('end_date', Carbon\Carbon::today()->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tipe</label>
                            <select class="form-select" name="type">
                                <option value="">Semua</option>
                                <option value="login" {{ request('type') == 'login' ? 'selected' : '' }}>Login</option>
                                <option value="create" {{ request('type') == 'create' ? 'selected' : '' }}>Create</option>
                                <option value="update" {{ request('type') == 'update' ? 'selected' : '' }}>Update</option>
                                <option value="delete" {{ request('type') == 'delete' ? 'selected' : '' }}>Delete</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Role</label>
                            <select class="form-select" name="role">
                                <option value="">Semua</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="guru" {{ request('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                                <option value="kepala_sekolah" {{ request('role') == 'kepala_sekolah' ? 'selected' : '' }}>
                                    Kepala Sekolah</option>
                                <option value="kurikulum" {{ request('role') == 'kurikulum' ? 'selected' : '' }}>Kurikulum
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search me-1"></i>Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-primary">
                    <div class="card-body text-center">
                        <i class="bi bi-box-arrow-in-right display-4 text-primary"></i>
                        <h3 class="mt-2 mb-0">{{ $stats['login'] ?? 0 }}</h3>
                        <p class="text-muted mb-0">Login</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-success">
                    <div class="card-body text-center">
                        <i class="bi bi-plus-circle display-4 text-success"></i>
                        <h3 class="mt-2 mb-0">{{ $stats['create'] ?? 0 }}</h3>
                        <p class="text-muted mb-0">Create</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-warning">
                    <div class="card-body text-center">
                        <i class="bi bi-pencil-square display-4 text-warning"></i>
                        <h3 class="mt-2 mb-0">{{ $stats['update'] ?? 0 }}</h3>
                        <p class="text-muted mb-0">Update</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-danger">
                    <div class="card-body text-center">
                        <i class="bi bi-trash display-4 text-danger"></i>
                        <h3 class="mt-2 mb-0">{{ $stats['delete'] ?? 0 }}</h3>
                        <p class="text-muted mb-0">Delete</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Timeline -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>Riwayat Aktivitas
                </h5>
            </div>
            <div class="card-body">
                @if ($activities->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <p class="text-muted mt-3">Tidak ada aktivitas</p>
                    </div>
                @else
                    <div class="timeline">
                        @foreach ($activities as $activity)
                            <div class="timeline-item mb-4">
                                <div class="row">
                                    <div class="col-md-2 text-md-end">
                                        <small class="text-muted">
                                            {{ $activity->created_at->format('d M Y') }}<br>
                                            {{ $activity->created_at->format('H:i:s') }}
                                        </small>
                                    </div>
                                    <div class="col-md-1 text-center">
                                        <div
                                            class="timeline-badge
                                        @if ($activity->type == 'login') bg-primary
                                        @elseif($activity->type == 'create') bg-success
                                        @elseif($activity->type == 'update') bg-warning
                                        @elseif($activity->type == 'delete') bg-danger
                                        @else bg-secondary @endif">
                                            <i
                                                class="bi
                                            @if ($activity->type == 'login') bi-box-arrow-in-right
                                            @elseif($activity->type == 'create') bi-plus-circle
                                            @elseif($activity->type == 'update') bi-pencil
                                            @elseif($activity->type == 'delete') bi-trash
                                            @else bi-circle @endif">
                                            </i>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <div>
                                                        <h6 class="mb-1">
                                                            <span
                                                                class="badge
                                                            @if ($activity->type == 'login') bg-primary
                                                            @elseif($activity->type == 'create') bg-success
                                                            @elseif($activity->type == 'update') bg-warning text-dark
                                                            @elseif($activity->type == 'delete') bg-danger
                                                            @else bg-secondary @endif">
                                                                {{ strtoupper($activity->type) }}
                                                            </span>
                                                            {{ $activity->description }}
                                                        </h6>
                                                        <p class="text-muted mb-1">
                                                            <i class="bi bi-person me-1"></i>
                                                            <strong>{{ $activity->user->nama ?? $activity->user->username }}</strong>
                                                            <span class="badge bg-secondary ms-2">
                                                                {{ ucfirst(str_replace('_', ' ', $activity->user->role)) }}
                                                            </span>
                                                        </p>
                                                    </div>
                                                </div>

                                                @if ($activity->ip_address || $activity->user_agent)
                                                    <small class="text-muted">
                                                        @if ($activity->ip_address)
                                                            <i class="bi bi-globe me-1"></i>IP: {{ $activity->ip_address }}
                                                        @endif
                                                        @if ($activity->user_agent)
                                                            <br><i class="bi bi-device-hdd me-1"></i>
                                                            {{ Str::limit($activity->user_agent, 80) }}
                                                        @endif
                                                    </small>
                                                @endif

                                                @if ($activity->details)
                                                    <div class="mt-2">
                                                        <button class="btn btn-sm btn-outline-secondary" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#details-{{ $activity->id }}">
                                                            <i class="bi bi-info-circle me-1"></i>Detail
                                                        </button>
                                                        <div class="collapse mt-2" id="details-{{ $activity->id }}">
                                                            <pre class="bg-light p-2 rounded small mb-0">{{ json_encode(json_decode($activity->details), JSON_PRETTY_PRINT) }}</pre>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Menampilkan {{ $activities->firstItem() }} - {{ $activities->lastItem() }}
                            dari {{ $activities->total() }} aktivitas
                        </div>
                        <div>
                            {{ $activities->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .timeline-badge {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            margin: 0 auto;
        }

        .timeline-item:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 50%;
            top: 60px;
            transform: translateX(-50%);
            width: 2px;
            height: calc(100% - 60px);
            background: #dee2e6;
        }

        @media (max-width: 767px) {
            .timeline-item::after {
                display: none;
            }
        }
    </style>
@endpush

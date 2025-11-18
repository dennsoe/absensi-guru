@extends('layouts.app')

@section('title', 'Laporan Keterlambatan')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('admin.laporan.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <h2 class="mb-1">Laporan Keterlambatan</h2>
                        <p class="text-muted">Data keterlambatan guru dalam periode tertentu</p>
                    </div>
                    <div>
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.laporan.export-pdf.keterlambatan', request()->query()) }}"
                                class="btn btn-danger" target="_blank" title="Export ke PDF">
                                <i class="bi bi-file-pdf"></i> Export PDF
                            </a>
                            <a href="{{ route('admin.laporan.export-excel.keterlambatan', request()->query()) }}"
                                class="btn btn-success" title="Export ke Excel">
                                <i class="bi bi-file-excel"></i> Export Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-funnel"></i> Filter Laporan</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.laporan.keterlambatan') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control"
                                value="{{ request('tanggal_mulai', now()->startOfMonth()->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control"
                                value="{{ request('tanggal_selesai', now()->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Guru</label>
                            <select name="guru_id" class="form-select">
                                <option value="">Semua Guru</option>
                                @foreach ($guru_list ?? [] as $guru)
                                    <option value="{{ $guru->id }}"
                                        {{ request('guru_id') == $guru->id ? 'selected' : '' }}>
                                        {{ $guru->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary d-block w-100">
                                <i class="bi bi-search"></i> Tampilkan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Total Keterlambatan</h6>
                        <h3 class="mb-0 text-warning">{{ $total_terlambat ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Guru Terlambat</h6>
                        <h3 class="mb-0">{{ $jumlah_guru ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Rata-rata Terlambat</h6>
                        <h3 class="mb-0">{{ $rata_rata_terlambat ?? 0 }} menit</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Terlambat >30 menit</h6>
                        <h3 class="mb-0 text-danger">{{ $terlambat_parah ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Data Keterlambatan</h5>
            </div>
            <div class="card-body">
                @if (($keterlambatan_list ?? collect())->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Nama Guru</th>
                                    <th>Kelas</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Jam Seharusnya</th>
                                    <th>Jam Datang</th>
                                    <th>Durasi Terlambat</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($keterlambatan_list as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</td>
                                        <td>
                                            <strong>{{ $item->guru->nama }}</strong>
                                            <br><small class="text-muted">{{ $item->guru->nip }}</small>
                                        </td>
                                        <td>{{ $item->jadwal->kelas->nama_kelas }}</td>
                                        <td>{{ $item->jadwal->mataPelajaran->nama_mapel }}</td>
                                        <td>{{ $item->jadwal->jam_mulai }}</td>
                                        <td>{{ $item->jam_masuk ? \Carbon\Carbon::parse($item->jam_masuk)->format('H:i') : '-' }}
                                        </td>
                                        <td>
                                            @php
                                                $durasi = $item->waktu_terlambat_menit ?? 0;
                                            @endphp
                                            <span class="badge {{ $durasi > 30 ? 'bg-danger' : 'bg-warning' }}">
                                                {{ $durasi }} menit
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning">Terlambat</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $keterlambatan_list->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-check-circle" style="font-size: 3rem; color: #10b981;"></i>
                        <p class="text-muted mt-2">Tidak ada data keterlambatan untuk periode ini</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Top 10 Guru Terlambat -->
        @if (($top_guru_terlambat ?? collect())->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Top 10 Guru Sering Terlambat</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Nama Guru</th>
                                    <th>NIP</th>
                                    <th class="text-center">Jumlah Keterlambatan</th>
                                    <th class="text-center">Total Durasi</th>
                                    <th class="text-center">Rata-rata Durasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($top_guru_terlambat as $index => $data)
                                    <tr>
                                        <td>
                                            @if ($index < 3)
                                                <span class="badge bg-danger">{{ $index + 1 }}</span>
                                            @else
                                                {{ $index + 1 }}
                                            @endif
                                        </td>
                                        <td><strong>{{ $data->nama }}</strong></td>
                                        <td>{{ $data->nip }}</td>
                                        <td class="text-center">{{ $data->jumlah_terlambat }}</td>
                                        <td class="text-center">{{ $data->total_durasi }} menit</td>
                                        <td class="text-center">{{ round($data->rata_rata_durasi) }} menit</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

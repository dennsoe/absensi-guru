@extends('layouts.base')

@section('title', '503 - Maintenance Mode')

@section('content')
    <div class="min-vh-100 d-flex align-items-center justify-content-center" style="background: var(--color-gray-50);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 text-center">

                    <!-- Maintenance Illustration -->
                    <div class="mb-5">
                        <svg width="200" height="200" viewBox="0 0 200 200" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <circle cx="100" cy="100" r="90" fill="var(--color-info-light)" opacity="0.2" />
                            <path
                                d="M100 50C72.4 50 50 72.4 50 100C50 127.6 72.4 150 100 150C127.6 150 150 127.6 150 100C150 72.4 127.6 50 100 50Z"
                                stroke="var(--color-info)" stroke-width="4" fill="none" />
                            <path d="M85 85L100 70L115 85M100 70V120" stroke="var(--color-info)" stroke-width="6"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <circle cx="100" cy="135" r="5" fill="var(--color-info)" />
                        </svg>
                    </div>

                    <!-- Maintenance Message -->
                    <h1 class="display-4 fw-bold mb-3" style="color: var(--color-gray-900);">
                        Sedang Maintenance
                    </h1>

                    <p class="lead text-muted mb-4">
                        Aplikasi sedang dalam proses maintenance untuk meningkatkan kualitas layanan. Mohon coba beberapa
                        saat lagi.
                    </p>

                    <!-- Status Badge -->
                    <div class="alert alert-info d-inline-flex align-items-center gap-2 mb-4">
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span>System Under Maintenance</span>
                    </div>

                    <!-- Estimated Time -->
                    @if (isset($retryAfter))
                        <p class="text-muted mb-4">
                            <i class="bi bi-clock me-1"></i>
                            Estimasi selesai: <strong>{{ $retryAfter }}</strong>
                        </p>
                    @endif

                    <!-- Contact Info -->
                    <div class="mt-5 pt-4 border-top" style="border-color: var(--color-gray-200) !important;">
                        <p class="text-muted small mb-2">
                            <i class="bi bi-telephone me-1"></i>
                            Untuk informasi lebih lanjut, hubungi administrator sistem
                        </p>
                        <p class="text-muted small mb-0">
                            <i class="bi bi-envelope me-1"></i>
                            Email: admin@smknkasomalang.sch.id
                        </p>
                    </div>

                    <!-- Auto Refresh Button -->
                    <button onclick="location.reload()" class="btn btn-primary btn-lg mt-4">
                        <i class="bi bi-arrow-clockwise me-2"></i>
                        Muat Ulang Halaman
                    </button>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Auto refresh every 60 seconds
            setTimeout(function() {
                location.reload();
            }, 60000);
        </script>
    @endpush
@endsection

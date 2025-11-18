@extends('layouts.base')

@section('title', '403 - Akses Ditolak')

@section('content')
    <div class="min-vh-100 d-flex align-items-center justify-content-center" style="background: var(--color-gray-50);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 text-center">

                    <!-- Error Illustration -->
                    <div class="mb-5">
                        <svg width="200" height="200" viewBox="0 0 200 200" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <circle cx="100" cy="100" r="90" fill="var(--color-danger-light)" opacity="0.2" />
                            <path
                                d="M100 50C72.4 50 50 72.4 50 100C50 127.6 72.4 150 100 150C127.6 150 150 127.6 150 100C150 72.4 127.6 50 100 50ZM100 140C77.9 140 60 122.1 60 100C60 77.9 77.9 60 100 60C122.1 60 140 77.9 140 100C140 122.1 122.1 140 100 140Z"
                                fill="var(--color-danger)" />
                            <path d="M130 70L70 130M70 70L130 130" stroke="var(--color-danger)" stroke-width="8"
                                stroke-linecap="round" />
                        </svg>
                    </div>

                    <!-- Error Message -->
                    <h1 class="display-4 fw-bold mb-3" style="color: var(--color-gray-900);">
                        Akses Ditolak
                    </h1>

                    <p class="lead text-muted mb-4">
                        Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. Silakan hubungi administrator jika Anda
                        merasa ini adalah kesalahan.
                    </p>

                    <!-- Error Code -->
                    <div class="alert alert-danger d-inline-flex align-items-center gap-2 mb-4">
                        <i class="bi bi-shield-x"></i>
                        <span>Error Code: 403 - Forbidden</span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="javascript:history.back()" class="btn btn-outline-primary btn-lg">
                            <i class="bi bi-arrow-left me-2"></i>
                            Kembali
                        </a>

                        @auth
                            @if (auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-lg">
                                    <i class="bi bi-house-door me-2"></i>
                                    Ke Dashboard
                                </a>
                            @elseif(auth()->user()->role === 'guru')
                                <a href="{{ route('guru.dashboard') }}" class="btn btn-primary btn-lg">
                                    <i class="bi bi-house-door me-2"></i>
                                    Ke Dashboard
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                                    <i class="bi bi-house-door me-2"></i>
                                    Ke Dashboard
                                </a>
                            @endif
                        @endauth
                    </div>

                    <!-- Help Text -->
                    <div class="mt-5 pt-4 border-top" style="border-color: var(--color-gray-200) !important;">
                        <p class="text-muted small mb-2">
                            <i class="bi bi-info-circle me-1"></i>
                            Jika Anda yakin seharusnya memiliki akses, hubungi administrator sistem
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

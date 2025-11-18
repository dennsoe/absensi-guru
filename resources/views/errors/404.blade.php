@extends('layouts.base')

@section('title', '404 - Halaman Tidak Ditemukan')

@section('content')
    <div class="min-vh-100 d-flex align-items-center justify-content-center" style="background: var(--color-gray-50);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 text-center">

                    <!-- Error Illustration -->
                    <div class="mb-5">
                        <svg width="200" height="200" viewBox="0 0 200 200" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <circle cx="100" cy="100" r="90" fill="var(--color-primary-light)" opacity="0.2" />
                            <text x="100" y="120" text-anchor="middle" font-size="80" font-weight="bold"
                                fill="var(--color-primary)">404</text>
                        </svg>
                    </div>

                    <!-- Error Message -->
                    <h1 class="display-4 fw-bold mb-3" style="color: var(--color-gray-900);">
                        Halaman Tidak Ditemukan
                    </h1>

                    <p class="lead text-muted mb-4">
                        Maaf, halaman yang Anda cari tidak dapat ditemukan. Halaman mungkin telah dipindahkan atau dihapus.
                    </p>

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
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                Login
                            </a>
                        @endauth
                    </div>

                    <!-- Help Text -->
                    <div class="mt-5 pt-4 border-top" style="border-color: var(--color-gray-200) !important;">
                        <p class="text-muted small mb-2">
                            <i class="bi bi-info-circle me-1"></i>
                            Butuh bantuan? Hubungi administrator
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

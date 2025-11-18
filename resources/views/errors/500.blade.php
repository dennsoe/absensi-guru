@extends('layouts.base')

@section('title', '500 - Kesalahan Server')

@section('content')
    <div class="min-vh-100 d-flex align-items-center justify-content-center" style="background: var(--color-gray-50);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 text-center">

                    <!-- Error Illustration -->
                    <div class="mb-5">
                        <svg width="200" height="200" viewBox="0 0 200 200" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <circle cx="100" cy="100" r="90" fill="var(--color-warning-light)" opacity="0.2" />
                            <text x="100" y="120" text-anchor="middle" font-size="80" font-weight="bold"
                                fill="var(--color-warning)">500</text>
                        </svg>
                    </div>

                    <!-- Error Message -->
                    <h1 class="display-4 fw-bold mb-3" style="color: var(--color-gray-900);">
                        Kesalahan Server
                    </h1>

                    <p class="lead text-muted mb-4">
                        Maaf, terjadi kesalahan pada server. Tim kami telah menerima notifikasi dan sedang menangani masalah
                        ini.
                    </p>

                    <!-- Error Code -->
                    <div class="alert alert-warning d-inline-flex align-items-center gap-2 mb-4">
                        <i class="bi bi-exclamation-triangle"></i>
                        <span>Error Code: 500 - Internal Server Error</span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="javascript:location.reload()" class="btn btn-outline-primary btn-lg">
                            <i class="bi bi-arrow-clockwise me-2"></i>
                            Muat Ulang
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

                    <!-- Technical Details (Only in Debug Mode) -->
                    @if (config('app.debug') && isset($exception))
                        <div class="mt-5 pt-4 border-top text-start"
                            style="border-color: var(--color-gray-200) !important;">
                            <h5 class="mb-3">Technical Details (Debug Mode):</h5>
                            <div class="alert alert-secondary">
                                <pre class="mb-0" style="font-size: 12px; max-height: 200px; overflow-y: auto;">{{ $exception->getMessage() }}</pre>
                            </div>
                        </div>
                    @else
                        <!-- Help Text -->
                        <div class="mt-5 pt-4 border-top" style="border-color: var(--color-gray-200) !important;">
                            <p class="text-muted small mb-2">
                                <i class="bi bi-info-circle me-1"></i>
                                Jika masalah berlanjut, silakan hubungi administrator
                            </p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection

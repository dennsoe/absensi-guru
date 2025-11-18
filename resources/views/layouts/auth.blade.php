@extends('layouts.base')

@section('body-class', 'login-page')

@section('content')
    <div class="min-vh-100 d-flex align-items-center justify-content-center"
        style="background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-hover) 100%);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-6">

                    <!-- Login Card -->
                    <div class="card shadow-lg border-0" style="border-radius: var(--radius-xl); overflow: hidden;">

                        <!-- Card Header with Branding -->
                        <div class="card-header text-center py-5"
                            style="background: var(--color-white); border-bottom: 1px solid var(--color-gray-200);">
                            <img src="{{ asset('assets/images/logonekas.png') }}" alt="Logo SMKN Kasomalang" class="mb-3"
                                style="height: 80px; width: auto;">
                            <h3 class="mb-2" style="color: var(--color-gray-900); font-weight: var(--font-weight-bold);">
                                SIAG NEKAS
                            </h3>
                            <p class="text-muted mb-0" style="font-size: var(--font-size-sm);">
                                Sistem Informasi Absensi Guru<br>
                                <strong>SMK Negeri Kasomalang</strong>
                            </p>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body p-5">
                            @yield('auth-content')
                        </div>

                        <!-- Card Footer -->
                        <div class="card-footer text-center py-4"
                            style="background: var(--color-gray-50); border-top: 1px solid var(--color-gray-200);">
                            <p class="text-muted mb-0" style="font-size: var(--font-size-xs);">
                                &copy; {{ date('Y') }} SMK Negeri Kasomalang. All rights reserved.
                            </p>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .login-page {
                background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-hover) 100%);
            }

            .form-control:focus {
                border-color: var(--color-primary);
                box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.15);
            }

            .btn-login {
                padding: var(--spacing-4);
                font-weight: var(--font-weight-semibold);
                font-size: var(--font-size-base);
            }
        </style>
    @endpush

@endsection

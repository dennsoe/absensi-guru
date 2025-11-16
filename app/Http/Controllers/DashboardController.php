<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Redirect ke dashboard berdasarkan role
     */
    public function index()
    {
        $user = Auth::user();

        return match($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'guru' => redirect()->route('guru.dashboard'),
            'guru_piket' => redirect()->route('piket.dashboard'),
            'kepala_sekolah' => redirect()->route('kepsek.dashboard'),
            'kurikulum' => redirect()->route('kurikulum.dashboard'),
            'ketua_kelas' => redirect()->route('guru.dashboard'), // Ketua kelas sama dengan guru
            default => abort(403, 'Role tidak dikenali'),
        };
    }
}

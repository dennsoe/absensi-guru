<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User, Guru, Absensi, JadwalMengajar, Kelas};
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Dashboard Admin
     */
    public function dashboard()
    {
        $data = [
            'total_guru' => Guru::count(),
            'total_kelas' => Kelas::count(),
            'total_jadwal' => JadwalMengajar::where('status', 'aktif')->count(),
            'guru_hadir_hari_ini' => Absensi::whereDate('tanggal', today())
                                            ->where('status_kehadiran', 'hadir')
                                            ->count(),
            'guru_terlambat_hari_ini' => Absensi::whereDate('tanggal', today())
                                                ->where('status_keterlambatan', 'terlambat')
                                                ->count(),
            'guru_izin_hari_ini' => Absensi::whereDate('tanggal', today())
                                           ->whereIn('status_kehadiran', ['izin', 'sakit', 'dinas_luar'])
                                           ->count(),
        ];

        return view('admin.dashboard', $data);
    }

    /**
     * Kelola User
     */
    public function users()
    {
        $users = User::with('guru')->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        $gurus = Guru::whereDoesntHave('user')->get();
        return view('admin.users.create', compact('gurus'));
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users,username|max:50',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,guru,ketua_kelas,guru_piket,kepala_sekolah,kurikulum',
            'guru_id' => 'nullable|exists:guru,id',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return redirect()->route('admin.users')->with('success', 'User berhasil ditambahkan.');
    }

    public function editUser(User $user)
    {
        $gurus = Guru::whereDoesntHave('user')
                     ->orWhere('id', $user->guru_id)
                     ->get();
        return view('admin.users.edit', compact('user', 'gurus'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,guru,ketua_kelas,guru_piket,kepala_sekolah,kurikulum',
            'guru_id' => 'nullable|exists:guru,id',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users')->with('success', 'User berhasil diupdate.');
    }

    public function destroyUser(User $user)
    {
        // Cek jika user adalah admin terakhir
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Tidak dapat menghapus admin terakhir.');
        }

        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User berhasil dihapus.');
    }
}

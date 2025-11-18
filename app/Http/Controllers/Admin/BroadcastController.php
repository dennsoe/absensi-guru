<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{BroadcastMessage, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class BroadcastController extends Controller
{
    /**
     * Display broadcast page
     */
    public function index()
    {
        $broadcastHistory = BroadcastMessage::latest()->paginate(10);

        // User statistics
        $totalUsers = User::count();
        $totalAdmin = User::where('role', 'admin')->count();
        $totalGuru = User::where('role', 'guru')->count();
        $totalKetuaKelas = User::where('role', 'ketua_kelas')->count();
        $totalGuruPiket = User::whereHas('guruPiket')->count();

        return view('admin.broadcast.index', [
            'broadcastHistory' => $broadcastHistory,
            'totalUsers' => $totalUsers,
            'totalAdmin' => $totalAdmin,
            'totalGuru' => $totalGuru,
            'totalKetuaKelas' => $totalKetuaKelas,
            'totalGuruPiket' => $totalGuruPiket,
        ]);
    }

    /**
     * Send broadcast message
     */
    public function send(Request $request)
    {
        $request->validate([
            'target' => 'required|in:all,admin,guru,ketua_kelas,guru_piket',
            'title' => 'required|string|max:200',
            'message' => 'required|string|max:500',
            'priority' => 'nullable|in:normal,high,urgent',
        ]);

        try {
            // Get target users
            $users = $this->getTargetUsers($request->target);

            if ($users->isEmpty()) {
                return redirect()->back()
                    ->with('error', 'Tidak ada pengguna yang sesuai dengan target');
            }

            // Save broadcast message
            $broadcast = BroadcastMessage::create([
                'target' => $request->target,
                'title' => $request->title,
                'message' => $request->message,
                'priority' => $request->priority ?? 'normal',
                'send_email' => $request->has('send_email'),
                'send_notification' => $request->has('send_notification'),
                'sent_at' => Carbon::now(),
                'sent_by' => auth()->id(),
                'recipient_count' => $users->count(),
            ]);

            // Send notifications
            if ($request->has('send_notification')) {
                // TODO: Implement push notification logic
                // foreach ($users as $user) {
                //     Notification::send($user, new BroadcastNotification($broadcast));
                // }
            }

            // Send emails
            if ($request->has('send_email')) {
                foreach ($users as $user) {
                    if ($user->email) {
                        try {
                            // TODO: Implement email sending logic
                            // Mail::to($user->email)->send(new BroadcastMail($broadcast));
                        } catch (\Exception $e) {
                            // Log email error but continue
                            \Log::error('Failed to send broadcast email to ' . $user->email);
                        }
                    }
                }
            }

            return redirect()->route('admin.broadcast.index')
                ->with('success', "Broadcast berhasil dikirim ke {$users->count()} pengguna");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengirim broadcast: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Get target users based on selection
     */
    private function getTargetUsers($target)
    {
        switch ($target) {
            case 'all':
                return User::all();

            case 'admin':
                return User::where('role', 'admin')->get();

            case 'guru':
                return User::where('role', 'guru')->get();

            case 'ketua_kelas':
                return User::where('role', 'ketua_kelas')->get();

            case 'guru_piket':
                return User::whereHas('guruPiket')->get();

            default:
                return collect();
        }
    }

    /**
     * View broadcast details
     */
    public function show($id)
    {
        $broadcast = BroadcastMessage::with('sentBy')->findOrFail($id);
        return view('admin.broadcast.show', compact('broadcast'));
    }

    /**
     * Delete broadcast
     */
    public function destroy($id)
    {
        try {
            $broadcast = BroadcastMessage::findOrFail($id);
            $broadcast->delete();

            return response()->json([
                'success' => true,
                'message' => 'Broadcast berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus broadcast: ' . $e->getMessage()
            ], 500);
        }
    }
}

<?php

namespace App\Events;

use App\Models\Absensi;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AbsensiCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Absensi $absensi;

    /**
     * Create a new event instance.
     */
    public function __construct(Absensi $absensi)
    {
        $this->absensi = $absensi;
    }
}

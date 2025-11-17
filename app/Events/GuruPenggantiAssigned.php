<?php

namespace App\Events;

use App\Models\GuruPengganti;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GuruPenggantiAssigned
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public GuruPengganti $guruPengganti;

    /**
     * Create a new event instance.
     */
    public function __construct(GuruPengganti $guruPengganti)
    {
        $this->guruPengganti = $guruPengganti;
    }
}

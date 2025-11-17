<?php

namespace App\Events;

use App\Models\IzinCuti;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IzinApproved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public IzinCuti $izinCuti;

    /**
     * Create a new event instance.
     */
    public function __construct(IzinCuti $izinCuti)
    {
        $this->izinCuti = $izinCuti;
    }
}

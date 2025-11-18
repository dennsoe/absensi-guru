<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SendReminderNotification;

class SendRemindersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-reminders {type : Reminder type (absensi, checkout, izin_pending)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder notifications to teachers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->argument('type');

        // Validasi tipe reminder
        $validTypes = ['absensi', 'checkout', 'izin_pending'];
        if (!in_array($type, $validTypes)) {
            $this->error('❌ Invalid reminder type. Valid types: ' . implode(', ', $validTypes));
            return 1;
        }

        $this->info("🚀 Sending {$type} reminders...");

        // Dispatch job
        SendReminderNotification::dispatch($type);

        $this->info('✅ Reminder job dispatched to queue.');
        $this->info('   Run "php artisan queue:work" to process the job.');

        return 0;
    }
}

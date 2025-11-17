<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Guru;
use App\Models\Absensi;
use App\Models\SuratPeringatan;
use App\Jobs\GenerateSuratPeringatan;
use Carbon\Carbon;

class GenerateSuratPeringatanCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-surat-peringatan {--guru-id= : Specific guru ID} {--bulan= : Month (1-12)} {--tahun= : Year}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Surat Peringatan untuk guru dengan alpha >= 3 hari';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting Surat Peringatan generation...');
        
        $bulan = $this->option('bulan') ?? Carbon::now()->month;
        $tahun = $this->option('tahun') ?? Carbon::now()->year;
        $guruId = $this->option('guru-id');
        
        // Query guru yang perlu diproses
        $query = Guru::where('status_kepegawaian', 'aktif');
        
        if ($guruId) {
            $query->where('id_guru', $guruId);
        }
        
        $gurus = $query->get();
        
        if ($gurus->isEmpty()) {
            $this->warn('⚠️  No active teachers found.');
            return 0;
        }
        
        $this->info("Processing {$gurus->count()} teachers for period: {$bulan}/{$tahun}");
        $progressBar = $this->output->createProgressBar($gurus->count());
        
        $generated = 0;
        
        foreach ($gurus as $guru) {
            // Hitung jumlah alpha dalam bulan ini
            $alphaCount = Absensi::where('id_guru', $guru->id_guru)
                ->whereMonth('tanggal_absen', $bulan)
                ->whereYear('tanggal_absen', $tahun)
                ->where('status_kehadiran', 'Alpha')
                ->count();
            
            // Tentukan jenis SP berdasarkan alpha count
            $jenisSP = null;
            if ($alphaCount >= 5) {
                $jenisSP = 'SP3';
            } elseif ($alphaCount >= 4) {
                $jenisSP = 'SP2';
            } elseif ($alphaCount >= 3) {
                $jenisSP = 'SP1';
            }
            
            // Generate SP jika memenuhi kriteria
            if ($jenisSP) {
                // Check apakah sudah ada SP untuk periode ini
                $existingSP = SuratPeringatan::where('id_guru', $guru->id_guru)
                    ->where('jenis_sp', $jenisSP)
                    ->whereMonth('tanggal_sp', $bulan)
                    ->whereYear('tanggal_sp', $tahun)
                    ->first();
                
                if (!$existingSP) {
                    // Dispatch job untuk generate PDF
                    GenerateSuratPeringatan::dispatch($guru->id_guru, $jenisSP, $bulan, $tahun);
                    $generated++;
                    $this->newLine();
                    $this->info("✅ Dispatched {$jenisSP} for {$guru->nama_lengkap} ({$alphaCount} alpha)");
                }
            }
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine(2);
        $this->info("✅ Completed! {$generated} Surat Peringatan jobs dispatched to queue.");
        
        return 0;
    }
}

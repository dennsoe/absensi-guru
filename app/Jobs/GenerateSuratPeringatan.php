<?php

namespace App\Jobs;

use App\Models\Guru;
use App\Models\Absensi;
use App\Models\SuratPeringatan;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class GenerateSuratPeringatan implements ShouldQueue
{
    use Queueable;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 300;

    protected int $guruId;
    protected string $jenisSP;
    protected int $bulan;
    protected int $tahun;

    /**
     * Create a new job instance.
     */
    public function __construct(int $guruId, string $jenisSP, int $bulan, int $tahun)
    {
        $this->guruId = $guruId;
        $this->jenisSP = $jenisSP;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $guru = Guru::findOrFail($this->guruId);

            // Get alpha count for the period
            $alphaCount = Absensi::where('guru_id', $this->guruId)
                ->where('status', 'alpha')
                ->whereYear('tanggal', $this->tahun)
                ->whereMonth('tanggal', $this->bulan)
                ->count();

            // Create surat peringatan record
            $sp = SuratPeringatan::create([
                'guru_id' => $this->guruId,
                'jenis' => $this->jenisSP,
                'nomor_surat' => $this->generateNomorSurat($this->jenisSP),
                'tanggal_surat' => Carbon::now(),
                'periode_bulan' => $this->bulan,
                'periode_tahun' => $this->tahun,
                'jumlah_pelanggaran' => $alphaCount,
                'keterangan' => "Tidak hadir tanpa keterangan sebanyak {$alphaCount} kali pada periode " .
                               Carbon::create($this->tahun, $this->bulan)->locale('id')->format('F Y'),
                'status' => 'draft',
                'dibuat_oleh' => 1, // System/Admin
            ]);

            // Generate PDF
            $pdf = Pdf::loadView('pdf.surat-peringatan', [
                'sp' => $sp,
                'guru' => $guru,
            ]);

            // Save PDF to storage
            $filename = "SP_{$this->jenisSP}_{$guru->nip}_" . date('Ymd') . ".pdf";
            $path = "surat-peringatan/{$this->tahun}/{$this->bulan}/";
            Storage::disk('public')->put($path . $filename, $pdf->output());

            // Update file path
            $sp->update([
                'file_pdf' => $path . $filename,
                'status' => 'terbit',
            ]);

            Log::info("GenerateSuratPeringatan: Created {$this->jenisSP} for Guru ID {$this->guruId}");

        } catch (\Exception $e) {
            Log::error('GenerateSuratPeringatan failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate nomor surat
     */
    protected function generateNomorSurat(string $jenis): string
    {
        $count = SuratPeringatan::where('jenis', $jenis)
            ->whereYear('tanggal_surat', $this->tahun)
            ->count() + 1;

        $sekolah = config('sekolah.nama', 'SMKN Kasomalang');

        return sprintf(
            '%03d/%s/%s/%s/%04d',
            $count,
            $jenis,
            str_replace(' ', '', $sekolah),
            strtoupper(Carbon::create($this->tahun, $this->bulan)->locale('id')->format('mY')),
            $this->tahun
        );
    }
}

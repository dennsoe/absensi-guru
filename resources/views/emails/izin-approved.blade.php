<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persetujuan Izin/Cuti</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }

        .content {
            background: #f9f9f9;
            padding: 30px;
            border: 1px solid #ddd;
        }

        .detail-box {
            background: white;
            border: 1px solid #ddd;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }

        .detail-row {
            display: flex;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .detail-label {
            font-weight: bold;
            width: 150px;
        }

        .detail-value {
            flex: 1;
        }

        .success-badge {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
        }

        .footer {
            background: #333;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            border-radius: 0 0 10px 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>✅ Permohonan Disetujui</h1>
    </div>

    <div class="content">
        <p>Yth. {{ $izinCuti->guru->nama }},</p>

        <p>Permohonan <strong>{{ strtoupper($izinCuti->jenis) }}</strong> Anda telah <span
                class="success-badge">DISETUJUI</span></p>

        <div class="detail-box">
            <h3 style="margin-top: 0;">Detail Permohonan:</h3>

            <div class="detail-row">
                <div class="detail-label">Nomor Surat:</div>
                <div class="detail-value">{{ $izinCuti->nomor_surat }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Jenis:</div>
                <div class="detail-value">{{ ucfirst($izinCuti->jenis) }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Tanggal:</div>
                <div class="detail-value">
                    {{ $izinCuti->tanggal_mulai->format('d/m/Y') }} -
                    {{ $izinCuti->tanggal_selesai->format('d/m/Y') }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Durasi:</div>
                <div class="detail-value">{{ $izinCuti->durasi }} hari</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Alasan:</div>
                <div class="detail-value">{{ $izinCuti->alasan }}</div>
            </div>

            @if ($izinCuti->catatan_approval)
                <div class="detail-row">
                    <div class="detail-label">Catatan:</div>
                    <div class="detail-value">{{ $izinCuti->catatan_approval }}</div>
                </div>
            @endif

            <div class="detail-row">
                <div class="detail-label">Disetujui oleh:</div>
                <div class="detail-value">{{ $izinCuti->approvedBy->name ?? 'Admin' }}</div>
            </div>

            <div class="detail-row" style="border-bottom: none;">
                <div class="detail-label">Tanggal Persetujuan:</div>
                <div class="detail-value">{{ $izinCuti->updated_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>

        <p><strong>Informasi Penting:</strong></p>
        <ul>
            <li>Pastikan Anda menyimpan surat izin/cuti ini sebagai bukti</li>
            <li>Sistem akan otomatis mencatat kehadiran Anda sesuai status izin/cuti</li>
            <li>Jika ada perubahan rencana, segera hubungi pihak sekolah</li>
        </ul>

        <p style="margin-top: 30px;">
            Terima kasih,<br>
            <strong>Sistem Absensi Guru</strong><br>
            SDN Nekas
        </p>
    </div>

    <div class="footer">
        <p>
            Email ini dikirim otomatis oleh sistem.<br>
            Jangan balas email ini.
        </p>
        <p style="margin-top: 10px;">
            &copy; {{ date('Y') }} SDN Nekas. All rights reserved.
        </p>
    </div>
</body>

</html>

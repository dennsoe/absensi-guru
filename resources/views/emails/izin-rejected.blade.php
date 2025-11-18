<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penolakan Izin/Cuti</title>
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
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
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

        .danger-badge {
            display: inline-block;
            background: #dc3545;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
        }

        .reason-box {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
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
        <h1>❌ Permohonan Ditolak</h1>
    </div>

    <div class="content">
        <p>Yth. {{ $izinCuti->guru->nama }},</p>

        <p>Dengan menyesal kami informasikan bahwa permohonan <strong>{{ strtoupper($izinCuti->jenis) }}</strong> Anda
            telah <span class="danger-badge">DITOLAK</span></p>

        <div class="detail-box">
            <h3 style="margin-top: 0;">Detail Permohonan:</h3>

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

            <div class="detail-row" style="border-bottom: none;">
                <div class="detail-label">Alasan Permohonan:</div>
                <div class="detail-value">{{ $izinCuti->alasan }}</div>
            </div>
        </div>

        <div class="reason-box">
            <h4 style="margin-top: 0;">Alasan Penolakan:</h4>
            <p style="margin: 0;">{{ $izinCuti->alasan_penolakan ?? 'Tidak disebutkan' }}</p>
        </div>

        <p><strong>Tindak Lanjut:</strong></p>
        <ul>
            <li>Silakan hubungi bagian administrasi untuk informasi lebih lanjut</li>
            <li>Anda dapat mengajukan permohonan baru dengan alasan yang lebih lengkap</li>
            <li>Pastikan melengkapi dokumen pendukung jika diperlukan</li>
        </ul>

        <p style="margin-top: 30px;">
            Terima kasih atas pengertiannya,<br>
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

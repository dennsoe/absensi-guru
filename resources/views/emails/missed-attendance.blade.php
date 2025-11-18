<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reminder Absensi</title>
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
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
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

        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }

        .schedule-box {
            background: white;
            border: 1px solid #ddd;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }

        .button {
            display: inline-block;
            padding: 15px 30px;
            background: #ffc107;
            color: #333;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
            text-align: center;
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
        <h1>⏰ Reminder Absensi</h1>
    </div>

    <div class="content">
        <p>Yth. {{ $guru->nama }},</p>

        <div class="warning-box">
            <h3 style="margin-top: 0;">⚠️ Anda Belum Melakukan Absensi</h3>
            <p style="margin-bottom: 0;">Kami mengingatkan bahwa Anda belum melakukan absensi untuk jadwal mengajar hari
                ini.</p>
        </div>

        <div class="schedule-box">
            <h4 style="margin-top: 0;">Detail Jadwal:</h4>
            <p style="margin: 5px 0;"><strong>Mata Pelajaran:</strong> {{ $jadwal->mataPelajaran->nama_mapel }}</p>
            <p style="margin: 5px 0;"><strong>Kelas:</strong> {{ $jadwal->kelas->nama_kelas }}</p>
            <p style="margin: 5px 0;"><strong>Waktu:</strong> {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</p>
            <p style="margin: 5px 0;"><strong>Tanggal:</strong> {{ $tanggal->format('d/m/Y') }}</p>
        </div>

        <p><strong>Cara Melakukan Absensi:</strong></p>
        <ul>
            <li>Login ke sistem absensi</li>
            <li>Pilih menu "Absensi"</li>
            <li>Scan QR Code dari Ketua Kelas atau gunakan fitur Selfie</li>
            <li>Pastikan Anda berada di area sekolah</li>
        </ul>

        <center>
            <a href="{{ url('/guru/absensi/scan-qr') }}" class="button">
                Absen Sekarang
            </a>
        </center>

        <p style="margin-top: 30px; color: #dc3545;">
            <strong>Penting:</strong> Keterlambatan absensi akan dicatat dalam sistem dan dapat mempengaruhi penilaian
            kinerja Anda.
        </p>

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

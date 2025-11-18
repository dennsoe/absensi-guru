<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

        .footer {
            background: #333;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            border-radius: 0 0 10px 10px;
        }

        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }

        .info-box {
            background: #e8f4fd;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 20px 0;
        }

        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
        }

        .success-box {
            background: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 20px 0;
        }

        .danger-box {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $title }}</h1>
    </div>

    <div class="content">
        <p>Halo {{ $user->name }},</p>

        {!! $message !!}

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

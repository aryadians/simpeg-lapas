<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Cuti Baru</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
            background-color: #f4f7f6;
            color: #3d4852;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background-color: #4f46e5;
            color: #ffffff;
            padding: 25px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .content p {
            margin: 0 0 1em;
        }
        .details {
            background-color: #f4f7f6;
            border-left: 4px solid #4f46e5;
            padding: 15px;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .details strong {
            display: block;
            color: #2d3748;
            margin-bottom: 5px;
        }
        .button {
            display: inline-block;
            background-color: #4f46e5;
            color: #ffffff;
            padding: 12px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #718096;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Pengajuan Cuti Baru</h1>
        </div>
        <div class="content">
            <p>Yth. Bapak/Ibu Admin,</p>
            <p>Telah diterima pengajuan cuti baru dengan rincian sebagai berikut:</p>
            
            <div class="details">
                <strong>Pegawai:</strong>
                <span>{{ $leaveRequest->user->name }}</span>
            </div>

            <div class="details">
                <strong>Tanggal Cuti:</strong>
                <span>{{ \Carbon\Carbon::parse($leaveRequest->start_date)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($leaveRequest->end_date)->translatedFormat('d F Y') }}</span>
            </div>

            <div class="details">
                <strong>Alasan:</strong>
                <span>{{ $leaveRequest->reason }}</span>
            </div>

            <p>Mohon untuk segera ditinjau dan diproses lebih lanjut melalui aplikasi.</p>

            <a href="{{ url('/') }}" class="button">Buka Dashboard</a>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Semua hak dilindungi.</p>
        </div>
    </div>
</body>
</html>

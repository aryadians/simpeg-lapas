<!DOCTYPE html>
<html>
<head>
    <title>Laporan Patroli - {{ $monthName }} {{ $year }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 10px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { bg-color: #f2f2f2; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        .footer { margin-top: 50px; text-align: right; }
        .signature { margin-top: 80px; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Aktivitas Patroli Checkpoint</h2>
        <p>Lapas Kelas IIB Jombang - Periode: {{ $monthName }} {{ $year }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="15%">Waktu</th>
                <th width="20%">Petugas</th>
                <th width="25%">Lokasi Checkpoint</th>
                <th>Catatan Observasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($patrolLogs as $log)
            <tr>
                <td style="font-family: monospace;">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                <td><strong>{{ $log->user->name }}</strong></td>
                <td>{{ $log->checkpoint->name }} ({{ $log->checkpoint->location_code }})</td>
                <td>{{ $log->notes ?: '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
        <div class="signature">
            <p>Mengetahui,</p>
            <p><strong>Kepala Kesatuan Pengamanan</strong></p>
            <br><br><br>
            <p>__________________________</p>
        </div>
    </div>
</body>
</html>

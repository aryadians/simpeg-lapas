<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Tunjangan Kinerja</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 12px;
        }
        .footer .signature {
            margin-top: 60px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Laporan Tunjangan Kinerja (TUKIN)</h1>
            <p>Bulan: {{ $month }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Pegawai</th>
                    <th>NIP</th>
                    <th>Jabatan</th>
                    <th class="text-right">Tukin Pokok (Rp)</th>
                    <th class="text-right">Total Potongan (%)</th>
                    <th class="text-right">Potongan (Rp)</th>
                    <th class="text-right">Tukin Diterima (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $user['name'] }}</td>
                        <td>{{ $user['nip'] }}</td>
                        <td>{{ $user['jabatan'] }}</td>
                        <td class="text-right">{{ number_format($user['tukin_nominal'], 0, ',', '.') }}</td>
                        <td class="text-right">{{ $user['total_deduction_percentage'] }}%</td>
                        <td class="text-right">{{ number_format($user['total_deduction_amount'], 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($user['final_tukin'], 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center;">Tidak ada data untuk ditampilkan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            <p>Dicetak pada: {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }}</p>
            <div class="signature">
                (_________________________)
            </div>
        </div>
    </div>
</body>
</html>

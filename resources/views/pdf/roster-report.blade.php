<!DOCTYPE html>
<html>
<head>
    <title>Laporan Jadwal Dinas</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        h2, h4 { text-align: center; margin: 5px 0; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 4px; text-align: center; }
        th { background-color: #f0f0f0; }
        .text-left { text-align: left; }
        .bg-pagi { background-color: #fffbeb; } /* Kuning Tipis */
        .bg-siang { background-color: #eff6ff; } /* Biru Tipis */
        .bg-malam { background-color: #f1f5f9; } /* Abu Tipis */
        
        .signature { margin-top: 40px; float: right; width: 200px; text-align: center; }
    </style>
</head>
<body>

    <h2>JADWAL DINAS PEGAWAI</h2>
    <h4>PERIODE: {{ strtoupper($monthName) }} {{ $year }}</h4>

    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 30px;">NO</th>
                <th rowspan="2" style="width: 150px;">NAMA / NIP</th>
                <th colspan="{{ count($dates) }}">TANGGAL</th>
            </tr>
            <tr>
                @foreach($dates as $date)
                    <th>{{ $date->format('d') }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($users as $index => $user)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="text-left">
                    <strong>{{ $user->name }}</strong><br>
                    <span style="font-size: 9px; color: #555;">{{ $user->nip }}</span>
                </td>
                
                @foreach($dates as $date)
                    @php
                        $dateStr = $date->format('Y-m-d');
                        $kode = $schedule[$user->id][$dateStr] ?? '-';
                        
                        // Warna sel sesuai shift
                        $bgClass = '';
                        if($kode == 'P') $bgClass = 'bg-pagi';
                        elseif($kode == 'S') $bgClass = 'bg-siang';
                        elseif($kode == 'M') $bgClass = 'bg-malam';
                    @endphp
                    
                    <td class="{{ $bgClass }}">
                        {{ $kode == '-' ? '' : $kode }}
                    </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature">
        <p>Jombang, {{ date('d F Y') }}</p>
        <p>Kepala Kesatuan Pengamanan,</p>
        <br><br><br><br>
        <p style="text-decoration: underline; font-weight: bold;">( NAMA KALAPAS )</p>
        <p>NIP. 19800101 200012 1 001</p>
    </div>

</body>
</html>
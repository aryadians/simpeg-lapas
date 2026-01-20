<!DOCTYPE html>
<html>
<head>
    <title>Jadwal Dinas</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .header p { margin: 2px; font-size: 9pt; color: #555; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 4px; text-align: center; font-size: 8pt; }
        th { background-color: #eee; }
        
        .name-col { text-align: left; width: 150px; font-weight: bold; }
        .shift-p { background-color: #fff; } /* Pagi - Putih */
        .shift-s { background-color: #e0f2fe; } /* Siang - Biru Tipis */
        .shift-m { background-color: #333; color: #fff; } /* Malam - Hitam */
        .shift-l { background-color: #eee; color: #aaa; } /* Libur/Off */
        
        .legend { margin-top: 20px; font-size: 8pt; }
        .ttd { margin-top: 40px; float: right; width: 200px; text-align: center; }
    </style>
</head>
<body>

    <div class="header">
        <h2>JADWAL DINAS PEGAWAI</h2>
        <p>LEMBAGA PEMASYARAKATAN KELAS IIB</p>
        <p>Periode: {{ $monthName }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 20px;">No</th>
                <th rowspan="2" class="name-col">Nama Pegawai</th>
                <th colspan="{{ count($dates) }}">Tanggal</th>
            </tr>
            <tr>
                @foreach($dates as $date)
                    <th>{{ $date }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($rosters as $userId => $userRosters)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td class="name-col">{{ $userRosters->first()->user->name }}</td>
                    
                    @foreach($dates as $date)
                        @php
                            // Cari apakah ada jadwal di tanggal ini (Looping manual karena PDF tidak bisa query berat)
                            $schedule = $userRosters->first(function($item) use ($date) {
                                return \Carbon\Carbon::parse($item->date)->day == $date;
                            });
                            
                            $code = '-';
                            $class = 'shift-l';
                            
                            if ($schedule) {
                                if ($schedule->shift->name == 'Regu Pagi') { $code = 'P'; $class = 'shift-p'; }
                                elseif ($schedule->shift->name == 'Regu Siang') { $code = 'S'; $class = 'shift-s'; }
                                elseif ($schedule->shift->is_overnight) { $code = 'M'; $class = 'shift-m'; }
                            }
                        @endphp
                        <td class="{{ $class }}">{{ $code }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="legend">
        <strong>Keterangan:</strong> P = Pagi, S = Siang, M = Malam, - = Libur/Lepas Dinas
    </div>

    <div class="ttd">
        <p>Mengetahui,<br>Kepala Pengamanan Lapas</p>
        <br><br><br>
        <p><strong>_______________________</strong><br>NIP. 19800101 200001 1 001</p>
    </div>

</body>
</html>
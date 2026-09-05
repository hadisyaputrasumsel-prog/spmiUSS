<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Jadwal Bulan Mutu - {{ $tahun }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        h2 {
            text-align: center;
            font-size: 16px;
            margin-bottom: 5px;
        }
        h3 {
            text-align: center;
            font-size: 14px;
            margin-top: 0;
            margin-bottom: 20px;
            font-weight: normal;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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
        .text-center {
            text-align: center;
        }
        .badge {
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-success { background-color: #d4edda; color: #155724; }
        .badge-warning { background-color: #fff3cd; color: #856404; }
        .badge-danger { background-color: #f8d7da; color: #721c24; }
        .badge-default { background-color: #e2e3e5; color: #383d41; }
    </style>
</head>
<body>

    <h2>JADWAL PELAKSANAAN BULAN MUTU (SPMI)</h2>
    <h3>Tahun Pelaporan: {{ $tahun }}</h3>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;" class="text-center">NO</th>
                <th>NAMA KEGIATAN</th>
                <th>TAHAP</th>
                <th>PENANGGUNG JAWAB</th>
                <th>TANGGAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($activities as $activity)
            @php
                $statusRecord = $statuses->get($activity->id);
                $status = $statusRecord ? $statusRecord->status : 'Belum Dilaksanakan';
                $tanggal = $statusRecord && $statusRecord->tanggal_pelaksanaan ? $statusRecord->tanggal_pelaksanaan : '-';
                
                $badgeClass = 'badge-default';
                if ($status == 'Terlaksana Sesuai Rencana') $badgeClass = 'badge-success';
                elseif ($status == 'Terlaksana - Tertunda') $badgeClass = 'badge-warning';
                elseif ($status == 'Dibatalkan') $badgeClass = 'badge-danger';
            @endphp
            <tr>
                <td class="text-center">{{ $activity->index_kegiatan }}</td>
                <td>{{ $activity->nama }}</td>
                <td>{{ $activity->tahap }}</td>
                <td>{{ $activity->pic }}</td>
                <td>{{ $tanggal }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; width: 100%;">
        <div style="float: right; width: 250px; text-align: center;">
            <p>Disahkan oleh,</p>
            <br><br><br><br>
            <p><strong>Ketua LPMA</strong></p>
        </div>
        <div style="clear: both;"></div>
    </div>

</body>
</html>

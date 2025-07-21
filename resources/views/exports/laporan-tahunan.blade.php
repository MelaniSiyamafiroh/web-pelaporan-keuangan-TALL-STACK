<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Tahunan {{ $tahun }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #eee;
        }
    </style>
</head>
<body>
    <h2>Laporan Tahunan Kegiatan Tahun {{ $tahun }}</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama PPTK</th>
                <th>Subkegiatan</th>
                <th>Kegiatan</th>
                <th>Jenis Belanja</th>
                <th>Nominal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($laporanSiap as $i => $laporan)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $laporan->pptk->name }}</td>
                    <td>{{ $laporan->subkegiatan->nama_subkegiatan }}</td>
                    <td>{{ $laporan->subkegiatan->kegiatan->nama_kegiatan }}</td>
                    <td>{{ $laporan->jenis_belanja }}</td>
                    <td>Rp {{ number_format($laporan->nominal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Total Laporan:</strong> {{ $laporanSiap->count() }}</p>
    <p><strong>Catatan:</strong> {{ $laporanSiap->first()?->catatan ?? '-' }}</p>
    <p><small>Dibukukan pada: {{ now()->format('d-m-Y H:i') }}</small></p>

</body>
</html>

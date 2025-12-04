<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pendapatan</title>

    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #444; padding: 6px; }
        .title { text-align: center; font-size: 18px; font-weight: bold; }
        .subtitle { text-align: center; margin-bottom: 15px; }
        .right { text-align: right; }
    </style>
</head>
<body>

<div class="title">LAPORAN PENDAPATAN</div>
<div class="subtitle">
    Periode: {{ $from ?? '-' }} s/d {{ $to ?? '-' }}
</div>

<h3>Total Pendapatan: Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Keterangan</th>
            <th>Sumber</th>
            <th>Nominal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $i => $row)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ \Carbon\Carbon::parse($row->tanggal)->format('d M Y') }}</td>
            <td>{{ $row->keterangan ?? '-' }}</td>
            <td>{{ ucfirst($row->sumber) }}</td>
            <td class="right">Rp {{ number_format($row->nominal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>

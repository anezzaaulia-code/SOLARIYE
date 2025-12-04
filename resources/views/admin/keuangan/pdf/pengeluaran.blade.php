<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Pengeluaran</title>
    <style>
        @page { margin: 20px; }

        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #444;
            padding: 6px;
            text-align: left;
        }

        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .right {
            text-align: right;
        }
    </style>
</head>
<body>

<div class="title">LAPORAN PENGELUARAN</div>
<div class="subtitle">
    Periode: {{ request('from') ?? '-' }} s/d {{ request('to') ?? '-' }}
</div>

<p><strong>Total Pengeluaran: </strong> Rp {{ number_format($totalPengeluaran,0,',','.') }}</p>

<table>
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="20%">Tanggal</th>
            <th width="35%">Keterangan</th>
            <th width="20%">Sumber</th>
            <th width="20%">Nominal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $i => $d)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ \Carbon\Carbon::parse($d->tanggal)->format('d M Y') }}</td>
            <td>{{ $d->keterangan ?? '-' }}</td>
            <td>{{ ucfirst($d->sumber) }}</td>
            <td class="right">Rp {{ number_format($d->nominal,0,',','.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>

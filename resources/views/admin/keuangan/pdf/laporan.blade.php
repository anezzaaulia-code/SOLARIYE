<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>

    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h2, h3 { text-align: center; margin: 4px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #444; padding: 6px; }
        .right { text-align: right; }
        .center { text-align: center; }
    </style>
</head>
<body>

<h2>LAPORAN KEUANGAN</h2>
<h3>Periode: {{ $from }} - {{ $to }}</h3>

<hr>

<table>
    <tr>
        <td><strong>Total Pendapatan</strong></td>
        <td class="right">Rp {{ number_format($pendapatan, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <td><strong>Total Pengeluaran</strong></td>
        <td class="right">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <td><strong>Saldo Akhir</strong></td>
        <td class="right">
            @if($saldo >= 0)
                <span style="color:green;">Rp {{ number_format($saldo, 0, ',', '.') }}</span>
            @else
                <span style="color:red;">Rp {{ number_format($saldo, 0, ',', '.') }}</span>
            @endif
        </td>
    </tr>
</table>

<br><br>

<h3>Rincian Transaksi</h3>

<table>
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="15%">Tanggal</th>
            <th width="10%">Jenis</th>
            <th width="45%">Keterangan</th>
            <th width="20%">Nominal</th>
        </tr>
    </thead>

    <tbody>
        @foreach($list as $i => $row)
        <tr>
            <td class="center">{{ $i+1 }}</td>
            <td>{{ \Carbon\Carbon::parse($row->tanggal)->format('d M Y') }}</td>
            <td class="center">{{ ucfirst($row->jenis) }}</td>
            <td>{{ $row->keterangan ?? '-' }}</td>
            <td class="right">
                Rp {{ number_format($row->nominal, 0, ',', '.') }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>

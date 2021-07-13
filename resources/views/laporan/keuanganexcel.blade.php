<table>
    <tr>
        <th colspan="6" style="text-align: center; font-weight: bold;">Laporan Keuangan Masjid {{ ucwords($keuangan['nama_masjid']) }}</th>
    </tr>
    <tr>
        <th colspan="6" style="text-align: center; font-weight: bold;">@if($tahun != null) Tahun {{ $tahun }} @endif</th>
    </tr>
</table>
@if(count($keuangan['history']) > 0)
<table cellpadding="1" cellspacing="0" border="1">
    <thead>
        <tr>
            <th rowspan="2" style="text-align: center; font-weight: bold; border: 1px solid #000000;">Nama Masjid</th>
            <th colspan="5" style="text-align: center; font-weight: bold; border: 1px solid #000000;">Riwayat Keuangan</th>
        </tr>
        <tr>
            <th style="text-align: center; font-weight: bold; border: 1px solid #000000;">Tanggal</th>
            <th style="text-align: center; font-weight: bold; border: 1px solid #000000;">Keterangan</th>
            <th style="text-align: center; font-weight: bold; border: 1px solid #000000;">Sumber</th>
            <th style="text-align: center; font-weight: bold; border: 1px solid #000000;">Masuk</th>
            <th style="text-align: center; font-weight: bold; border: 1px solid #000000;">Keluar</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="border: 1px solid #000000;" rowspan="{{ count($keuangan->history) }}">{{ $keuangan->nama_masjid }}</td>
            <td style="border: 1px solid #000000;">{{ \Carbon\Carbon::parse($keuangan->history[0]['tanggal'])->format('d/m/Y') }}</td>
            <td style="border: 1px solid #000000;">{{ ucfirst($keuangan->history[0]['keterangan']) }}</td>
            <td style="border: 1px solid #000000;">{{ ucfirst($keuangan->history[0]['sumber']) }}</td>
            <td style="border: 1px solid #000000;">
                @if($keuangan->history[0]['jenis_keuangan'] == 'masuk')
                Rp. {{ number_format($keuangan->history[0]['jumlah'],0,',','.') }}
                @endif
            </td>
            <td style="border: 1px solid #000000;">
                @if($keuangan->history[0]['jenis_keuangan'] == 'keluar')
                Rp. {{ number_format($keuangan->history[0]['jumlah'],0,',','.') }}
                @endif
            </td>
        </tr>
        @for ($i = 1; $i < count($keuangan->history); $i++)
        <tr>
            <td style="border: 1px solid #000000;">{{ \Carbon\Carbon::parse($keuangan->history[$i]['tanggal'])->format('d/m/Y') }}</td>
            <td style="border: 1px solid #000000;">{{ ucfirst($keuangan->history[$i]['keterangan']) }}</td>
            <td style="border: 1px solid #000000;">{{ ucfirst($keuangan->history[$i]['sumber']) }}</td>
            <td style="border: 1px solid #000000;">
                @if($keuangan->history[$i]['jenis_keuangan'] == 'masuk')
                Rp. {{ number_format($keuangan->history[$i]['jumlah'],0,',','.') }}
                @endif
            </td>
            <td style="border: 1px solid #000000;">
                @if($keuangan->history[$i]['jenis_keuangan'] == 'keluar')
                Rp. {{ number_format($keuangan->history[$i]['jumlah'],0,',','.') }}
                @endif
            </td>
        </tr>
        @endfor
        <tr>
            <td style="border: 1px solid #000000;"><b>Total Saldo</b></td>
            <td style="border: 1px solid #000000;" colspan="3">
                Rp. {{ number_format($keuangan->saldo,0,',','.') }}
            </td>
            <td style="border: 1px solid #000000;">Rp. {{ number_format($keuangan->history->where('jenis_keuangan', 'masuk')->sum('jumlah'),0,',','.') }}</td>
            <td style="border: 1px solid #000000;">Rp. {{ number_format($keuangan->history->where('jenis_keuangan', 'keluar')->sum('jumlah'),0,',','.') }}</td>
        </tr>
    </tbody>
</table>
<br><br><br>
<table>
    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th colspan="3">{{ ucwords(strtolower($keuangan['nama_masjid'])) }},....................{{ date('Y') }}</th>
    </tr>
    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th colspan="3">Bendahara,</th>
    </tr>
</table>
<br><br><br>
<table>
    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th colspan="3">.............................................................</th>
    </tr>
</table>
@else
<table>
    <tr>
        <th>Data tidak ditemukan!</th>
    </tr>
</table>
@endif
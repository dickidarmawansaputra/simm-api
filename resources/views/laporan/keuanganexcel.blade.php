<table>
    <thead>
        <tr>
            <th rowspan="2">Nama Masjid</th>
            <th rowspan="2">Tipologi Masjid</th>
            <th colspan="4">Riwayat Keuangan</th>
        </tr>
        <tr>
            <th>Jenis</th>
            <th>Jumlah</th>
            <th>Sumber</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td rowspan="{{ count($keuangan->history) }}">{{ $keuangan->nama_masjid }}</td>
            <td rowspan="{{ count($keuangan->history) }}">{{ $keuangan->tipologi_masjid }}</td>
            <td>Uang {{ ucfirst($keuangan->history[0]['jenis_keuangan']) }}</td>
            <td>Rp. {{ number_format($keuangan->history[0]['jumlah'],0,',','.') }}</td>
            <td>{{ ucfirst($keuangan->history[0]['sumber']) }}</td>
            <td>{{ ucfirst($keuangan->history[0]['keterangan']) }}</td>
        </tr>
        @for ($i = 1; $i < count($keuangan->history); $i++)
        <tr>
            <td>Uang {{ ucfirst($keuangan->history[$i]['jenis_keuangan']) }}</td>
            <td>Rp. {{ number_format($keuangan->history[$i]['jumlah'],0,',','.') }}</td>
            <td>{{ ucfirst($keuangan->history[$i]['sumber']) }}</td>
            <td>{{ ucfirst($keuangan->history[$i]['keterangan']) }}</td>
        </tr>
        @endfor
        <tr>
            <td colspan="2"><b>Total Saldo</b></td>
            <td colspan="4">
                Rp. {{ number_format($keuangan->saldo,0,',','.') }}
            </td>
        </tr>
    </tbody>
</table>
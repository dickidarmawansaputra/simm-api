<table>
    <tr>
        <th colspan="4" style="text-align: center; font-weight: bold;">Laporan Inventaris Masjid {{ ucwords($masjid['nama_masjid']) }}</th>
    </tr>
</table>
@if(count($data) > 0)
<table cellpadding="1" cellspacing="0" border="1">
    <thead>
        <tr>
            <th style="text-align: center; font-weight: bold; border: 1px solid #000000;">Kode Inventaris</th>
            <th style="text-align: center; font-weight: bold; border: 1px solid #000000;">Nama Inventaris</th>
            <th style="text-align: center; font-weight: bold; border: 1px solid #000000;">Kondisi Inventaris</th>
            <th style="text-align: center; font-weight: bold; border: 1px solid #000000;">Deskripsi Inventaris</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $val)
        <tr>
            <td style="border: 1px solid #000000;">{{ $val->kode_inventaris }}</td>
            <td style="border: 1px solid #000000;">{{ $val->nama_inventaris }}</td>
            <td style="border: 1px solid #000000;">{{ $val->kondisi_inventaris }}</td>
            <td style="border: 1px solid #000000;">{{ $val->deskripsi_inventaris }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<br><br><br>
<table>
    <tr>
        <th></th>
        <th></th>
        <th colspan="2">{{ ucwords(strtolower($masjid['nama_masjid'])) }},....................{{ date('Y') }}</th>
    </tr>
    <tr>
        <th></th>
        <th></th>
        <th colspan="2">Bendahara,</th>
    </tr>
</table>
<br><br><br>
<table>
    <tr>
        <th></th>
        <th></th>
        <th colspan="2">.............................................................</th>
    </tr>
</table>
@else
<table>
    <tr>
        <th>Data tidak ditemukan!</th>
    </tr>
</table>
@endif
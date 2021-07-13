<table>
    <tr>
        <th colspan="4" style="text-align: center; font-weight: bold;">Laporan Kegiatan Masjid {{ ucwords($masjid['nama_masjid']) }}</th>
    </tr>
    <tr>
        <th colspan="4" style="text-align: center; font-weight: bold;">@if($tahun != null) Tahun {{ $tahun }} @endif</th>
    </tr>
</table>
@if(count($data) > 0)
<table cellpadding="1" cellspacing="0" border="1">
    <thead>
        <tr>
            <th style="text-align: center; font-weight: bold; border: 1px solid #000000;">Nama Kegiatan</th>
            <th style="text-align: center; font-weight: bold; border: 1px solid #000000;">Jenis Kegiatan</th>
            <th style="text-align: center; font-weight: bold; border: 1px solid #000000;">Tanggal Waktu Kegiatan</th>
            <th style="text-align: center; font-weight: bold; border: 1px solid #000000;">Pemateri Kegiatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $val)
        <tr>
            <td style="border: 1px solid #000000;">{{ ucwords(strtolower($val->nama_kegiatan)) }}</td>
            <td style="border: 1px solid #000000;">{{ ucfirst($val->jenis_kegiatan) }}</td>
            <td style="border: 1px solid #000000;">{{ \Carbon\Carbon::parse($val->tanggal_waktu_kegiatan)->format('d F Y') }}</td>
            <td style="border: 1px solid #000000;">{{ ucfirst($val->pemateri) }}</td>
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
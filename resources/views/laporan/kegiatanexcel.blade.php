@if(count($data) > 0)
<table>
    <thead>
        <tr>
            <th>Nama Kegiatan</th>
            <th>Jenis Kegiatan</th>
            <th>Tanggal Waktu Kegiatan</th>
            <th>Pemateri Kegiatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $val)
        <tr>
            <td>{{ ucwords(strtolower($val->nama_kegiatan)) }}</td>
            <td>{{ ucfirst($val->jenis_kegiatan) }}</td>
            <td>{{ \Carbon\Carbon::parse($val->tanggal_waktu_kegiatan)->format('d F Y') }}</td>
            <td>{{ ucfirst($val->pemateri) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<table>
    <tr>
        <th>Data tidak ditemukan!</th>
    </tr>
</table>
@endif
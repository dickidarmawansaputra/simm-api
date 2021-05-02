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
        <tr>
            <td>{{ ucwords($kegiatan->nama_kegiatan) }}</td>
            <td>{{ $kegiatan->jenis_kegiatan }}</td>
            <td>{{ \Carbon\Carbon::parse($kegiatan->tanggal_waktu_kegiatan)->format('d F Y') }}</td>
            <td>{{ ucwords($kegiatan->pemateri) }}</td>
        </tr>
    </tbody>
</table>
<table>
    <thead>
        <tr>
            <th>Nama Kegiatan</th>
            <th>Jenis Kegiatan</th>
            <th>Tanggal & Waktu Kegiatan</th>
            <th>Pemateri Kegiatan</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ ucwords(strtolower($data->nama_kegiatan)) }}</td>
            <td>{{ ucfirst($data->jenis_kegiatan) }}</td>
            <td>{{ \Carbon\Carbon::parse($data->tanggal_waktu_kegiatan)->format('d F Y') }}</td>
            <td>{{ ucfirst($data->pemateri) }}</td>
        </tr>
    </tbody>
</table>
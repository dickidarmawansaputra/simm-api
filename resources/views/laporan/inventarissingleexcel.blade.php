<table>
    <thead>
        <tr>
            <th>Kode Inventaris</th>
            <th>Nama Inventaris</th>
            <th>Kondisi Inventaris</th>
            <th>Deskripsi Inventaris</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $inventaris->kode_inventaris }}</td>
            <td>{{ ucwords($inventaris->nama_inventaris) }}</td>
            <td>{{ $inventaris->kondisi_inventaris }}</td>
            <td>{{ ucfirst($inventaris->deskripsi_inventaris) }}</td>
        </tr>
    </tbody>
</table>
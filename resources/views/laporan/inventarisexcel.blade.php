@if(count($data) > 0)
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
        @foreach($data as $val)
        <tr>
            <td>{{ $val->kode_inventaris }}</td>
            <td>{{ $val->nama_inventaris }}</td>
            <td>{{ $val->kondisi_inventaris }}</td>
            <td>{{ $val->deskripsi_inventaris }}</td>
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
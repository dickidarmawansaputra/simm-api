<!DOCTYPE html>
<html>
<head>
	<title>Laporan Inventaris</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<style>
	.page-break {
	    page-break-after: always;
	}
	table {
		width: 100%;
	}
	.format {
		font-size: 12pt;
		line-height: 1;
	}
	</style>
</head>
<body>
	{{-- <table cellpadding="0" cellspacing="0">
		<tr>
			<td width="30%">
				<img src="{{ asset('logo.png') }}" width="200">
			</td>
			<td width="70%">
				<h3 style="margin-left: -250px; text-align: center;">
					Rincian Faktur Barang Masuk
				</h3>
			</td>
		</tr>
	</table> --}}
	<hr>
	<div class="format">
		<table cellpadding="1" cellspacing="0" border="1">
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
					<td>{{ strtoupper($data->kode_inventaris) }}</td>
					<td>{{ ucfirst($data->nama_inventaris) }}</td>
					<td>{{ ucfirst($data->kondisi_inventaris) }}</td>
					<td>{{ ucfirst($data->deskripsi_inventaris) }}</td>
				</tr>
			</tbody>
		</table>
	</div>
</body>
</html>
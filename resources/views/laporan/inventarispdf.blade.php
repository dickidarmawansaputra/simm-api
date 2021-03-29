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
		@if(count($data) > 0)
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
				@foreach($data as $val)
				<tr>
					<td>{{ strtoupper($val->kode_inventaris) }}</td>
					<td>{{ ucfirst($val->nama_inventaris) }}</td>
					<td>{{ ucfirst($val->kondisi_inventaris) }}</td>
					<td>{{ ucfirst($val->deskripsi_inventaris) }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		@else
		<h1>Data tidak ditemukan!</h1>
		@endif
	</div>
</body>
</html>
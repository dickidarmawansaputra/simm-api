<!DOCTYPE html>
<html>
<head>
	<title>Laporan Kegiatan</title>
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
					<th>Nama Kegiatan</th>
					<th>Jenis Kegiatan</th>
					<th>Tanggal & Waktu Kegiatan</th>
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
		<h1>Data tidak ditemukan!</h1>
		@endif
	</div>
</body>
</html>
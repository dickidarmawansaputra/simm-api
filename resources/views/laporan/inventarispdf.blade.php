<!DOCTYPE html>
<html>
<head>
	<title>Laporan Inventaris Masjid {{ ucwords($masjid->nama_masjid) }}</title>
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
	<h2 style="text-align: center;">Laporan Inventaris Masjid {{ ucwords($masjid->nama_masjid) }}</h2>
	{{-- @if($tahun != null)
	<h2 style="text-align: center;">Tahun {{ $tahun }}</h2>
	@endif --}}
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
		<br><br><br>
		<p style="margin-left: 420px; margin-right: -100px;">{{ ucwords(strtolower($masjid['kelurahan'])) }},<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>{{ date('Y') }}</p>
		<p style="margin-left: 420px; margin-right: -100px;">Bendahara,</p>
		<br><br><br><br><br><br>
		<p style="margin-left: 420px; margin-right: -100px;"><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></p>
		@else
		<h1>Data tidak ditemukan!</h1>
		@endif
	</div>
</body>
</html>
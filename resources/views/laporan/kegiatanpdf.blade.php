<!DOCTYPE html>
<html>
<head>
	<title>Laporan Kegiatan Masjid {{ ucwords($masjid->nama_masjid) }}</title>
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
	<h2 style="text-align: center;">Laporan Kegiatan Masjid {{ ucwords($masjid->nama_masjid) }}</h2>
	@if($tahun != null)
	<h2 style="text-align: center;">Tahun {{ $tahun }}</h2>
	@endif
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
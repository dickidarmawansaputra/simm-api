<!DOCTYPE html>
<html>
<head>
	<title>Laporan Keuangan</title>
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
		@if(count($data['history']) > 0)
		<table cellpadding="1" cellspacing="0" border="1">
			<thead>
				<tr>
					<th rowspan="2">Nama Masjid</th>
					<th rowspan="2">Tipologi Masjid</th>
					<th colspan="4">Riwayat Keuangan</th>
				</tr>
				<tr>
					<th>Jenis</th>
					<th>Jumlah</th>
					<th>Sumber</th>
					<th>Keterangan</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td rowspan="{{ count($data->history) }}">{{ $data->nama_masjid }}</td>
					<td rowspan="{{ count($data->history) }}">{{ $data->tipologi_masjid }}</td>
					<td>Uang {{ ucfirst($data->history[0]['jenis_keuangan']) }}</td>
					<td>Rp. {{ number_format($data->history[0]['jumlah'],0,',','.') }}</td>
					<td>{{ ucfirst($data->history[0]['sumber']) }}</td>
					<td>{{ ucfirst($data->history[0]['keterangan']) }}</td>
				</tr>
				@for ($i = 1; $i < count($data->history); $i++)
				<tr>
					<td>Uang {{ ucfirst($data->history[$i]['jenis_keuangan']) }}</td>
					<td>Rp. {{ number_format($data->history[$i]['jumlah'],0,',','.') }}</td>
					<td>{{ ucfirst($data->history[$i]['sumber']) }}</td>
					<td>{{ ucfirst($data->history[$i]['keterangan']) }}</td>
				</tr>
				@endfor
				<tr>
					<td colspan="2"><b>Jumlah Uang</b></td>
					<td colspan="4">
						Rp. {{ number_format($data->history[0]['jumlah'],0,',','.') }}
					</td>
				</tr>
			</tbody>
		</table>
		@else
		<h1>Data tidak ditemukan!</h1>
		@endif
	</div>
</body>
</html>
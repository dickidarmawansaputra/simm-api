<!DOCTYPE html>
<html>
<head>
	<title>Laporan Keuangan Masjid {{ ucwords($data['nama_masjid']) }}</title>
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
	<h2 style="text-align: center;">Laporan Keuangan Masjid {{ ucwords($data['nama_masjid']) }}</h2>
	@if($tahun != null)
	<h2 style="text-align: center;">Tahun {{ $tahun }}</h2>
	@endif
	<hr>
	<div class="format">
		@if(count($data['history']) > 0)
		<table cellpadding="1" cellspacing="0" border="1">
			<thead>
				<tr>
					<th rowspan="2">Nama Masjid</th>
					<th colspan="5">Riwayat Keuangan</th>
				</tr>
				<tr>
					<th>Tanggal</th>
					<th>Keterangan</th>
					<th>Sumber</th>
					<th>Masuk</th>
					<th>Keluar</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td rowspan="{{ count($data->history) }}">{{ $data->nama_masjid }}</td>
					<td>{{ \Carbon\Carbon::parse($data->history[0]['tanggal'])->format('d/m/Y') }}</td>
					<td>{{ ucfirst($data->history[0]['keterangan']) }}</td>
					<td>{{ ucfirst($data->history[0]['sumber']) }}</td>
					<td>
						@if($data->history[0]['jenis_keuangan'] == 'masuk')
						Rp. {{ number_format($data->history[0]['jumlah'],0,',','.') }}
						@endif
					</td>
					<td>
						@if($data->history[0]['jenis_keuangan'] == 'keluar')
						Rp. {{ number_format($data->history[0]['jumlah'],0,',','.') }}
						@endif
					</td>
				</tr>
				@for ($i = 1; $i < count($data->history); $i++)
				<tr>
					<td>{{ \Carbon\Carbon::parse($data->history[$i]['tanggal'])->format('d/m/Y') }}</td>
					<td>{{ ucfirst($data->history[$i]['keterangan']) }}</td>
					<td>{{ ucfirst($data->history[$i]['sumber']) }}</td>
					<td>
						@if($data->history[$i]['jenis_keuangan'] == 'masuk')
						Rp. {{ number_format($data->history[$i]['jumlah'],0,',','.') }}
						@endif
					</td>
					<td>
						@if($data->history[$i]['jenis_keuangan'] == 'keluar')
						Rp. {{ number_format($data->history[$i]['jumlah'],0,',','.') }}
						@endif
					</td>
				</tr>
				@endfor
				<tr>
					<td><b>Total Saldo</b></td>
					<td colspan="3">
						Rp. {{ number_format($data->saldo,0,',','.') }}
					</td>
					<td>Rp. {{ number_format($data->history->where('jenis_keuangan', 'masuk')->sum('jumlah'),0,',','.') }}</td>
					<td>Rp. {{ number_format($data->history->where('jenis_keuangan', 'keluar')->sum('jumlah'),0,',','.') }}</td>
				</tr>
			</tbody>
		</table>
		<br><br><br>
		<p style="margin-left: 420px; margin-right: -100px;">{{ ucwords(strtolower($data['kelurahan'])) }},<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>{{ date('Y') }}</p>
		<p style="margin-left: 420px; margin-right: -100px;">Bendahara,</p>
		<br><br><br><br><br><br>
		<p style="margin-left: 420px; margin-right: -100px;"><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></p>
		@else
		<h1>Data tidak ditemukan!</h1>
		@endif
	</div>
</body>
</html>
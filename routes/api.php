<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('email/send', 'MailController@send');
Route::group(['prefix' => 'v1', 'namespace' => 'API'], function () {
	Route::group(['prefix' => 'data', 'namespace' => 'Frontend'], function () {
		Route::get('masjid/show/{id}', 'MasjidController@show');
		Route::get('masjid/showall', 'MasjidController@showAll');
		Route::post('masjid/showall/detail', 'MasjidController@showAllDetail');
		Route::get('kegiatan/show/{masjid}/{id}', 'KegiatanController@show');
		Route::get('kegiatan/showall/{masjid}', 'KegiatanController@showAll');
		Route::get('kegiatan/showall/limit/{masjid}', 'KegiatanController@showAllLimit');
		Route::get('kegiatan/data', 'KegiatanController@data');
		Route::post('kegiatan/dataall', 'KegiatanController@dataAll');
		Route::get('fasilitas/show/{masjid}/{id}', 'FasilitasController@show');
		Route::get('fasilitas/showall/{masjid}', 'FasilitasController@showAll');
		Route::get('fasilitas/showall/limit/{masjid}', 'FasilitasController@showAllLimit');
		Route::get('fasilitas/data', 'FasilitasController@data');
		Route::post('fasilitas/dataall', 'FasilitasController@dataAll');
		Route::get('kepengurusan/show/{masjid}/{id}', 'KepengurusanController@show');
		Route::get('kepengurusan/showall/{masjid}', 'KepengurusanController@showAll');
		Route::get('kepengurusan/showall/limit/{masjid}', 'KepengurusanController@showAllLimit');
		Route::get('inventaris/show/{masjid}/{id}', 'InventarisController@show');
		Route::get('inventaris/showall/{masjid}', 'InventarisController@showAll');
		Route::get('inventaris/showall/limit/{masjid}', 'InventarisController@showAllLimit');
		Route::get('keuangan/show/{masjid}/{id}', 'KeuanganController@show');
		Route::get('keuangan/showall/{masjid}', 'KeuanganController@showAll');
		Route::get('keuangan/showall/limit/{masjid}', 'KeuanganController@showAllLimit');
		Route::get('keuangan/pdf/{masjid}', 'KeuanganController@pdf');
		Route::get('keuangan/excel/{masjid}', 'KeuanganController@excel');
	});
	Route::post('login', 'PenggunaController@login');
	Route::post('reset/verification', 'PenggunaController@resetVerification');
	Route::post('reset/password', 'PenggunaController@resetPassword');
	Route::group(['middleware' => 'auth:api'], function () {
		Route::get('pengguna/chart', 'PenggunaController@chartPengguna');
		
		Route::post('logout', 'PenggunaController@logout');
		Route::post('fasilitas/store', 'FasilitasController@store');
		Route::post('fasilitas/data', 'FasilitasController@data');
		Route::get('fasilitas/show/{id}', 'FasilitasController@show');
		Route::post('fasilitas/update', 'FasilitasController@update');
		Route::delete('fasilitas/destroy/{id}', 'FasilitasController@destroy');

		Route::post('inventaris/dashboard', 'InventarisController@dashboard');
		Route::post('inventaris/store', 'InventarisController@store');
		Route::post('inventaris/data', 'InventarisController@data');
		Route::get('inventaris/show/{id}', 'InventarisController@show');
		Route::post('inventaris/update', 'InventarisController@update');
		Route::delete('inventaris/destroy/{id}', 'InventarisController@destroy');

		Route::post('kegiatan/dashboard', 'KegiatanController@dashboard');
		Route::post('kegiatan/store', 'KegiatanController@store');
		Route::post('kegiatan/data', 'KegiatanController@data');
		Route::get('kegiatan/show/{id}', 'KegiatanController@show');
		Route::post('kegiatan/update', 'KegiatanController@update');
		Route::delete('kegiatan/destroy/{id}', 'KegiatanController@destroy');

		Route::post('kepengurusan/store', 'KepengurusanController@store');
		Route::post('kepengurusan/data', 'KepengurusanController@data');
		Route::get('kepengurusan/show/{id}', 'KepengurusanController@show');
		Route::post('kepengurusan/update', 'KepengurusanController@update');
		Route::delete('kepengurusan/destroy/{id}', 'KepengurusanController@destroy');

		Route::post('keuangan/store', 'KeuanganController@store');
		Route::post('keuangan/data', 'KeuanganController@data');
		Route::get('keuangan/show/{id}', 'KeuanganController@show');
		Route::post('keuangan/update', 'KeuanganController@update');
		Route::delete('keuangan/destroy/{id}', 'KeuanganController@destroy');

		Route::post('masjid/store', 'MasjidController@store');
		Route::post('masjid/data', 'MasjidController@data');
		Route::get('masjid/show/{id}', 'MasjidController@show');
		Route::post('masjid/update', 'MasjidController@update');
		Route::delete('masjid/destroy/{id}', 'MasjidController@destroy');

		Route::post('pengguna/store', 'PenggunaController@store');
		Route::post('pengguna/data', 'PenggunaController@data');
		Route::get('pengguna/show/{id}', 'PenggunaController@show');
		Route::post('pengguna/update', 'PenggunaController@update');
		Route::delete('pengguna/destroy/{id}', 'PenggunaController@destroy');
		Route::post('laporan/data/keuangan', 'LaporanController@dataKeuangan');
		Route::post('laporan/data/kegiatan', 'LaporanController@dataKegiatan');
		Route::post('laporan/data/inventaris', 'LaporanController@dataInventaris');
		Route::get('user', 'PenggunaController@user');

	});
	Route::get('laporan/keuangan/pdf/{masjid}/{id}', 'LaporanController@pdfKeuangan')->name('pdf.keuangan');
	Route::get('laporan/kegiatan/pdf/{masjid}/{id}', 'LaporanController@pdfKegiatan')->name('pdf.kegiatan');
	Route::get('laporan/inventaris/pdf/{masjid}/{id}', 'LaporanController@pdfInventaris')->name('pdf.inventaris');
	Route::post('laporan/keuangan/chart', 'LaporanController@chartKeuangan');
	Route::post('laporan/inventaris/chart', 'LaporanController@chartInventaris');
	Route::post('laporan/kegiatan/chart', 'LaporanController@chartKegiatan');
	Route::post('laporan/keuangan/pdf/{masjid}', 'LaporanController@pdfKeuanganAll');
	Route::post('laporan/kegiatan/pdf/{masjid}', 'LaporanController@pdfKegiatanAll');
	Route::post('laporan/inventaris/pdf/{masjid}', 'LaporanController@pdfInventarisAll');
	Route::post('laporan/keuangan/excel/{masjid}', 'LaporanController@excelKeuanganAll');
	Route::post('laporan/inventaris/excel/{masjid}', 'LaporanController@excelInventarisAll');
	Route::post('laporan/kegiatan/excel/{masjid}', 'LaporanController@excelKegiatanAll');
	Route::get('laporan/keuangan/excel/{masjid}/{id}', 'LaporanController@excelKeuangan')->name('excel.keuangan');
	Route::get('laporan/kegiatan/excel/{masjid}/{id}', 'LaporanController@excelKegiatan')->name('excel.kegiatan');
	Route::get('laporan/inventaris/excel/{masjid}/{id}', 'LaporanController@excelInventaris')->name('excel.inventaris');
});

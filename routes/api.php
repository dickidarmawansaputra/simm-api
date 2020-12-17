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

Route::group(['prefix' => 'v1', 'namespace' => 'API'], function () {
	Route::group(['prefix' => 'data', 'namespace' => 'Frontend'], function () {
		Route::get('masjid/show/{id}', 'MasjidController@show');
		Route::get('masjid/showall', 'MasjidController@showAll');
		Route::get('kegiatan/show/{id}', 'KegiatanController@show');
		Route::get('kegiatan/showall', 'KegiatanController@showAll');
		Route::get('fasilitas/show/{id}', 'FasilitasController@show');
		Route::get('fasilitas/showall', 'FasilitasController@showAll');
		Route::get('inventaris/show/{id}', 'InventarisController@show');
		Route::get('inventaris/showall', 'InventarisController@showAll');
		Route::get('keuangan/show/{id}', 'KeuanganController@show');
		Route::get('keuangan/showall', 'KeuanganController@showAll');
	});
	Route::post('login', 'PenggunaController@login');
	Route::group(['middleware' => 'auth:api'], function () {
		Route::post('fasilitas/store', 'FasilitasController@store');
		Route::post('fasilitas/data', 'FasilitasController@data');
		Route::get('fasilitas/show/{id}', 'FasilitasController@show');
		Route::put('fasilitas/update', 'FasilitasController@update');
		Route::delete('fasilitas/destroy/{id}', 'FasilitasController@destroy');

		Route::post('inventaris/store', 'InventarisController@store');
		Route::post('inventaris/data', 'InventarisController@data');
		Route::get('inventaris/show/{id}', 'InventarisController@show');
		Route::put('inventaris/update', 'InventarisController@update');
		Route::delete('inventaris/destroy/{id}', 'InventarisController@destroy');

		Route::post('kegiatan/store', 'KegiatanController@store');
		Route::post('kegiatan/data', 'KegiatanController@data');
		Route::get('kegiatan/show/{id}', 'KegiatanController@show');
		Route::put('kegiatan/update', 'KegiatanController@update');
		Route::delete('kegiatan/destroy/{id}', 'KegiatanController@destroy');

		Route::post('kepengurusan/store', 'KepengurusanController@store');
		Route::post('kepengurusan/data', 'KepengurusanController@data');
		Route::get('kepengurusan/show/{id}', 'KepengurusanController@show');
		Route::put('kepengurusan/update', 'KepengurusanController@update');
		Route::delete('kepengurusan/destroy/{id}', 'KepengurusanController@destroy');

		Route::post('keuangan/store', 'KeuanganController@store');
		Route::post('keuangan/data', 'KeuanganController@data');
		Route::get('keuangan/show/{id}', 'KeuanganController@show');
		Route::put('keuangan/update', 'KeuanganController@update');
		Route::delete('keuangan/destroy/{id}', 'KeuanganController@destroy');

		Route::post('masjid/store', 'MasjidController@store');
		Route::post('masjid/data', 'MasjidController@data');
		Route::get('masjid/show/{id}', 'MasjidController@show');
		Route::put('masjid/update', 'MasjidController@update');
		Route::delete('masjid/destroy/{id}', 'MasjidController@destroy');

		Route::post('pengguna/store', 'PenggunaController@store');
		Route::post('pengguna/data', 'PenggunaController@data');
		Route::get('masjid/show/{id}', 'MasjidController@show');
		Route::put('pengguna/update', 'PenggunaController@update');
		Route::delete('pengguna/destroy', 'PenggunaController@destroy');
	});
});
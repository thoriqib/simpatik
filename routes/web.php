<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Petugas;

Route::get('/', function () {
    return view('welcome');
});

// ─── Admin ───────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('petugas', Admin\PetugasController::class);
    Route::resource('shift', Admin\ShiftController::class);
    Route::resource('jadwal', Admin\JadwalController::class);
    Route::resource('jenis-layanan', Admin\JenisLayananController::class);
    Route::resource('pengaduan', Admin\PengaduanController::class);
    Route::get('laporan/antrian', [Admin\LaporanController::class, 'antrian'])->name('laporan.antrian');
    Route::get('laporan/penilaian', [Admin\LaporanController::class, 'penilaian'])->name('laporan.penilaian');
    Route::get('laporan/presensi', [Admin\LaporanController::class, 'presensi'])->name('laporan.presensi');
});

// ─── Petugas ──────────────────────────────────────
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [Petugas\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/presensi/masuk', [Petugas\PresensiController::class, 'masuk'])->name('presensi.masuk');
    Route::post('/presensi/keluar', [Petugas\PresensiController::class, 'keluar'])->name('presensi.keluar');
    Route::get('/jadwal', [Petugas\JadwalController::class, 'index'])->name('jadwal');
    Route::post('/antrian/{id}/panggil', [Petugas\AntrianController::class, 'panggil'])->name('antrian.panggil');
    Route::post('/antrian/{id}/selesai', [Petugas\AntrianController::class, 'selesai'])->name('antrian.selesai');
});

// ─── Publik/Guest (Pengunjung — TANPA LOGIN) ──────
// Semua route di bawah ini dapat diakses tanpa autentikasi
Route::get('/', [App\Http\Controllers\AntrianPublikController::class, 'index'])->name('home');
Route::post('/antrian/ambil', [App\Http\Controllers\AntrianPublikController::class, 'ambil'])->name('antrian.ambil');
Route::get('/antrian/{kode}/tiket', [App\Http\Controllers\AntrianPublikController::class, 'tiket'])->name('antrian.tiket');
Route::get('/display-antrian', [App\Http\Controllers\AntrianPublikController::class, 'display'])->name('antrian.display');
Route::get('/penilaian/{kode}', [App\Http\Controllers\PenilaianController::class, 'create'])->name('penilaian.create');
Route::post('/penilaian', [App\Http\Controllers\PenilaianController::class, 'store'])->name('penilaian.store');
Route::get('/pengaduan', [App\Http\Controllers\PengaduanController::class, 'create'])->name('pengaduan.create');
Route::post('/pengaduan', [App\Http\Controllers\PengaduanController::class, 'store'])->name('pengaduan.store');
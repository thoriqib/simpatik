<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Petugas;
use App\Http\Controllers\AntrianPublikController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\PengaduanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIK / GUEST — Tidak perlu login
|--------------------------------------------------------------------------
*/

// Halaman utama — ambil antrian
Route::get('/', [AntrianPublikController::class, 'index'])->name('home');
Route::post('/antrian/ambil', [AntrianPublikController::class, 'ambil'])->name('antrian.ambil');
Route::get('/antrian/{kode}/tiket', [AntrianPublikController::class, 'tiket'])->name('antrian.tiket');
Route::get('/display-antrian', [AntrianPublikController::class, 'display'])->name('antrian.display');

// Penilaian (diakses dari link di tiket setelah antrian selesai)
Route::get('/penilaian/{kode}', [PenilaianController::class, 'create'])->name('penilaian.create');
Route::post('/penilaian', [PenilaianController::class, 'store'])->name('penilaian.store');

// Pengaduan anonim
Route::get('/pengaduan', [PengaduanController::class, 'create'])->name('pengaduan.create');
Route::post('/pengaduan', [PengaduanController::class, 'store'])->name('pengaduan.store');

// Jadwal petugas — dapat diakses publik tanpa login
Route::get('/jadwal-petugas', [App\Http\Controllers\JadwalPublikController::class, 'index'])
    ->name('jadwal.publik');

/*
|--------------------------------------------------------------------------
| ADMIN — Wajib login + role admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])
            ->name('dashboard');

        // ── Petugas ──────────────────────────────────────────────────
        Route::resource('petugas', Admin\PetugasController::class)
            ->except(['show']);

        // ── Shift Piket ──────────────────────────────────────────────
        Route::resource('shift', Admin\ShiftController::class)
            ->except(['show']);
        Route::patch('shift/{shift}/toggle', [Admin\ShiftController::class, 'toggleAktif'])
            ->name('shift.toggle');

        // ── Jadwal Piket ─────────────────────────────────────────────
        Route::get('jadwal', [Admin\JadwalController::class, 'index'])
            ->name('jadwal.index');
        Route::post('jadwal', [Admin\JadwalController::class, 'store'])
            ->name('jadwal.store');
        Route::delete('jadwal/{jadwal}', [Admin\JadwalController::class, 'destroy'])
            ->name('jadwal.destroy');

        // ── Jenis Layanan ────────────────────────────────────────────
        Route::resource('jenis-layanan', Admin\JenisLayananController::class)
            ->except(['show']);

        // ── Pengaduan ────────────────────────────────────────────────
        Route::get('pengaduan', [Admin\PengaduanController::class, 'index'])
            ->name('pengaduan.index');
        Route::get('pengaduan/{pengaduan}', [Admin\PengaduanController::class, 'show'])
            ->name('pengaduan.show');
        Route::post('pengaduan/{pengaduan}/tanggapi', [Admin\PengaduanController::class, 'tanggapi'])
            ->name('pengaduan.tanggapi');
        Route::patch('pengaduan/{pengaduan}/status', [Admin\PengaduanController::class, 'updateStatus'])
            ->name('pengaduan.status');

        // ── Laporan ──────────────────────────────────────────────────
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('antrian',  [Admin\LaporanController::class, 'antrian'])
                ->name('antrian');
            Route::get('penilaian', [Admin\LaporanController::class, 'penilaian'])
                ->name('penilaian');
            Route::get('presensi', [Admin\LaporanController::class, 'presensi'])
                ->name('presensi');
        });

        // Di dalam group admin
        Route::prefix('penilaian')->name('penilaian.')->group(function () {
            Route::get('/',                [Admin\PenilaianController::class, 'index'])  ->name('index');
            Route::get('/{penilaian}',    [Admin\PenilaianController::class, 'show'])   ->name('show');
            Route::delete('/{penilaian}', [Admin\PenilaianController::class, 'destroy'])->name('destroy');
        });
    });

/*
|--------------------------------------------------------------------------
| PETUGAS — Wajib login + role petugas
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:petugas'])
    ->prefix('petugas')
    ->name('petugas.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [Petugas\DashboardController::class, 'index'])
            ->name('dashboard');

        // ── Jadwal ───────────────────────────────────────────────────
        Route::get('/jadwal', [Petugas\JadwalController::class, 'index'])
            ->name('jadwal');

        // ── Presensi ─────────────────────────────────────────────────
        Route::get('/presensi', [Petugas\PresensiController::class, 'index'])
            ->name('presensi.index');
        Route::post('/presensi/masuk', [Petugas\PresensiController::class, 'masuk'])
            ->name('presensi.masuk');
        Route::post('/presensi/keluar', [Petugas\PresensiController::class, 'keluar'])
            ->name('presensi.keluar');

        // ── Antrian ──────────────────────────────────────────────────
        Route::post('/antrian/{antrian}/panggil', [Petugas\AntrianController::class, 'panggil'])
            ->name('antrian.panggil');
        Route::post('/antrian/{antrian}/mulai', [Petugas\AntrianController::class, 'mulaiLayani'])
            ->name('antrian.mulai');
        Route::post('/antrian/{antrian}/selesai', [Petugas\AntrianController::class, 'selesai'])
            ->name('antrian.selesai');
        Route::post('/antrian/{antrian}/batal', [Petugas\AntrianController::class, 'batal'])
            ->name('antrian.batal');
    });

/*
|--------------------------------------------------------------------------
| AUTH — Di-generate oleh Laravel Breeze
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
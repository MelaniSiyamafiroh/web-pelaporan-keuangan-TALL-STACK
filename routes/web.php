<?php

use App\Livewire\Pages\Kelola\KelolaAkunPage;
use App\Livewire\Pages\Pembukuan\PembukuanTahunanPage;
use Illuminate\Support\Facades\Route;

// Pages
use App\Livewire\Pages\Dashboard;
use App\Livewire\Pages\SettingPage;

// User Management
use App\Livewire\Pages\User\UserPage;
use App\Livewire\Pages\User\UserProfile;

// Pelaporan
use App\Livewire\Pages\Pelaporan\DaftarPelaporan;
use App\Livewire\Pages\Pelaporan\LaporanMasukPage;

// Kegiatan & Subkegiatan
use App\Livewire\Pages\Kelola\KelolaKegiatanPage;
use App\Livewire\Pages\Kelola\KelolaSubkegiatanPage;

Route::middleware(['auth'])->group(function () {

    // ✅ Dashboard dapat diakses semua pengguna login
    Route::get('/', Dashboard::class)->name('home');
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // ✅ Halaman Daftar Pelaporan (untuk PPTK)
    Route::get('/daftar-pelaporan', DaftarPelaporan::class)
        ->middleware('permission:akses_daftar_pelaporan')
        ->name('daftar-pelaporan');

    // ✅ Halaman Laporan Masuk (untuk verifikator/bendahara/kepala dinas)
    Route::get('/laporan-masuk', LaporanMasukPage::class)
        ->middleware('permission:verifikasi_laporan')
        ->name('laporan-masuk');

    // ✅ Halaman Kelola Kegiatan (Admin)
    Route::get('/kelola-kegiatan', KelolaKegiatanPage::class)
        ->middleware('permission:input_anggaran')
        ->name('kelola-kegiatan');

    // ✅ Halaman Kelola Subkegiatan (Admin)
    Route::get('/kelola-subkegiatan', KelolaSubkegiatanPage::class)
        ->middleware('permission:input_anggaran')
        ->name('kelola-subkegiatan');

    // ✅ Kelola Akun
    Route::get('/user', UserPage::class)
        ->middleware('permission:kelola_pengguna')
        ->name('user');

    // ✅ Profil pengguna
    Route::get('/profil', UserProfile::class)
        ->name('profil');

    // ✅ Halaman Pembukuan Laporan Tahunan (Admin)
    Route::get('/pembukuan-tahunan', PembukuanTahunanPage::class)
        ->middleware('permission:kelola_pengguna') // atau buat permission baru: pembukuan_laporan
        ->name('pembukuan-tahunan');

    Route::get('/kelola-akun', KelolaAkunPage::class)
        ->middleware('permission:kelola_pengguna') // atau buat permission baru: pembukuan_laporan
        ->name('kelola-akun');


});

// Auth routes
require __DIR__.'/auth.php';

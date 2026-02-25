<?php

use Illuminate\Support\Facades\Route;
use App\Http\Admin\IpcrfController;
use App\Http\Admin\NoticeController;
use App\Http\Admin\FormController;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [IpcrfController::class, 'dashboard'])->name('dashboard');
    
    // Upload - No role selection, direct to form
    Route::get('/upload', [IpcrfController::class, 'uploadForm'])->name('upload');
    Route::post('/upload', [IpcrfController::class, 'store'])->name('upload.store');
    
    Route::get('/records', [IpcrfController::class, 'records'])->name('records');
    Route::get('/records/{id}/download', [IpcrfController::class, 'download'])->name('records.download');
    Route::get('/api/provinces/{province}/municipalities', [IpcrfController::class, 'getMunicipalities']);
    
    Route::get('/notices', [NoticeController::class, 'index'])->name('notices');
    Route::post('/notices', [NoticeController::class, 'store'])->name('notices.store');
    Route::delete('/notices/{id}', [NoticeController::class, 'destroy'])->name('notices.destroy');
    
    Route::get('/forms', [FormController::class, 'index'])->name('forms');
    Route::post('/forms', [FormController::class, 'store'])->name('forms.store');
});
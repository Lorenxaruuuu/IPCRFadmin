<?php

use Illuminate\Support\Facades\Route;
use App\Http\Admin\IpcrfController;
use App\Http\Admin\NoticeController;
use App\Http\Admin\FormController;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

Route::prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [IpcrfController::class, 'dashboard'])->name('dashboard');
    
    // Upload - Single page, no steps
    Route::get('/upload', [IpcrfController::class, 'uploadForm'])->name('upload');
    Route::post('/upload', [IpcrfController::class, 'store'])->name('upload.store');
    
    // Records
    Route::get('/records', [IpcrfController::class, 'records'])->name('records');
    Route::get('/records/{id}/download', [IpcrfController::class, 'download'])->name('records.download');
    
    // API for cascading dropdowns
    Route::get('/api/provinces/{province}/municipalities', [IpcrfController::class, 'getMunicipalities']);
    Route::get('/api/municipalities/{municipality}/schools', [IpcrfController::class, 'getSchools']);
    
    // Notices
    Route::get('/notices', [NoticeController::class, 'index'])->name('notices');
    Route::post('/notices', [NoticeController::class, 'store'])->name('notices.store');
    Route::delete('/notices/{id}', [NoticeController::class, 'destroy'])->name('notices.destroy');
    
    // Forms
    Route::get('/forms', [FormController::class, 'index'])->name('forms');
    Route::post('/forms', [FormController::class, 'store'])->name('forms.store');

    Route::get('/forms/{id}/download', [FormController::class, 'download'])->name('forms.download');
    Route::delete('/forms/{id}', [FormController::class, 'destroy'])->name('forms.destroy');
});
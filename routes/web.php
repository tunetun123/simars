<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\SsoLoginController;
use App\Livewire\Auth\Login;
use App\Livewire\AppSwitcher;
use App\Livewire\Sigap\Dashboard;
use App\Livewire\Sigap\Categories;
use App\Livewire\Sigap\Documents;

Route::get('/', Login::class)->name('login');

Route::get('/auth/{provider}/redirect', [SsoLoginController::class, 'redirect'])->name('sso.redirect');
Route::get('/auth/{provider}/callback', [SsoLoginController::class, 'callback'])->name('sso.callback');

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');

    // App Switcher
    Route::get('/apps', AppSwitcher::class)->name('apps');

    // Sigap Module - Protected
    Route::middleware(['module.access:access sigap'])->group(function () {
        Route::get('/sigap', Dashboard::class)->name('sigap.dashboard');
        Route::get('/sigap/categories', Categories::class)->name('sigap.categories');
        Route::get('/sigap/documents', Documents::class)->name('sigap.documents');

        Route::get('/sigap/documents/{document}/download', function (\Modules\Sigap\Models\Document $document) {
            if (!\Illuminate\Support\Facades\Storage::disk('local')->exists($document->file_path)) {
                abort(404, 'File tidak ditemukan di server.');
            }
            return response()->download(
                storage_path('app/private/' . $document->file_path),
                $document->file_name ?? 'document.pdf',
                ['Content-Type' => $document->file_mime ?? 'application/pdf']
            );
        })->name('sigap.documents.download');

        Route::get('/sigap/documents/{document}/view', function (\Modules\Sigap\Models\Document $document) {
            if (!\Illuminate\Support\Facades\Storage::disk('local')->exists($document->file_path)) {
                abort(404, 'File tidak ditemukan di server.');
            }
            return response()->file(
                storage_path('app/private/' . $document->file_path),
                ['Content-Type' => $document->file_mime ?? 'application/pdf']
            );
        })->name('sigap.documents.view');
    });

    // Administrator Module - Protected
    Route::middleware(['module.access:access admin'])->prefix('admin')->group(function () {
        Route::get('/users', \App\Livewire\Administrator\Users::class)->name('admin.users');
        Route::get('/roles', \App\Livewire\Administrator\Roles::class)->name('admin.roles');
        Route::get('/settings', \App\Livewire\Administrator\Settings::class)->name('admin.settings');
    });
});

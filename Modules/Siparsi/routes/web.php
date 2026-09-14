<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Siparsi\Dashboard;
use App\Livewire\Siparsi\Groups;
use App\Livewire\Siparsi\Categories;
use App\Livewire\Siparsi\AssessmentElements;
use App\Livewire\Siparsi\Documents;
use Modules\Siparsi\Models\Document;
use Illuminate\Support\Facades\Storage;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/siparsi', Dashboard::class)->name('siparsi.dashboard');
    Route::get('/siparsi/groups', Groups::class)->name('siparsi.groups');
    Route::get('/siparsi/categories', Categories::class)->name('siparsi.categories');
    Route::get('/siparsi/assessment-elements', AssessmentElements::class)->name('siparsi.assessment-elements');
    Route::get('/siparsi/documents', Documents::class)->name('siparsi.documents');

    // Document Download Route
    Route::get('/siparsi/documents/{document}/download', function (Document $document) {
        if (!Storage::disk('local')->exists($document->file_path)) {
            abort(404, 'File tidak ditemukan di server.');
        }
        return response()->download(
            storage_path('app/private/' . $document->file_path),
            $document->file_name ?? 'document'
        );
    })->name('siparsi.documents.download');

    // Document View (Stream) Route
    Route::get('/siparsi/documents/{document}/view', function (Document $document) {
        if (!Storage::disk('local')->exists($document->file_path)) {
            abort(404, 'File tidak ditemukan di server.');
        }
        return response()->file(
            storage_path('app/private/' . $document->file_path)
        );
    })->name('siparsi.documents.view');
});

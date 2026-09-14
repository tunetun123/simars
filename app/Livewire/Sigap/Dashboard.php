<?php

namespace App\Livewire\Sigap;

use Livewire\Component;
use Modules\Sigap\Models\Document;
use Modules\Sigap\Models\DocumentCategory;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.sigap')]
#[Title('Dashboard Sigap')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.sigap.dashboard', [
            'totalCategories' => DocumentCategory::count(),
            'totalDocuments'  => Document::count(),
            'recentDocuments' => Document::with(['category', 'uploader'])->latest()->take(5)->get(),
        ]);
    }
}

<?php

namespace App\Livewire\Siparsi;

use Livewire\Component;
use Modules\Siparsi\Models\DocumentGroup;
use Modules\Siparsi\Models\DocumentCategory;
use Modules\Siparsi\Models\AssessmentElement;
use Modules\Siparsi\Models\Document;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.siparsi')]
#[Title('Dashboard SIPARSI')]
class Dashboard extends Component
{
    public function mount()
    {
        $user = auth()->user();
        $hasSiparsiAccess = $user->can('access siparsi');

        if (!$hasSiparsiAccess) {
            foreach ($user->getAllPermissions() as $perm) {
                if (str_starts_with($perm->name, 'upload siparsi ')) {
                    $hasSiparsiAccess = true;
                    break;
                }
            }
        }

        abort_if(!$hasSiparsiAccess, 403, 'Anda tidak memiliki akses ke halaman ini.');
    }
    public function render()
    {
        return view('livewire.siparsi.dashboard', [
            'totalGroups' => DocumentGroup::count(),
            'totalCategories' => DocumentCategory::count(),
            'totalElements' => AssessmentElement::count(),
            'totalDocuments' => Document::count(),
            'recentDocuments' => Document::with(['assessmentElement.category.group', 'uploader'])->latest()->take(5)->get(),
        ]);
    }
}

<?php

namespace App\Livewire\Siparsi;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Modules\Siparsi\Models\Document;
use Modules\Siparsi\Models\AssessmentElement;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.siparsi')]
#[Title('Arsip Akreditasi')]
class Documents extends Component
{
    use WithPagination, WithFileUploads;

    public $title, $assessment_element_id, $assessment_element_name, $sub_point, $file, $documentId;
    public $isModalOpen = false;

    // Master-Detail Filters
    public $selectedCategoryId = null;
    public $searchStandard = '';
    public $canUploadCategory = false;

    // Preview
    public $previewDoc = null;
    public $isPreviewModalOpen = false;

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'assessment_element_id' => 'required|exists:siparsi_assessment_elements,id',
            'sub_point' => 'required|string|max:10',
            'file' => $this->documentId ? 'nullable|file|mimes:pdf,jpeg,png,jpg,doc,docx,xls,xlsx|max:20480' : 'required|file|mimes:pdf,jpeg,png,jpg,doc,docx,xls,xlsx|max:20480',
        ];
    }

    public function mount()
    {
        $user = auth()->user();
        $hasSiparsiView = $user->can('view siparsi documents');

        if (!$hasSiparsiView) {
            foreach ($user->getAllPermissions() as $perm) {
                if (str_starts_with($perm->name, 'upload siparsi ')) {
                    $hasSiparsiView = true;
                    break;
                }
            }
        }

        abort_if(!$hasSiparsiView, 403, 'Anda tidak memiliki akses ke halaman ini.');

        $firstCategory = \Modules\Siparsi\Models\DocumentCategory::first();
        if ($firstCategory) {
            $this->selectCategory($firstCategory->id);
        }
    }

    public function selectCategory($id)
    {
        $this->selectedCategoryId = $id;

        $user = Auth::user();
        if ($user->can('create siparsi documents') || $user->hasRole('Super Admin')) {
            $this->canUploadCategory = true;
        } else {
            $category = \Modules\Siparsi\Models\DocumentCategory::find($id);
            $this->canUploadCategory = false;
            if ($category) {
                preg_match('/\((.*?)\)/', $category->name, $matches);
                $acronym = $matches[1] ?? '';
                if ($acronym && $user->can('upload siparsi ' . strtolower($acronym))) {
                    $this->canUploadCategory = true;
                }
            }
        }
    }

    public function updatedSubPoint($value)
    {
        $this->sub_point = strtoupper($value);
    }

    public function render()
    {
        // MASTER: Get Groups with Categories (Filtered by search)
        $groups = \Modules\Siparsi\Models\DocumentGroup::with(['categories' => function ($q) {
            if ($this->searchStandard) {
                $q->where('name', 'like', '%' . $this->searchStandard . '%');
            }
        }])->whereHas('categories', function ($q) {
            if ($this->searchStandard) {
                $q->where('name', 'like', '%' . $this->searchStandard . '%');
            }
        })->get();

        // If search removes groups but they have matching categories, the above logic handles it.
        // If there's no search, just get all groups.
        if (empty($this->searchStandard)) {
            $groups = \Modules\Siparsi\Models\DocumentGroup::with('categories')->get();
        }

        // DETAIL: Get Assessment Elements and Documents for selected Category
        $selectedCategory = null;
        if ($this->selectedCategoryId) {
            $selectedCategory = \Modules\Siparsi\Models\DocumentCategory::with(['assessmentElements.documents.uploader'])->find($this->selectedCategoryId);
        }

        return view('livewire.siparsi.documents', [
            'groups' => $groups,
            'selectedCategory' => $selectedCategory,
        ]);
    }

    private function authorizeCategory($categoryId)
    {
        $user = Auth::user();
        if ($user->can('create siparsi documents') || $user->hasRole('Super Admin')) {
            return true;
        }

        $category = \Modules\Siparsi\Models\DocumentCategory::find($categoryId);
        if ($category) {
            preg_match('/\((.*?)\)/', $category->name, $matches);
            $acronym = $matches[1] ?? '';
            if ($acronym && $user->can('upload siparsi ' . strtolower($acronym))) {
                return true;
            }
        }

        abort(403, 'Anda tidak memiliki hak akses untuk mengubah dokumen di standar ini.');
    }

    public function create($epId = null)
    {
        $this->resetInputFields();
        if ($epId) {
            $ep = AssessmentElement::find($epId);
            if ($ep) {
                $this->authorizeCategory($ep->document_category_id);
                $this->assessment_element_id = $ep->id;
                $this->assessment_element_name = $ep->name;
            }
        }
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $document = Document::findOrFail($id);
        $this->authorizeCategory($document->assessmentElement->document_category_id);

        $this->documentId = $document->id;
        $this->title = $document->title;
        $this->assessment_element_id = $document->assessment_element_id;
        $this->assessment_element_name = $document->assessmentElement?->name;
        $this->sub_point = $document->sub_point;
        $this->file = null; // Don't bind file to input

        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate();

        $ep = AssessmentElement::findOrFail($this->assessment_element_id);
        $this->authorizeCategory($ep->document_category_id);

        $data = [
            'title' => $this->title,
            'assessment_element_id' => $this->assessment_element_id,
            'sub_point' => strtoupper($this->sub_point),
        ];

        if ($this->file) {
            $path = $this->file->store('siparsi_documents', 'local');
            $data['file_path'] = $path;
            $data['file_name'] = $this->file->getClientOriginalName();
            $data['file_size'] = $this->file->getSize();
            $data['file_type'] = $this->file->getClientOriginalExtension();
        }

        if ($this->documentId) {
            $document = Document::find($this->documentId);
            if ($this->file && $document->file_path && Storage::disk('local')->exists($document->file_path)) {
                Storage::disk('local')->delete($document->file_path);
            }
            $document->update($data);
        } else {
            $data['uploaded_by'] = Auth::id() ?? 1;
            Document::create($data);
        }

        $this->isModalOpen = false;
        $this->resetInputFields();

        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text' => $this->documentId ? 'Dokumen berhasil diubah.' : 'Dokumen berhasil ditambahkan.',
            'icon' => 'success'
        ]);
    }

    public function delete($id)
    {
        $doc = Document::find($id);
        if ($doc) {
            $this->authorizeCategory($doc->assessmentElement->document_category_id);
            $doc->delete(); // event triggers file deletion
            $this->dispatch('swal', [
                'title' => 'Terhapus!',
                'text' => 'Dokumen berhasil dihapus dari sistem.',
                'icon' => 'success'
            ]);
        }
    }

    public function preview($id)
    {
        $this->previewDoc = Document::findOrFail($id);
        $this->isPreviewModalOpen = true;
    }

    public function closePreviewModal()
    {
        $this->isPreviewModalOpen = false;
        $this->previewDoc = null;
    }

    private function resetInputFields()
    {
        $this->title = '';
        $this->assessment_element_id = '';
        $this->assessment_element_name = '';
        $this->sub_point = '';
        $this->file = null;
        $this->documentId = null;
    }
}

<?php

namespace App\Livewire\Sigap;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Modules\Sigap\Models\Document;
use Modules\Sigap\Models\DocumentCategory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.sigap')]
#[Title('Arsip Dokumen')]
class Documents extends Component
{
    use WithPagination, WithFileUploads;

    public $title, $description, $document_category_id, $file, $documentId, $document_date;
    public $isModalOpen = 0;
    public $filterCategory = '';
    public $viewMode = 'table'; // Default view

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
    }

    public function mount()
    {
        abort_if(!auth()->user()->can('view sigap documents'), 403, 'Anda tidak memiliki akses ke halaman ini.');
    }

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'document_category_id' => 'required|exists:document_categories,id',
            'description' => 'nullable|string',
            'document_date' => 'nullable|date',
            'file' => $this->documentId ? 'nullable|file|mimes:pdf,jpeg,png,jpg|max:20480' : 'required|file|mimes:pdf,jpeg,png,jpg|max:20480',
        ];
    }

    public function render()
    {
        $query = Document::with(['category', 'uploader'])->latest();

        if ($this->filterCategory) {
            $query->where('document_category_id', $this->filterCategory);
        }

        return view('livewire.sigap.documents', [
            'documents' => $query->paginate(10),
            'categories' => DocumentCategory::all(),
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->title = '';
        $this->description = '';
        $this->document_category_id = '';
        $this->document_date = '';
        $this->file = null;
        $this->documentId = '';
    }

    public function store()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'document_category_id' => $this->document_category_id,
            'document_date' => $this->document_date,
        ];

        // Assign uploader on create
        if (!$this->documentId) {
            $data['uploaded_by'] = Auth::id() ?? 1;
        }

        if ($this->file) {
            // Save to local disk instead of public for security
            $path = $this->file->store('sigap_documents', 'local');
            $data['file_path'] = $path;
            $data['file_name'] = $this->file->getClientOriginalName();
            $data['file_size'] = $this->file->getSize();
            $data['file_mime'] = $this->file->getMimeType();

            // Delete old file if updating
            if ($this->documentId) {
                $oldDoc = Document::find($this->documentId);
                if ($oldDoc && Storage::disk('local')->exists($oldDoc->file_path)) {
                    Storage::disk('local')->delete($oldDoc->file_path);
                }
            }
        }

        Document::updateOrCreate(['id' => $this->documentId], $data);

        $this->dispatch('swal', [
            'title' => 'Sukses!',
            'text' => $this->documentId ? 'Arsip Dokumen berhasil diperbarui.' : 'Arsip Dokumen berhasil diunggah.',
            'icon' => 'success'
        ]);

        $this->closeModal();
    }

    public function edit($id)
    {
        $doc = Document::findOrFail($id);
        $this->documentId = $id;
        $this->title = $doc->title;
        $this->description = $doc->description;
        $this->document_category_id = $doc->document_category_id;
        $this->document_date = $doc->document_date ? $doc->document_date->format('Y-m-d') : '';

        $this->openModal();
    }

    // Modal Preview Document
    public $previewDoc = null;
    public $isPreviewModalOpen = false;

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

    public function delete($id)
    {
        $doc = Document::find($id);
        if ($doc) {
            // Model deleting event handles physical file deletion
            $doc->delete();

            $this->dispatch('swal', [
                'title' => 'Terhapus!',
                'text' => 'Dokumen berhasil dihapus dari sistem.',
                'icon' => 'success'
            ]);
        }
    }
}

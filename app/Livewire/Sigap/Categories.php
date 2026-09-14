<?php

namespace App\Livewire\Sigap;

use Livewire\Component;
use Modules\Sigap\Models\DocumentCategory;
use Livewire\WithPagination;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.sigap')]
#[Title('Kategori Dokumen')]
class Categories extends Component
{
    use WithPagination;

    public $name, $description, $categoryId;
    public $isModalOpen = 0;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:document_categories,name,' . $this->categoryId,
            'description' => 'nullable|string',
        ];
    }

    public function mount()
    {
        abort_if(!auth()->user()->can('manage sigap categories'), 403, 'Anda tidak memiliki akses ke halaman ini.');
    }

    public function render()
    {
        return view('livewire.sigap.categories', [
            'categories' => DocumentCategory::latest()->paginate(10),
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
        $this->name = '';
        $this->description = '';
        $this->categoryId = '';
    }

    public function store()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
        ];

        if (!$this->categoryId) {
            $data['created_by'] = \Illuminate\Support\Facades\Auth::id() ?? 1;
        }

        DocumentCategory::updateOrCreate(['id' => $this->categoryId], $data);

        $this->dispatch('swal', [
            'title' => 'Sukses!',
            'text' => $this->categoryId ? 'Kategori berhasil diperbarui.' : 'Kategori berhasil ditambahkan.',
            'icon' => 'success'
        ]);

        $this->closeModal();
    }

    public function edit($id)
    {
        $category = DocumentCategory::findOrFail($id);
        $this->categoryId = $id;
        $this->name = $category->name;
        $this->description = $category->description;

        $this->openModal();
    }

    public function delete($id)
    {
        $this->authorize('manage sigap categories');

        DocumentCategory::find($id)->delete();
        $this->dispatch('swal', [
            'title' => 'Terhapus!',
            'text' => 'Kategori berhasil dihapus.',
            'icon' => 'success'
        ]);
    }
}

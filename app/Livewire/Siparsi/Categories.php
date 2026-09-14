<?php

namespace App\Livewire\Siparsi;

use Livewire\Component;
use Modules\Siparsi\Models\DocumentCategory;
use Modules\Siparsi\Models\DocumentGroup;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.siparsi')]
#[Title('Standar Akreditasi')]
class Categories extends Component
{
    use WithPagination;

    public $name, $document_group_id, $categoryId;
    public $isModalOpen = false;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'document_group_id' => 'required|exists:siparsi_document_groups,id',
        ];
    }

    public function mount()
    {
        abort_if(!auth()->user()->can('manage siparsi categories'), 403, 'Anda tidak memiliki akses ke halaman ini.');
    }

    public function render()
    {
        return view('livewire.siparsi.categories', [
            'categories' => DocumentCategory::with('group')->withCount('assessmentElements')->latest()->paginate(10),
            'groups' => DocumentGroup::all(),
        ]);
    }

    public function create()
    {
        $this->resetFields();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $category = DocumentCategory::findOrFail($id);
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->document_group_id = $category->document_group_id;
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate();

        DocumentCategory::updateOrCreate(
            ['id' => $this->categoryId],
            [
                'name' => $this->name,
                'document_group_id' => $this->document_group_id
            ]
        );

        $this->isModalOpen = false;
        $this->resetFields();

        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text' => 'Standar Akreditasi berhasil disimpan.',
            'icon' => 'success'
        ]);
    }

    public function delete($id)
    {
        DocumentCategory::findOrFail($id)->delete();
        $this->dispatch('swal', [
            'title' => 'Terhapus!',
            'text' => 'Standar Akreditasi berhasil dihapus.',
            'icon' => 'success'
        ]);
    }

    public function resetFields()
    {
        $this->name = '';
        $this->document_group_id = '';
        $this->categoryId = null;
    }
}

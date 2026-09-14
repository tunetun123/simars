<?php

namespace App\Livewire\Siparsi;

use Livewire\Component;
use Modules\Siparsi\Models\AssessmentElement;
use Modules\Siparsi\Models\DocumentCategory;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.siparsi')]
#[Title('Elemen Penilaian (EP)')]
class AssessmentElements extends Component
{
    use WithPagination;

    public $name, $document_category_id, $elementId;
    public $isModalOpen = false;
    public $filterCategory = '';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'document_category_id' => 'required|exists:siparsi_document_categories,id',
        ];
    }

    public function mount()
    {
        abort_if(!auth()->user()->can('manage siparsi ep'), 403, 'Anda tidak memiliki akses ke halaman ini.');
    }

    public function render()
    {
        $query = AssessmentElement::with(['category.group'])->withCount('documents')->latest();

        if ($this->filterCategory) {
            $query->where('document_category_id', $this->filterCategory);
        }

        return view('livewire.siparsi.assessment-elements', [
            'elements' => $query->paginate(10),
            'categories' => DocumentCategory::with('group')->get()->sortBy('group.name'),
        ]);
    }

    public function create()
    {
        $this->resetFields();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $element = AssessmentElement::findOrFail($id);
        $this->elementId = $element->id;
        $this->name = $element->name;
        $this->document_category_id = $element->document_category_id;
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate();

        AssessmentElement::updateOrCreate(
            ['id' => $this->elementId],
            [
                'name' => $this->name,
                'document_category_id' => $this->document_category_id
            ]
        );

        $this->isModalOpen = false;
        $this->resetFields();

        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text' => 'Elemen Penilaian berhasil disimpan.',
            'icon' => 'success'
        ]);
    }

    public function delete($id)
    {
        AssessmentElement::findOrFail($id)->delete();
        $this->dispatch('swal', [
            'title' => 'Terhapus!',
            'text' => 'Elemen Penilaian berhasil dihapus.',
            'icon' => 'success'
        ]);
    }

    public function resetFields()
    {
        $this->name = '';
        $this->document_category_id = '';
        $this->elementId = null;
    }
}

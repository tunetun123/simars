<?php

namespace App\Livewire\Siparsi;

use Livewire\Component;
use Modules\Siparsi\Models\DocumentGroup;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.siparsi')]
#[Title('Bab Akreditasi')]
class Groups extends Component
{
    use WithPagination;

    public $name, $groupId;
    public $isModalOpen = false;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:siparsi_document_groups,name,' . $this->groupId,
        ];
    }

    public function mount()
    {
        abort_if(!auth()->user()->can('manage siparsi groups'), 403, 'Anda tidak memiliki akses ke halaman ini.');
    }

    public function render()
    {
        return view('livewire.siparsi.groups', [
            'groups' => DocumentGroup::withCount('categories')->latest()->paginate(10),
        ]);
    }

    public function create()
    {
        $this->resetFields();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $group = DocumentGroup::findOrFail($id);
        $this->groupId = $group->id;
        $this->name = $group->name;
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate();

        DocumentGroup::updateOrCreate(
            ['id' => $this->groupId],
            ['name' => $this->name]
        );

        $this->isModalOpen = false;
        $this->resetFields();

        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text' => 'Bab Akreditasi berhasil disimpan.',
            'icon' => 'success'
        ]);
    }

    public function delete($id)
    {
        DocumentGroup::findOrFail($id)->delete();
        $this->dispatch('swal', [
            'title' => 'Terhapus!',
            'text' => 'Bab Akreditasi berhasil dihapus.',
            'icon' => 'success'
        ]);
    }

    public function resetFields()
    {
        $this->name = '';
        $this->groupId = null;
    }
}

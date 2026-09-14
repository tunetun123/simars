<?php

namespace App\Livewire\Administrator;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Livewire\WithPagination;

class Roles extends Component
{
    use WithPagination;

    public $roleId, $name;
    public $selectedPermissions = [];
    public $isModalOpen = false;

    public function render()
    {
        $permissions = Permission::all()->groupBy(function ($perm) {
            if (str_starts_with($perm->name, 'manage users') || str_starts_with($perm->name, 'manage roles') || str_starts_with($perm->name, 'access admin')) {
                return 'Administrator (Sistem)';
            }
            if (str_contains($perm->name, 'sigap')) {
                return 'Modul SIGAP';
            }
            if (str_starts_with($perm->name, 'upload siparsi')) {
                return 'SIPARSI (Hak Akses Per Standar)';
            }
            if (str_contains($perm->name, 'siparsi')) {
                return 'Modul SIPARSI (Global)';
            }
            return 'Lainnya';
        });

        return view('livewire.administrator.roles', [
            'roles' => Role::with('permissions')->latest()->paginate(10),
            'permissionGroups' => $permissions
        ])->layout('components.layouts.admin')->title('Manajemen Role & Akses');
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
        $this->roleId = '';
        $this->selectedPermissions = [];
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|unique:roles,name,' . $this->roleId,
            'selectedPermissions' => 'array',
        ]);

        $role = Role::updateOrCreate(['id' => $this->roleId], ['name' => $this->name]);

        // Sync permissions
        $role->syncPermissions($this->selectedPermissions);

        $this->dispatch('swal', [
            'title' => 'Sukses!',
            'text' => $this->roleId ? 'Role berhasil diperbarui.' : 'Role baru berhasil ditambahkan.',
            'icon' => 'success'
        ]);

        $this->closeModal();
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);

        // Prevent editing Super Admin
        if ($role->name === 'Super Admin') {
            $this->dispatch('swal', [
                'title' => 'Akses Ditolak!',
                'text' => 'Role Super Admin tidak dapat diubah.',
                'icon' => 'error'
            ]);
            return;
        }

        $this->roleId = $id;
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();

        $this->openModal();
    }

    public function delete($id)
    {
        $role = Role::findOrFail($id);

        if ($role->name === 'Super Admin') {
            $this->dispatch('swal', [
                'title' => 'Akses Ditolak!',
                'text' => 'Role Super Admin tidak dapat dihapus.',
                'icon' => 'error'
            ]);
            return;
        }

        $role->delete();

        $this->dispatch('swal', [
            'title' => 'Terhapus!',
            'text' => 'Role berhasil dihapus.',
            'icon' => 'success'
        ]);
    }
}

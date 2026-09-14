<?php

namespace App\Livewire\Administrator;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class Users extends Component
{
    use WithPagination;

    public $name, $email, $password, $role, $userId;
    public $isModalOpen = 0;

    public function render()
    {
        return view('livewire.administrator.users', [
            'users' => User::with('roles')->latest()->paginate(10),
            'roles' => Role::all(),
        ])->layout('components.layouts.admin')->title('Manajemen Pengguna');
    }

    public function create()
    {
        $this->resetInputFields();
        // Set default password sesuai persetujuan user
        $this->password = 'password123';
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
        $this->email = '';
        $this->password = '';
        $this->role = '';
        $this->userId = '';
    }

    public function store()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($this->userId)],
            'role' => 'required|exists:roles,name',
        ];

        // Password required if creating new
        if (!$this->userId) {
            $rules['password'] = 'required|string|min:6';
        } else {
            $rules['password'] = 'nullable|string|min:6';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $user = User::updateOrCreate(['id' => $this->userId], $data);
        
        // Sync Roles
        $user->syncRoles([$this->role]);

        $this->dispatch('swal', [
            'title' => 'Sukses!',
            'text' => $this->userId ? 'Data pengguna berhasil diperbarui.' : 'Pengguna baru berhasil ditambahkan.',
            'icon' => 'success'
        ]);

        $this->closeModal();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->roles->first()?->name ?? '';
        $this->password = ''; // Kosongkan saat edit, hanya diisi jika ingin diganti
        
        $this->openModal();
    }

    public function delete($id)
    {
        if (auth()->id() == $id) {
            $this->dispatch('swal', [
                'title' => 'Ditolak!',
                'text' => 'Anda tidak dapat menghapus akun Anda sendiri.',
                'icon' => 'error'
            ]);
            return;
        }

        User::find($id)->delete();
        $this->dispatch('swal', [
            'title' => 'Terhapus!',
            'text' => 'Pengguna berhasil dihapus.',
            'icon' => 'success'
        ]);
    }
}

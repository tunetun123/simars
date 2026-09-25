<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ChangePassword extends Component
{
    public $current_password;
    public $password;
    public $password_confirmation;

    protected $rules = [
        'current_password' => 'required',
        'password' => 'required|min:6|confirmed',
    ];

    public function updatePassword()
    {
        \Illuminate\Support\Facades\Log::info('updatePassword dipanggil!');

        $this->validate();

        \Illuminate\Support\Facades\Log::info('Validasi lolos!');

        $user = Auth::user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Password saat ini salah.');
            return;
        }

        // Prevent setting password123 again
        if ($this->password === 'password123') {
            $this->addError('password', 'Anda tidak boleh menggunakan password default.');
            return;
        }

        $user->password = Hash::make($this->password);
        $user->save();

        return redirect()->route('apps')->with('success', 'Password berhasil diubah!');
    }

    public function render()
    {
        return view('livewire.auth.change-password')
            ->layout('layouts.guest')->title('Ubah Password');
    }
}

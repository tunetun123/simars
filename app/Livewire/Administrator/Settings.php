<?php

namespace App\Livewire\Administrator;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\File;

class Settings extends Component
{
    use WithFileUploads;

    public $sigap_logo;
    public $siparsi_logo;
    public $admin_logo;
    public $simars_logo;

    public function render()
    {
        return view('livewire.administrator.settings', [
            'hasSigapLogo' => file_exists(public_path('logos/sigap_logo.png')),
            'hasSiparsiLogo' => file_exists(public_path('logos/siparsi_logo.png')),
            'hasAdminLogo' => file_exists(public_path('logos/admin_logo.png')),
            'hasSimarsLogo' => file_exists(public_path('logos/simars_logo.png')),
        ])->layout('components.layouts.admin')->title('Pengaturan Aplikasi');
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'sigap_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048|dimensions:ratio=1/1',
            'siparsi_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048|dimensions:ratio=1/1',
            'admin_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048|dimensions:ratio=1/1',
            'simars_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048|dimensions:ratio=1/1',
        ]);
    }

    public function uploadLogo($module)
    {
        $propName = $module . '_logo';

        $this->validate([
            $propName => 'required|image|mimes:jpeg,png,jpg,webp|max:2048|dimensions:ratio=1/1',
        ]);

        $file = $this->{$propName};

        // Save as generic PNG name regardless of original extension
        // to simplify checking existence in views
        $filename = $module . '_logo.png';

        // Livewire temporary files shouldn't use move() as they are already moved from $_FILES.
        File::ensureDirectoryExists(public_path('logos'));
        File::copy($file->getRealPath(), public_path('logos/' . $filename));

        $this->{$propName} = null;

        $this->dispatch('swal', [
            'title' => 'Sukses!',
            'text' => 'Logo modul ' . strtoupper($module) . ' berhasil diperbarui.',
            'icon' => 'success'
        ]);
    }

    public function deleteLogo($module)
    {
        $filename = $module . '_logo.png';
        $path = public_path('logos/' . $filename);

        if (File::exists($path)) {
            File::delete($path);
            $this->dispatch('swal', [
                'title' => 'Terhapus!',
                'text' => 'Logo modul ' . strtoupper($module) . ' berhasil dihapus. Aplikasi akan menggunakan ikon bawaan.',
                'icon' => 'success'
            ]);
        }
    }
}

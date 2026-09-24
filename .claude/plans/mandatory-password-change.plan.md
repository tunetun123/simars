# Plan: Mandatory Password Change & UX Improvements

**Complexity**: Small

## Summary
Memaksa pengguna yang masih menggunakan password default (`password123`) untuk segera mengubahnya saat pertama kali login. Selain itu, menambahkan tombol "Ubah Password" pada navigasi atas agar bisa digunakan kapan saja, serta menambahkan teks panduan lupa password di halaman login.

## Patterns to Mirror
| Category | Source | Pattern |
|---|---|---|
| Naming | `app/Livewire/Auth/Login.php` | Menggunakan Livewire component di dalam `App\Livewire\Auth\` untuk fungsionalitas autentikasi |
| Routes | `routes/web.php` | Mendaftarkan route dengan penamaan rapi seperti `change-password` |
| Middleware | `bootstrap/app.php` | Mendaftarkan middleware global pada grup `web` jika diperlukan |

## Files to Change
| File | Action | Why |
|---|---|---|
| `app/Http/Middleware/ForcePasswordChange.php` | CREATE | Middleware untuk mengecek jika password user adalah password default, arahkan paksa ke halaman ubah password |
| `bootstrap/app.php` | UPDATE | Mendaftarkan middleware `ForcePasswordChange` ke dalam grup middleware `web` |
| `app/Livewire/Auth/ChangePassword.php` | CREATE | Logika Livewire untuk validasi dan update password ke database |
| `resources/views/livewire/auth/change-password.blade.php` | CREATE | Tampilan form ubah password |
| `routes/web.php` | UPDATE | Menambahkan route `/change-password` |
| `resources/views/components/layouts/app.blade.php` | UPDATE | Menambahkan tombol "Ubah Password" di sebelah informasi akun pada navbar atas |
| `resources/views/livewire/auth/login.blade.php` | UPDATE | Menambahkan teks panduan jika pengguna lupa password |

## Tasks
### Task 1: Create ForcePasswordChange Middleware
- **Action**: Buat middleware `ForcePasswordChange` yang mengecek apakah user sedang login dan menggunakan password default (`password123`). Jika ya, arahkan ke route `change-password`.
- **Mirror**: Pengecekan route dengan `$request->routeIs()` untuk mencegah redirect loop.

### Task 2: Create ChangePassword Component
- **Action**: Buat class Livewire `ChangePassword` beserta view-nya. Membutuhkan field: `current_password`, `password`, `password_confirmation`. 
- **Action**: Jika user di-redirect paksa ke sini, form ini wajib diisi sebelum bisa mengakses halaman lain.

### Task 3: Update Routing & Registration
- **Action**: Daftarkan route `/change-password` di `routes/web.php` di dalam grup auth.
- **Action**: Daftarkan middleware di `bootstrap/app.php`.

### Task 4: UI Updates
- **Action**: Edit `resources/views/components/layouts/app.blade.php` untuk menambahkan link/tombol Ubah Password di samping Logout.
- **Action**: Edit `resources/views/livewire/auth/login.blade.php` untuk menambahkan teks "Lupa password? Silakan hubungi tim IT RS Bhayangkara Palu" di bawah tombol login.

## Validation
```bash
php artisan optimize:clear
```

## Risks
| Risk | Likelihood | Mitigation |
|---|---|---|
| Redirect Loop | Medium | Middleware harus mengecek `$request->routeIs('change-password')` dan `logout` agar user tidak terjebak |
| Pengguna lama terkunci | Low | Seluruh user yang belum mengganti default password (`password123`) akan langsung diarahkan saat login berikutnya |

## Acceptance
- [ ] Pengguna dengan password default ter-redirect ke `/change-password` saat login
- [ ] Pengguna tidak bisa melewati form jika password masih default
- [ ] Tombol Ubah Password muncul di navbar
- [ ] Tulisan IT RS Bhayangkara Palu muncul di halaman login

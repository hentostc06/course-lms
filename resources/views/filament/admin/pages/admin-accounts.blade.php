<x-filament-panels::page>
@if($notice)<div role="status" style="padding:12px;border-left:3px solid #D94A2F;background:#FFF0E9;color:#612B1E">{{ $notice }}</div>@endif
@if($errors->any())<div role="alert" style="color:#B83725">{{ $errors->first() }}</div>@endif
<x-filament::section heading="Administrator terdaftar" description="Hanya superadmin yang dapat menambah dan mengatur akun administrator.">
 <div style="overflow-x:auto"><table style="width:100%;border-collapse:collapse;text-align:left"><thead><tr><th style="padding:12px">Nama</th><th>Email</th><th>Hak akses</th><th>Tindakan</th></tr></thead><tbody>
 @foreach($this->getAdmins() as $admin)<tr style="border-top:1px solid #ccc"><td style="padding:12px">{{ $admin->name }}</td><td>{{ $admin->email }}</td><td>{{ $admin->hasRole('super_admin') ? 'Superadmin' : 'Admin' }}</td><td>@if(!$admin->hasRole('super_admin'))<x-filament::button size="sm" color="gray" wire:click="selectAdmin({{ $admin->id }})">Reset password</x-filament::button>@else—@endif</td></tr>@endforeach
 </tbody></table></div>
</x-filament::section>
@if($selectedAdminId)
<x-filament::section heading="Reset password admin #{{ $selectedAdminId }}" description="Gunakan password baru minimal 12 karakter, kombinasi huruf besar, kecil, dan angka.">
 <form wire:submit="resetAdminPassword" style="display:grid;gap:14px;max-width:530px">
 <label>Password baru <input wire:model="resetPassword" type="password" required autocomplete="new-password" minlength="12" style="display:block;width:100%;padding:10px;background:white;color:#171716;border:1px solid #aaa;border-radius:4px"></label>
 <label>Konfirmasi password <input wire:model="resetPasswordConfirmation" type="password" required autocomplete="new-password" minlength="12" style="display:block;width:100%;padding:10px;background:white;color:#171716;border:1px solid #aaa;border-radius:4px"></label>
 <div><x-filament::button color="danger" type="submit" wire:confirm="Reset password admin dan cabut semua sesi login-nya?">Simpan password baru</x-filament::button></div>
 </form>
</x-filament::section>
@endif
<x-filament::section heading="Buat administrator baru" description="Akun baru menerima role admin biasa, bukan superadmin.">
 <form wire:submit="createAdmin" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:14px;max-width:760px">
 <label>Nama lengkap <input wire:model="name" type="text" required minlength="3" autocomplete="off" style="display:block;width:100%;padding:10px;background:white;color:#171716;border:1px solid #aaa;border-radius:4px"></label>
 <label>Email <input wire:model="email" type="email" required autocomplete="off" style="display:block;width:100%;padding:10px;background:white;color:#171716;border:1px solid #aaa;border-radius:4px"></label>
 <label>Password <input wire:model="password" type="password" required minlength="12" autocomplete="new-password" style="display:block;width:100%;padding:10px;background:white;color:#171716;border:1px solid #aaa;border-radius:4px"></label>
 <label>Konfirmasi password <input wire:model="password_confirmation" type="password" required minlength="12" autocomplete="new-password" style="display:block;width:100%;padding:10px;background:white;color:#171716;border:1px solid #aaa;border-radius:4px"></label>
 <div style="grid-column:1/-1"><x-filament::button type="submit">Buat akun admin</x-filament::button></div>
 </form>
</x-filament::section>
<x-filament::section heading="Ubah password superadmin saya" description="Memerlukan password lama; sesi login lain akan dicabut jika sesi tersimpan di database.">
 <form wire:submit="updateOwnPassword" style="display:grid;gap:14px;max-width:530px">
 <label>Password saat ini <input wire:model="currentPassword" type="password" required autocomplete="current-password" style="display:block;width:100%;padding:10px;background:white;color:#171716;border:1px solid #aaa;border-radius:4px"></label>
 <label>Password baru <input wire:model="ownPassword" type="password" required minlength="12" autocomplete="new-password" style="display:block;width:100%;padding:10px;background:white;color:#171716;border:1px solid #aaa;border-radius:4px"></label>
 <label>Konfirmasi password baru <input wire:model="ownPasswordConfirmation" type="password" required minlength="12" autocomplete="new-password" style="display:block;width:100%;padding:10px;background:white;color:#171716;border:1px solid #aaa;border-radius:4px"></label>
 <div><x-filament::button type="submit">Ubah password saya</x-filament::button></div>
 </form>
</x-filament::section>
</x-filament-panels::page>

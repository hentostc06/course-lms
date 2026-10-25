<x-filament-panels::page>
@php($stats = $this->getTotals())
@php($users = $this->getUsers())
<div class="grid gap-4 md:grid-cols-3">
 @foreach(['Pengguna terdaftar' => $stats['users'], 'Pengguna aktif (15 menit)' => $stats['active'], 'Sesi aktif (15 menit)' => $stats['sessions']] as $label => $number)
 <div style="border:1px solid #ccc;border-top:4px solid #D94A2F;border-radius:8px;padding:20px"><p style="font-size:13px">{{ $label }}</p><strong style="font-size:30px">{{ $number }}</strong></div>
 @endforeach
</div>
<x-filament::section heading="Direktori & aktivitas pengguna" description="Aktivitas hanya terukur jika SESSION_DRIVER=database. Aktif berarti ada permintaan dalam 15 menit, bukan pemantauan layar.">
 <label style="display:block;margin-bottom:14px">Cari pengguna <input type="search" wire:model.live.debounce.350ms="search" placeholder="Nama atau email" style="margin-left:12px;padding:9px;border:1px solid #aaa;border-radius:5px;background:#fff;color:#171716;min-width:230px"></label>
 <div style="overflow-x:auto"><table style="width:100%;border-collapse:collapse;text-align:left"><thead><tr><th style="padding:12px">Nama</th><th>Email</th><th>Role</th><th>Sesi</th><th>Terakhir aktif</th><th>Status</th></tr></thead><tbody>
 @forelse($users as $user)
 @php($last = $user->last_seen ? \Illuminate\Support\Carbon::createFromTimestamp((int)$user->last_seen) : null)
 <tr style="border-top:1px solid #ccc"><td style="padding:12px">{{ $user->name }}</td><td>{{ $user->email }}</td><td>{{ $user->roles->pluck('name')->map(fn($name) => is_object($name) ? $name->value : $name)->implode(', ') ?: 'student' }}</td><td>{{ $user->session_count ?? 0 }}</td><td>{{ $last ? $last->timezone('Asia/Jakarta')->format('d M Y H:i') . ' WIB' : 'Belum tercatat' }}</td><td>{{ $last && $last->gte(now()->subMinutes(15)) ? 'Aktif' : 'Tidak aktif' }}</td></tr>
 @empty<tr><td colspan="6" style="padding:16px">Tidak ada pengguna.</td></tr>@endforelse
 </tbody></table></div>
 <div style="margin-top:16px">{{ $users->links() }}</div>
</x-filament::section>
</x-filament-panels::page>
